<?php
namespace ZenAPI;

class KaixinClient extends BaseClient{
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
	public $host = "https://api.kaixin001.com/";
	
	/**
	 *
	 * @param string $access_token
	 */
	public function __construct($access_token = NULL) {
		$this->access_token = $access_token;
	}
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}

	public function realUrl($url){
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = $this->host . $url . '.' . $this->format;
		}

		return $url;
	}
}
