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
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;

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

	/**
	 * access_token接口
	 *
	 * 对应API：{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	public function getAccessToken( $type = 'code', $keys ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new Exception("wrong auth type");
		}

		$response = $this->http($this->accessTokenURL(), 'POST', http_build_query($params));
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['error']) ) {
		} else {
			var_dump($response);var_dump($params);var_dump($token);	//modified by shen2, 用来调试
			throw new Exception("get access token failed." . $token['error']);
		}
		return $token;
	}
}
