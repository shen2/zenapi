<?php
namespace ZenOAuth2;

class SohutClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.t.sohu.com/";
	
	protected function _additionalHeaders(){
		$headers = array();

		if ($this->access_token)
			$headers[] = "Authorization: OAuth2 " . $this->access_token;

		return $headers;
	}

	public function realUrl($url){
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = $this->host . $url . '.' . $this->format;
		}

		return $url;
	}
}
