<?php
namespace ZenOAuth2;

class YoukuOAuth2 extends OAuth2Abstract {

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://openapi.youku.com/v2/oauth2/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://openapi.youku.com/v2/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://open.youku.com/docs?id=101
	 *
	 * @param string $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * @param string $response_type 支持的值包括 code 和token 默认值为code
	 * @param string $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * @return string
	 */
	public function getAuthorizeURL($url, $response_type = 'code', $state = NULL) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		
		return $this->authorizeURL() . "?" . http_build_query($params);
	}
}
