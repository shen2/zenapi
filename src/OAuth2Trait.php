<?php
namespace ZenAPI;

trait OAuth2Trait {
	/**
	 * @ignore
	 */
	public $client_id;
	/**
	 * @ignore
	 */
	public $client_secret;

	/**
	 * Set API URLS
	 */
	abstract public function accessTokenURL();
	
	abstract public function authorizeURL();

	/**
	 * construct self object
	 */
	public function __construct($client_id, $client_secret) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
	}
	
	/**
	 * authorize接口
	 * 
	 * @param array $params
	 * @return string
	 */
	public function getAuthorizeURL(array $params) {
		$defaults = array(
			'client_id'	=> $this->client_id,
			'response_type'=> 'code',
		);
		
		return $this->authorizeURL() . "?" . http_build_query($params + $defaults);
	}
	
	/**
	 * access_token接口
	 *
	 * @link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	public function getAccessToken( $type = 'code', $keys ) {
		$params = $keys;
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
		
		return $this->_tokenFilter($response);
	}
	
	protected function _tokenFilter($response){
		return json_decode($response, true);
	}
}
