<?php
namespace ZenOAuth2;

class InstagramClient extends Client{
	
	public $host = 'https://api.instagram.com/v1/';
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}
}
