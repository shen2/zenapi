<?php
namespace ZenOAuth2;

class InstagramOAuth2 extends OAuth2Abstract {
	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL()  { return 'https://api.instagram.com/oauth/access_token/'; }
	/**
	 * @ignore
	 */
	public function authorizeURL()    { return 'https://api.instagram.com/oauth/authorize/'; }

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		if (!is_array($token) || isset($token['error'])) {
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
}
