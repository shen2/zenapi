<?php
namespace ZenOAuth2;

class TudouOAuth2 extends OAuth2Abstract {

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://api.tudou.com/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://api.tudou.com/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://open.tudou.com/wiki/oauth2/authorize
	 *
	 * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * @param string $response_type 支持的值包括 code 和token 默认值为code
	 * @param string $scope
	 * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @param string $display 授权页面类型
	 * @return string
	 */
	public function getAuthorizeURL($url, $response_type = 'code', $scope = NULL, $state = NULL, $display = NULL) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		$params['scope'] = $scope;
		
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	protected function _tokenFilter($response){
		$token = json_decode($response, true);
		
		if (!is_array($token) || isset($token['error_code']) ) {
			throw new Exception($token['error_info'], $token['error_code']);
		}
		return $token;
	}
}
