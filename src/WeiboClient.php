<?php
namespace ZenOAuth2;

class WeiboClient extends Client{
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
	
	protected function _additionalHeaders(){
		$headers = array();
		
		if ($this->access_token)
			$headers[] = "Authorization: OAuth2 ".$this->access_token;
		
		$headers[] = "API-RemoteIP: " . ($this->remote_ip ?: $_SERVER['REMOTE_ADDR']);
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
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = "{$this->host}{$url}.{$this->format}";
		}
		
		return $url;
	}
}
