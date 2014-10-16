<?php
namespace ZenOAuth2;

class InstagramClient extends Client{
	
	public $host = 'https://api.instagram.com/v1/';
	
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

    protected function _getUserId(){

    }
}
