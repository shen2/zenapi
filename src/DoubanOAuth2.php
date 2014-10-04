<?php
namespace ZenOAuth2;

class DoubanOAuth2 extends OAuth2Abstract {
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
			var_dump($response);var_dump($params);var_dump($token);	//modified by shen2, 用来调试
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
}
