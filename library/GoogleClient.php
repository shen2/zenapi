<?php
namespace ZenOAuth2;

class GoogleClient extends Client{
	
	public $host = 'https://www.googleapis.com/';
	
	public function parseResponse($response){
		if ($this->format === 'json' && $this->decode_json) {
			$json = json_decode($response);		//	解析成对象而不是关联数组
			if ($json !== null)
				return $json;
		}
		return $response;
	}
	
	public function realUrl($url){
		return $this->host . $url;
	}
	
	protected function _paramsFilter(&$params){
		$params['key'] = $this->access_token;
	}
}
