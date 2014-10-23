<?php
namespace ZenOAuth2;

class InstagramClient extends Client{
	
	public $host = 'https://api.instagram.com/';
	
	protected function _paramsFilter(&$params){
		$params['key'] = $this->access_token;
	}
}
