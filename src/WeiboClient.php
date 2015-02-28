<?php
namespace ZenAPI;

class WeiboClient extends BaseClient{
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
	public $host = "https://api.weibo.com/2/";
	
	/**
	 *
	 * @var string
	 */
	public $remote_ip = null;
	
	/**
	 * 
	 * @var string
	 */
	public $format = 'json';
	
	/**
	 *
	 * @param string $access_token
	 */
	public function __construct($access_token = NULL) {
		$this->access_token = $access_token;
	}
	
	protected function _additionalHeaders(){
		$headers = array();
		
		if ($this->access_token)
			$headers[] = "Authorization: OAuth2 ".$this->access_token;
		
		if (isset($this->remote_ip))
			$headers[] = "API-RemoteIP: " . $this->remote_ip;
		elseif(isset($_SERVER['REMOTE_ADDR']))
			$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
		
		return $headers;
	}
	
	/**
	 * 
	 * @param string $clientId
	 */
	public static function createByClientId($clientId){
		$instance = new self(null);
		$instance->client_id = $clientId;
		
		return $instance;
	}
	
	public function realUrl($url){
		return $this->host . $url . '.' . $this->format;
	}
}
