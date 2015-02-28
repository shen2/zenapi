<?php
namespace ZenAPI;

trait MethodOverride{

	public function put($url, $parameters = array()) {
		$this->_paramsFilter($parameters);
		$parameters['_method'] = 'PUT';
	
		$body = http_build_query($parameters);
		$response = $this->http($this->realUrl($url), 'POST', $body, $this->_additionalHeaders());
	
		return $this->parseResponse($response);
	}
	
	public function patch($url, $parameters = array()) {
		$this->_paramsFilter($parameters);
		$parameters['_method'] = 'PATCH';
	
		$body = http_build_query($parameters);
		$response = $this->http($this->realUrl($url), 'POST', $body, $this->_additionalHeaders());
	
		return $this->parseResponse($response);
	}
	
	public function delete($url, $parameters = array()) {
		$this->_paramsFilter($parameters);
		$parameters['_method'] = 'DELETE';
	
		$body = http_build_query($parameters);
		$response = $this->http($this->realUrl($url), 'POST', $body, $this->_additionalHeaders());
	
		return $this->parseResponse($response);
	}
}
