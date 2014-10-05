<?php
namespace ZenOAuth2;

class RenrenClient extends Client{
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.renren.com/v2/";
	
	protected function _additionalHeaders(){
		$headers = array();
		
		if ($this->access_token)
			$headers[] = "Authorization: Bearer ".$this->access_token;
		
		return $headers;
	}
}
