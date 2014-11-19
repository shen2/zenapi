<?php
namespace ZenOAuth2;

class GoogleOAuth2 extends OAuth2Abstract {
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://www.googleapis.com/oauth2/v1/";

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL()  { return 'https://accounts.google.com/o/oauth2/token'; }
	/**
	 * @ignore
	 */
	public function authorizeURL()    { return 'https://accounts.google.com/o/oauth2/auth'; }

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		if (!is_array($token) || isset($token['error'])) {
			var_dump($response);var_dump($token);	//modified by shen2, 用来调试
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
}
