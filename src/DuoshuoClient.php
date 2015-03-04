<?php
namespace ZenAPI;

class DuoshuoClient extends BaseClient{
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
	public $host = "https://api.duoshuo.com/";
	
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
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}
	
	public function realUrl($url){
		return $this->host . $url . '.' . $this->format;
	}
}
