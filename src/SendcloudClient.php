<?php
namespace ZenAPI;

class SendcloudClient extends BaseClient{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://sendcloud.sohu.com/webapi/";
	
	/**
	 *
	 * @var string
	 */
	public $format = 'json';
	
	/**
	 * 
	 * @param string $api_user
	 * @param string $api_key
	 */
	public function __construct($api_user, $api_key) {
		$this->api_user = $api_user;
		$this->api_key = $api_key;
	}
	
	protected function _paramsFilter(&$params){
		$params['api_user'] = $this->api_user;
		$params['api_key'] = $this->api_key;
	}
	
	public function realUrl($url){
		return $this->host . $url . '.' . $this->format;
	}
}
