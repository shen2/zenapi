<?php
namespace ZenOAuth2;

class WeiboOAuth2 extends OAuth2Abstract {
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;
	
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
	 * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * @param string $response_type 支持的值包括 code 和token 默认值为code
	 * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @param string $display 授权页面类型 可选范围: 
	 *  - default		默认授权页面		
	 *  - mobile		支持html5的手机		
	 *  - popup			弹窗授权页		
	 *  - wap1.2		wap1.2页面		
	 *  - wap2.0		wap2.0页面		
	 *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数		
	 *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
	 * @return array
	 */
	public function getAuthorizeURL( $url, $response_type = 'code', $state = NULL, $display = NULL) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	/**
	 * access_token接口
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	public function getAccessToken( $type = 'code', $keys ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new Exception("wrong auth type");
		}

		$response = $this->http($this->accessTokenURL(), 'POST', http_build_query($params));
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['error']) ) {
			//$this->access_token = $token['access_token'];
			//if (isset($token['refresh_token'])) //	modified by shen2，新应用可能没有refresh_token
			//	$this->refresh_token = $token['refresh_token'];
		} else {
			var_dump($response);var_dump($params);var_dump($token);	//modified by shen2, 用来调试
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
				$this->access_token = $token['access_token'];
				$this->refresh_token = $token['refresh_token'];
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
		$headers[] = "API-RemoteIP: " . ($this->remote_ip ?: $_SERVER['REMOTE_ADDR']);
		return $headers;
	}
}
