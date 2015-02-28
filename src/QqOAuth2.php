<?php
namespace ZenAPI;

class QqOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://graph.qq.com/oauth2.0/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://graph.qq.com/oauth2.0/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://wiki.connect.qq.com/%E4%BD%BF%E7%94%A8authorization_code%E8%8E%B7%E5%8F%96access_token
	 *
	 * @param array $params
	 * $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * $response_type 支持的值包括 code 和token 默认值为code
	 * $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * $scope
	 * $display 授权页面类型 可选范围: 
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
		if (preg_match('/^callback\((.+)\);$/', $response, $matches))
			$token = json_decode($matches[1], true);
		else
			parse_str($response, $token);
		
		if (!is_array($token) || isset($token['error']) ) {
			throw new Exception("get access token failed." . $token['error'] . ':' . $token['error_description']);
		}
		
		return $token;
	}
	
	/**
	 * 
	 * @param string $access_token
	 * @throws Exception
	 * @return array
	 */
	public function getOpenid($access_token){
		$params = array(
			'access_token'	=> $access_token,
		);
		$response = $this->http('https://graph.qq.com/oauth2.0/me?' . http_build_query($params), 'GET');
		
		if (!preg_match('/^callback\((.+)\);$/', $response, $matches))
			return null;
		
		$json = json_decode($matches[1], true);
		
		if (isset($json['error']))
			throw new Exception($json['error_description'], $json['error']);
		
		return $json;
	}
}
