<?php
namespace ZenAPI;

class RenrenOAuth2 extends BaseClient{
	use OAuth2Trait;

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	public function accessTokenURL(){
		return 'https://graph.renren.com/oauth/token';
	}
	/**
	 * @ignore
	 */
	public function authorizeURL(){
		return 'https://graph.renren.com/oauth/authorize';
	}

	/**
	 * authorize接口
	 *
	 * @link http://wiki.connect.qq.com/%E4%BD%BF%E7%94%A8authorization_code%E8%8E%B7%E5%8F%96access_token
	 *
	 * @param array $params
	 	$url 授权后的回调地址,站外应用需与回调地址一致,站内应用需要填写canvas page的地址
	 	$response_type 支持的值包括 code 和token 默认值为code
	 	$scope
	 	$state 用于保持请求和回调的状态。在回调时,会在Query Parameter中回传该参数
	 	$display 授权页面类型
	 	$x_renew 是否强制用户重新登录，true：是，false：否。默认false。
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
			throw new Exception($token['error'] . ', ' . $token['error_description'], $token['error_code']);
		}
		return $token;
	}
}
