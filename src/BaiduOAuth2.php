<?php
namespace ZenAPI;

class BaiduOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://openapi.baidu.com/oauth/2.0/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://openapi.baidu.com/oauth/2.0/authorize';
	}

	/**
	 * 
	 * @param array $params
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['scope'] = $scope;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['force_login'] = $force_login;
		$params['confirm_login'] = $confirm_login;
		$params['login_type'] = $login_type;
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
			throw new Exception($token['error'] . ', ' . $token['error_description']);
		}
		return $token;
	}
}
