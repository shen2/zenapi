<?php
namespace ZenAPI;

class GithubOAuth2 extends BaseClient{
	use OAuth2Trait;
	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://github.com/login/oauth/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://github.com/login/oauth/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link https://developer.github.com/v3/oauth/#web-application-flow
	 *
	 * @param array $params
	 * $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * $response_type 支持的值包括 code 和token 默认值为code
	 * $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * $scope
	 * @return string
	 */
	public function getAuthorizeURL(array $params) {
		$defaults = array(
				'client_id'	=> $this->client_id,
				'response_type'=> 'code',
		);
	
		return $this->authorizeURL() . "?" . http_build_query($params + $defaults);
	}

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		
		if (!is_array($token) || isset($token['error']) ) {
			throw new Exception($token['error'] . ': ' . $token['error_description'] . ', ' . $token['error_uri']);
		}
		
		return $token;
	}

	protected function _additionalHeaders(){
		$headers = array();
		$headers[] = 'Accept: application/json';
		return $headers;
	}
}
