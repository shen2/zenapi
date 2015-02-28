<?php
namespace ZenAPI;

class QqtOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://open.t.qq.com/cgi-bin/oauth2/access_token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://open.t.qq.com/cgi-bin/oauth2/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://wiki.open.t.qq.com/index.php/OAuth2.0%E9%89%B4%E6%9D%83
	 *
	 * @param array $params
	 * $url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 * $response_type 支持的值包括 code 和token 默认值为code
	 * $state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 * $display 授权页面类型 可选范围: 
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
		parse_str($response, $token);
		
		if (!is_array($token) || isset($token['code']) ) {
			throw new Exception("get access token failed." . $token['code'] . ':' . $token['msg']);
		}
		
		return $token;
	}
}
