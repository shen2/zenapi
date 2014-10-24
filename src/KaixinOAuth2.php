<?php
namespace ZenOAuth2;

class KaixinOAuth2 extends OAuth2Abstract {

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.kaixin001.com/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'http://api.kaixin001.com/oauth2/authorize';
	}

	public function getAuthorizeURL($url, $response_type = 'code', $scope = NULL, $state = NULL, $display = NULL, $oauth_client = NULL, $forcelogin = NULL) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['scope'] = $scope;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['oauth_client'] = $oauth_client;
		$params['forcelogin'] = $forcelogin;
		
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		
		if (!is_array($token) || isset($token['error']) ) {
			throw new Exception($token['error'] . ', ' . $token['request'], $token['error_code']);
		}
		return $token;
	}
}
