<?php
namespace ZenAPI;

class WeixinClient extends BaseClient{
    /**
     * 
     * @var string
     */
    public $access_token;

    /**
     *
     * @var string
     */
    protected $openid;
    
    /**
     * Set up the API root URL.
     *
     * @ignore
     */
    public $host = "https://api.weixin.qq.com/sns/";
    
    /**
     * 
     * @var string
     */
    public $format = 'json';
    
    /**
     *
     * @param string $access_token
     * @param string $openid
     */
    public function __construct($access_token = NULL, $openid = NULL) {
        $this->access_token = $access_token;
        $this->openid = $openid;
    }

    protected function _paramsFilter(&$params){
        $params['access_token'] = $this->access_token;
        $params['openid'] = $this->openid;
    }
}
