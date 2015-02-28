<?php
namespace ZenAPI;

class KaixinOAuth2 extends BaseClient{
	use OAuth2Trait;

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

	/**
	 * authorize url
	 * 
	 * @param array $params
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['scope'] = $scope;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['oauth_client'] = $oauth_client;
		$params['forcelogin'] = $forcelogin;
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
			throw new Exception($token['error'] . ', ' . $token['request'], $token['error_code']);
		}
		return $token;
	}
}
