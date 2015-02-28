ZenAPI
=========

通过一个统一风格的OAuth2访问库，统一各大社交网站接口。

## 安装方法
ZenAPI需要php5.3+，唯一的依赖是curl extension。

ZenAPI遵循PSR-4规范。只需在composer.json中添加依赖：
```
    ...
    "require": {
        ...
        "shen2/zenapi": "dev-master"
    }
    ...
```

然后
```
$ composer install
```

## OAuth2类和授权流程
OAuht2类是包含了生成授权地址，获取token的方法。

+ 跳转到社交网站的授权页
```php
$config = array(
	'akey' => 'app key', 
	'skey' => 'secret key', 
	'scope' => 'email,friendships_groups_read',
);
$oauth = new ZenAPI\WeiboOAuth2($config['akey'], $config['skey']);  //初始化oauth
$params = array(
	'client_id'	=> $config['akey'],
	'redirect_uri'	=> 'callback',//设置回调
	'response_type'	=> 'code',
	'state'		=> 'made by md5 avoid crsf',
	'display'	=> null,
	'scope'		=> $config['scope'],
	'forcelogin'    => 0, //是否使用已登陆微博账号
);

$authorizeUrl = $oauth->authorizeURL() . "?" . http_build_query($params);
header('Location : ' . $authorizeUrl);
```

+ 获取access_token
```php
//微博返回的code
$keys = array(
	'code'	=> $_REQUEST['code'],
	'redirect_uri'=> '{{redirec_uri}}',
);

//获取token
$token = $oauth->getAccessToken('code', $keys);
```

## Client类和API访问方法
* 首先用之前获得的access_token实例化一个Client对象
* 然后就可以通过调用RESTful的方法，访问各种数据接口，如get()、post()、delete()

```php
//根据上一步的acces_token实例化Client对象
$client = new ZenAPI\WeiboClient($token['access_token']);

//根据uid获取用户信息
$info = $client->get('users/show', array('uid'=>1739476392)); 

//删除一条微博
$data = $client->post('comments/destory', array('uid'=>1739476392, 'cid' => 'weiboid'));
```

## 目前支持的社交网站
* 微博
* QQ
* 腾讯微博
* 人人
* 百度
* 搜狐
* 豆瓣
* 开心
* Google
* Instagram
* Github
* 优酷
* 土豆
* 爱奇艺
