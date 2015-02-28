<?php
namespace ZenAPI;

class YoukuClient extends BaseClient{
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
	public $host = "https://openapi.youku.com/v2/";
	
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
		$params['client_id'] = $this->client_id;
		$params['access_token'] = $this->access_token;
	}
}
