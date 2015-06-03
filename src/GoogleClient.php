<?php
namespace ZenAPI;

class GoogleClient extends BaseClient{
	/**
	 * 
	 * @var string
	 */
	public $client_id;
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
	
	protected function _paramsFilter(&$params){
		if (isset($this->access_token))
			$params['access_token'] = $this->access_token;
		elseif(isset($this->client_id))
			$params['key'] = $this->client_id;
	}
}
