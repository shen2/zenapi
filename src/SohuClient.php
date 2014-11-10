<?php
namespace ZenOAuth2;

class SohuClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.sohu.com/rest/";
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}
}
