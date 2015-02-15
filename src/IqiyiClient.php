<?php
namespace ZenOAuth2;

class IqiyiClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://openapi.iqiyi.com/api/";
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}
}
