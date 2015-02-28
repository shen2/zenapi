<?php
namespace ZenAPI;

class SohuOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.sohu.com/oauth2/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://api.sohu.com/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link https://open.sohu.com/wiki/OAuth2%E4%BB%8B%E7%BB%8D
	 *
	 * @param array $params
	 	$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['scope'] = $scope;
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
			throw new Exception($token['error'] . ', ' . $token['error_description'], $token['error_code']);
		}
		return $token;
	}
}
