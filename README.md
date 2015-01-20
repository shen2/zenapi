ZenOAuth2
=========

一个更好更完善的OAuth2访问库

## 库的基本说明
*  oauth client类：集成了对授权后的各种访问借口如get、post
*  oauth OAuth2Abstract类：一个抽象类包含了如何授权，获取token的基本操作
*  各种平台类包括weibo、qq、renren

## 授权基本步骤：

+ 前往授权
```php
$config = array(
	'akey' => 'app key', 
	'skey' => 'secret key', 
	'scope' => 'email,friendships_groups_read'
	);
$oauth = new ZenOAuth2\WeiboOAuth2($config['akey'], $config['skey']);  //初始化oauth
$params = array(
	'client_id'		=> $config['akey'],
	'redirect_uri'	=> 'callback',//设置回调
	'response_type'	=> 'code',
	'state'		=> 'made by md5 avoid crsf',
	'display'	=> null,
	'scope'		=> $config['scope'],
	'forcelogin'    => 0, //是否使用已登陆微博账号
	);
header('Location :' . $oauth->authorizeURL() . "?" . http_build_query($params));
```
+ 获取授权码

 ```php
keys = array(
	'code'	=> $_REQUEST['code'], //微博返回的code
	'redirect_uri'=>'callback',
	);
$token = $oauth->getAccessToken('code', $keys);  //获取token
```

## 平台操作

```php
//根据上一步的授权码建立对象
$client = new ZenOAuth2\WeiboClient($token['access_token']);
//根据uid获取数据
$info = $client->get('users/show', array('uid'=>$token['uid'])); 
//删除一条微博
$data = $client->post('comments/destory', array('uid'=>$token['uid'], 'cid' => 'weiboid');  
```

