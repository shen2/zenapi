<?php
namespace ZenAPI;

class QqtClient extends BaseClient{
	/**
	 * 
	 * @var string
	 */
	public $access_token;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://open.t.qq.com/api/";
	
	/**
	 *
	 * @var string
	 */
	public $remote_ip = null;
	
	/**
	 * 
	 * @var string|int
	 */
	protected $client_id;
	
	/**
	 * 
	 * @var string
	 */
	protected $openid;
	
	/**
	 * 
	 * @param string $access_token
	 * @param string $oauth_consumer_key
	 * @param string $open_id
	 */
	public function __construct($access_token, $client_id, $openid) {
		$this->access_token = $access_token;
		$this->client_id = $client_id;
		$this->openid = $openid;
	}
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
		$params['oauth_consumer_key'] = $this->client_id;
		$params['openid'] = $this->openid;
		$params['format'] = 'json';
		$params['oauth_version'] = '2.a';
		
		if (isset($this->remote_ip))
			$params['clientip'] = $this->remote_ip;
	}
}
