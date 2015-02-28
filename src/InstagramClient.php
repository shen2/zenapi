<?php
namespace ZenAPI;

class InstagramClient extends BaseClient{
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
	public $host = 'https://api.instagram.com/v1/';
	
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
}
