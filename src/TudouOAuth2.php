<?php
namespace ZenAPI;

class TudouOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.tudou.com/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://api.tudou.com/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://open.tudou.com/wiki/oauth2/authorize
	 *
	 * @param array $params
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['scope'] = $scope;
	 * 
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
		
		if (!is_array($token) || isset($token['error_code']) ) {
			throw new Exception($token['error_info'], $token['error_code']);
		}
		return $token;
	}
}
