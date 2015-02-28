<?php
namespace ZenAPI;

class GoogleOAuth2 extends BaseClient{
	use OAuth2Trait;
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
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
}
