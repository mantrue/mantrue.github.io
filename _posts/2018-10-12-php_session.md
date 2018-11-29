---
layout: post
title:  php跨域session共享
author: 承鹏辉
category: php
---

```
记录下


```


### 上代码

```

<?php
//主域名配置 www.phptest.me
ini_set('session.cookie_path', '/');
 
ini_set('session.cookie_domain', '.phptest.me');
 
ini_set('session.cookie_lifetime', '1800');

ini_set("session.save_handler", "redis");
ini_set("session.save_path", "xxx"); //redis地址

session_start();

if(isset($_GET["login"])) { 
	$_SESSION['user'] = array('name' => 'login1', 'pass' => 123456);
}

if(isset($_GET["loginout"])) { 
	unset($_SESSION['user']);
}

//存入session
if (isset($_SESSION['user'])) {
	print_r("登录成功"."<a href='http://bbs.phptest.me/demo.php' target='view_window'>GO SHOPING</a><br/>");
	
	print_r("退出"."<a href='./demo.php?loginout=out'>login out</a>");
} else {
	print_r("还没有登录"."<a href='./demo.php?login=user'>立即登录</a>");
}



```

```

<?php
//二级域名配置 bbs.phptest.me
ini_set('session.cookie_path', '/');
 
ini_set('session.cookie_domain', '.phptest.me'); //注意jb51.net换成你自己的域名
 
ini_set('session.cookie_lifetime', '1800');

ini_set("session.save_handler", "redis");
ini_set("session.save_path", "xxx");//redis地址

session_start();

if(isset($_GET["login"])) { 
	$_SESSION['user'] = array('name' => 'login1', 'pass' => 123456);
}

if(isset($_SESSION['user'])) {
	$user = json_encode($_SESSION['user']);

	if(isset($user) && !empty($user)) { 
		print_r("用户信息如下:已登录".$user);die();
	}
} else {
	print_r("还没有登录"."<a href='./demo.php?login=user'>立即登录</a>");
}




```



### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉
