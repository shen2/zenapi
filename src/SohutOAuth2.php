<?php
namespace ZenOAuth2;

class SohutOAuth2 extends OAuth2Abstract {

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.t.sohu.com/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://api.t.sohu.com/oauth2/authorize';
	}

	public function getAuthorizeURL($url, $response_type = 'code', $scope = 'basic', $state = NULL, $display = NULL, $forcelogin  = NULL) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['scope'] = $scope;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['isChangeUser'] = $forcelogin;

		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		
		if (!is_array($token) || isset($token['error']) ) {
			throw new Exception($token['error'] . ', ' . $token['description']);
		}
		return $token;
	}
}
