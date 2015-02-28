<?php
namespace ZenAPI;

class GoogleClient extends Client{
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
	public $host = 'https://www.googleapis.com/';
	
	/**
	 *
	 * @param string $access_token
	 */
	public function __construct($access_token = NULL) {
		$this->access_token = $access_token;
	}
	
	public function parseResponse($response){
		if ($this->format === 'json' && $this->decode_json) {
			$json = json_decode($response);		//	解析成对象而不是关联数组
			if ($json !== null)
				return $json;
		}
		return $response;
	}
	
	protected function _paramsFilter(&$params){
		$params['key'] = $this->access_token;
	}
}
