<?php
namespace ZenAPI;

class IqiyiOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://openapi.iqiyi.com/api/oauth2/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://openapi.iqiyi.com/api/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://open.iqiyi.com/lib/OAuth2.html
	 *
	 * @param array $params
	 * $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * $response_type 支持的值包括 code 和token 默认值为code
	 * $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * $display 授权页面类型
	 * @return string
	 */
	public function getAuthorizeURL(array $params) {
		$defaults = array(
				'client_id'	=> $this->client_id,
				'response_type'=> 'code',
		);
	
		return $this->authorizeURL() . "?" . http_build_query($params + $defaults);
	}
}
