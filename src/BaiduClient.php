<?php
namespace ZenOAuth2;

class BaiduClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://openapi.baidu.com/";
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}
}
