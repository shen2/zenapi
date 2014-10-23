<?php
namespace ZenOAuth2;

class KaixinClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.kaixin001.com/";
	
	protected function _paramsFilter(&$params){
		$params['access_token'] = $this->access_token;
	}

	public function realUrl($url){
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = $this->host . $url . '.' . $this->format;
		}

		return $url;
	}
}
