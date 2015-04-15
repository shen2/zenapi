<?php
namespace ZenAPI;

class WeiboOAuth2 extends BaseClient{
	use OAuth2Trait;
	/**
	 * 
	 * @var string
	 */
	public $remote_ip = null;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.weibo.com/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://api.weibo.com/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
	 *
	 * @param array $params 授权参数
	 * $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * $response_type 支持的值包括 code 和token 默认值为code
	 * $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * $display 授权页面类型 可选范围: 
	 *  - default		默认授权页面		
	 *  - mobile		支持html5的手机		
	 *  - popup			弹窗授权页		
	 *  - wap1.2		wap1.2页面		
	 *  - wap2.0		wap2.0页面		
	 *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数		
	 *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
	 * @return string
	 */
	public function getAuthorizeURL(array $params) {
		$defaults = array(
			'client_id'	=> $this->client_id,
			'response_type'=> 'code',
		);
		
		return $this->authorizeURL() . "?" . http_build_query($params + $defaults);
	}

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		
		if (!is_array($token) || isset($token['error']) ) {
			throw new Exception("get access token failed." . $token['error']);
		}
		
		return $token;
	}

	/**
	 * 读取jssdk授权信息，用于和jssdk的同步登录
	 *
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	public function getTokenFromJSSDK() {
		$key = "weibojs_" . $this->client_id;
		if ( isset($_COOKIE[$key]) && $cookie = $_COOKIE[$key] ) {
			parse_str($cookie, $token);
			if ( isset($token['access_token']) && isset($token['refresh_token']) ) {
				return $token;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	protected function _additionalHeaders(){
		$headers = array();
		
		if (isset($this->remote_ip))
			$headers[] = "API-RemoteIP: " . $this->remote_ip;
		elseif(isset($_SERVER['REMOTE_ADDR']))
			$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
		
		return $headers;
	}
	
	/**
	 * require $this->redirect_uri
	 * @param string $userId email of user
	 * @param string $passwd password of user
	 * @return array
	 */
	public function getTokenByPassword($userId, $passwd){
		$postdata = array(
				'client_id'		=> $this->client_id,
				'response_type'	=> 'code',
				'redirect_uri'	=> $this->redirect_uri,
				'action'		=> 'submit',
				'userId'		=> $userId,
				'passwd'		=> $passwd,
				'isLoginSina'	=> 0,
				'from'			=> '',
				'regCallback'	=> '',
				'state'			=> '',
				'ticket'		=> '',
				'withOfficalFlag'=> 0,
		);
		
		$ch = curl_init ();
		
		curl_setopt ( $ch, CURLOPT_URL, $this->authorizeURL() );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_REFERER, $this->getAuthorizeURL(array('redirect_uri'	=> $this->redirect_uri)));
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)' );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($postdata) );
		
		$response = curl_exec ( $ch );
		$last_url = curl_getinfo ( $ch, CURLINFO_EFFECTIVE_URL );
		
		$urlArray = parse_url($last_url);
		
		if (!isset($urlArray['query'])){
			return false;
		}
		parse_str($urlArray['query'], $param);
		
		$keys = array (
			'code'	=> $param['code'],
			'redirect_uri'=> $this->redirect_uri,
		);
		
		return $this->getAccessToken ( 'code', $keys );
	}
}
