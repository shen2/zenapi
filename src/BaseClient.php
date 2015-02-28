<?php
namespace ZenAPI;

class BaseClient {
	/**
	 * 
	 * @var curl Handle
	 */
	protected $_curlHandle;
	
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host;
	
	/**
	 * 
	 * @var array
	 */
	protected $_curlOptions = array(
		CURLOPT_HTTP_VERSION	=> CURL_HTTP_VERSION_1_0,
		CURLOPT_USERAGENT		=> 'ZenAPI v0.3',
		CURLOPT_CONNECTTIMEOUT	=> 30,
		CURLOPT_TIMEOUT			=> 30,
		CURLOPT_SSL_VERIFYPEER	=> FALSE,
	);
	
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_header = array();
	
	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;

	/**
	 * 
	 * @param string $host
	public function __construct($host = '') {
		$this->host = $host;
	}
	 */
	
	public function setCurlOptions(array $options){
		$this->_curlOptions = array_merge($this->_curlOptions, $options);
	}
	
	protected function _paramsFilter(&$params){
	}

	/**
	 * 
	 * @param string $response
	 * @throws Exception
	 * @return array
	 */
	public function parseResponse($response){
		$json = json_decode($response, true);
		if ($json === null)
			throw new Exception($response);
		
		return $json;
	}
	
	/**
	 * GET shorthand
	 *
	 * @return mixed
	 */
	public function get($url, $parameters = array()) {
		$this->_paramsFilter($parameters);
		
		$response = $this->http($this->realUrl($url) . (empty($parameters) ? '' : '?' . http_build_query($parameters)), 'GET', null, $this->_additionalHeaders());
		
		return $this->parseResponse($response);
	}

	/**
	 * POST shorthand
	 *
	 * @return mixed
	 */
	public function post($url, $parameters = array()) {
		$this->_paramsFilter($parameters);
		
		$body = http_build_query($parameters);
		$response = $this->http($this->realUrl($url), 'POST', $body, $this->_additionalHeaders());
		
		return $this->parseResponse($response);
	}

	public function postMulti($url, $parameters = array()) {
		$this->_paramsFilter($parameters);

		$boundary = uniqid('------------------');
		$body = self::build_http_query_multi($parameters, $boundary);
		$headers = array("Content-Type: multipart/form-data; boundary=" . $boundary);

		$response = $this->http($this->realUrl($url), 'POST', $body, array_merge($headers, $this->_additionalHeaders()));

		return $this->parseResponse($response);
	}

	/**
	 * DELTE shorthand
	 *
	 * @return mixed
	 */
	public function delete($url, $parameters = array()) {
		$this->_paramsFilter($parameters);
		
		$response = $this->http($this->realUrl($url) . '?' . http_build_query($parameters), 'DELETE');
		
		return $this->parseResponse($response);
	}

	public function put($url, $parameters = array()) {
		$this->_paramsFilter($parameters);

		$body = http_build_query($parameters);
		$response = $this->http($this->realUrl($url), 'PUT', $body, $this->_additionalHeaders());

		return $this->parseResponse($response);
	}

	public function putMulti($url, $parameters = array()) {
		$this->_paramsFilter($parameters);

		$boundary = uniqid('------------------');
		$body = self::build_http_query_multi($parameters, $boundary);
		$headers = array("Content-Type: multipart/form-data; boundary=" . $boundary);

		$response = $this->http($this->realUrl($url), 'PUT', $body, array_merge($headers, $this->_additionalHeaders()));

		return $this->parseResponse($response);
	}

	public function patch($url, $parameters = array()) {
		$this->_paramsFilter($parameters);

		$body = http_build_query($parameters);
		$response = $this->http($this->realUrl($url), 'PATCH', $body, $this->_additionalHeaders());

		return $this->parseResponse($response);
	}

	public function patchMulti($url, $parameters = array()) {
		$this->_paramsFilter($parameters);

		$boundary = uniqid('------------------');
		$body = self::build_http_query_multi($parameters, $boundary);
		$headers = array("Content-Type: multipart/form-data; boundary=" . $boundary);

		$response = $this->http($this->realUrl($url), 'PATCH', $body, array_merge($headers, $this->_additionalHeaders()));

		return $this->parseResponse($response);
	}

	/**
	 * 
	 * @return array
	 */
	protected function _additionalHeaders(){
		return array();
	}
	
	public function realUrl($url){
		return $this->host . $url;
	}
	
	/**
	 * Make an HTTP request
	 * 
	 * @param string $url
	 * @param string $method
	 * @param string $postfields
	 * @param array $headers
	 * @throws CurlException
	 * @return string API results
	 */
	public function http($url, $method, $postfields = NULL, $headers = array()) {
		if ($this->_curlHandle !== null){
			curl_close($this->_curlHandle);
			$this->http_header = array();
		}
		
		$this->_curlHandle = $ci = curl_init();
		
		/* Curl settings */
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, function($ch, $header){
			$this->http_header[] = $header;
			return strlen($header);
		});
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
		
		curl_setopt_array($ci, $this->_curlOptions);
		
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				break;
			case 'GET':
				curl_setopt($ci, CURLOPT_POST, FALSE);
				break;
			default:
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method);
		}

		if (!empty($postfields))
			curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);

		$response = curl_exec($ci);
		
		if ($response === false){
	    	throw new CurlException(curl_error($ci), curl_errno($ci));
		}
		
		if ($this->debug) {
			echo "=====post data======\r\n";
			var_dump($postfields);

			echo '=====info====='."\r\n";
			print_r( curl_getinfo($this->_curlHandle) );

			echo '=====$response====='."\r\n";
			print_r( $response );
		}
		return $response;
	}
	
	public function __destruct(){
		if ($this->_curlHandle !== null){
			curl_close($this->_curlHandle);
		}
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getInfo(){
		return curl_getinfo($this->_curlHandle);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getUrl(){
		return curl_getinfo($this->_curlHandle, CURLINFO_EFFECTIVE_URL);
	}

	/**
	 * Get the header info
	 *
	 * @return array
	 */
	public function getHeader() {
		$header = array();
		foreach($this->http_header as $line){
			$i = strpos($line, ':');
			if (!empty($i)) {
				$key = str_replace('-', '_', strtolower(substr($line, 0, $i)));
				$value = trim(substr($line, $i + 2));
				$header[$key] = $value;
			}
		}
		return $header;
	}

	/**
	 * 
	 * @param array $params
	 * @param string $boundary
	 * @return string
	 */
	public static function build_http_query_multi($params, $boundary) {
		if (!$params) return '';

		//uksort($params, 'strcmp');

		$multipartbody = '';

		foreach ($params as $parameter => $value) {
			if(	$value instanceof File) {
				$multipartbody .= '--' . $boundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $value->filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: ' . $value->mime . '\r\n\r\n";
				$multipartbody .= $value->content. "\r\n";
			}
			else {
				$multipartbody .= '--' . $boundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}
		}

		$multipartbody .= '--' . $boundary. '--';
		return $multipartbody;
	}
}
