<?php
namespace ZenOAuth2;

class GithubClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.github.com/";
	
	protected function _additionalHeaders(){
		$headers = array();
		
		if ($this->access_token)
			$headers[] = "Authorization: token ".$this->access_token;
		
		return $headers;
	}
}
