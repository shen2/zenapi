<?php
namespace ZenAPI;

class QqClient extends BaseClient{
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
	public $host = "https://graph.qq.com/";
	
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
	}
	
	protected function _additionalHeaders(){
		$headers = array();
		
		if ($this->access_token)
			$headers[] = "Authorization: OAuth2 ".$this->access_token;
		
		return $headers;
	}
}
