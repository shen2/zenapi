<?php
namespace ZenAPI;

class TudouClient extends BaseClient{
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
	public $host = 'https://api.tudou.com/v6/';
	
	/**
	 *
	 * @var string
	 */
	public $format = 'json';
	
	/**
	 * 
	 * @param string $access_token
	 * @param string $client_id
	 */
	public function __construct($access_token = NULL, $client_id = NULL) {
		$this->access_token = $access_token;
		$this->client_id = $client_id;
	}
	
	protected function _paramsFilter(&$params){
		$params['app_key'] = $this->client_id;
		$params['format'] = $this->format;
		$params['access_token'] = $this->access_token;
	}
}
