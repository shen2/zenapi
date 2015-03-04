<?php
namespace ZenAPI;

class DuoshuoOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.duoshuo.com/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://api.duoshuo.com/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @param array $params
	 	$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
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
		
		return $token;
	}
}
