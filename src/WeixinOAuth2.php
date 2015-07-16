<?php
namespace ZenAPI;

class WeixinOAuth2 extends BaseClient{
    use OAuth2Trait;

    /**
     * Set API URLS
     */
    /**
     * @ignore
     */
    public function accessTokenURL(){
        return 'https://api.weixin.qq.com/sns/oauth2/access_token';
    }
    /**
     * @ignore
     */
    public function authorizeURL(){
        return 'https://open.weixin.qq.com/connect/qrconnect';
    }

    /**
     * authorize接口
     *
     * @link https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&id=open1419316505
     *
     * @param array $params 授权参数
     * @return string
     */
    public function getAuthorizeURL(array $params) {
        $defaults = array(
            'appid' => $this->client_id,
            'response_type'=> 'code',
        );
        
        return $this->authorizeURL() . "?" . http_build_query($params + $defaults);
    }
    
    /**
     * access_token接口
     *
     * @link https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&id=open1419316505
     *
     * @param string $type 请求的类型,可以为:code
     * @param array $keys 其他参数：
     *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
     * @return array
     */
    public function getAccessToken( $type = 'code', $keys ) {
        $params = array(
            'appid' => $this->client_id,
            'secret'=> $this->client_secret,
        );
        if ( $type === 'code' ) {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $keys['code'];
        } else {
            throw new Exception("wrong auth type");
        }
    
        $response = $this->http($this->accessTokenURL(), 'POST', http_build_query($params));
    
        return $this->_tokenFilter($response);
    }

    /**
     * {"errcode":40029,"errmsg":"invalid code"}
     */
    protected function _tokenFilter($response){
        $token = json_decode($response, true);
        
        if (!is_array($token)){
            throw new Exception("parse error:" . (string) $token);
        }
        elseif (isset($token['errcode'])) {
            throw new Exception("get access token failed." . $token['errmsg'], $token['errcode']);
        }
        
        return $token;
    }
}
