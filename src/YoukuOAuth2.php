<?php
namespace ZenAPI;

class YoukuOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://openapi.youku.com/v2/oauth2/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://openapi.youku.com/v2/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://open.youku.com/docs?id=101
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
}
