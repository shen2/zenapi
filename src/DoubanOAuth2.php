<?php
namespace ZenAPI;

class DoubanOAuth2 extends BaseClient{
	use OAuth2Trait;
	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://www.douban.com/service/auth2/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://www.douban.com/service/auth2/auth';
	}

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		
		if (!is_array($token) || isset($token['error']) ) {
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
}
