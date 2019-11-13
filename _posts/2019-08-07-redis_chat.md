---
layout: post
title: redis 聊天版 不可用，并发异常
author: 承鹏辉
category: php
---

```
redis 订阅 发布聊天

```

### chat.html

```
<meta charset="utf8">
<div style="width:300px;height:300px;margin:0 auto;border:1px solid #ccc"></div>
<center>
    <textarea style="width:300px;height:100px"></textarea><br/>
    <button>发送</button>
</center>
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<script>
    $("button").click(function(){
        var text = $("textarea").val();
        $.post("pub.php",{"content":text});
    });
 
    function getdata(){
        $.post("sub.php",function(data){
            if(data){
                $("div").append(data+"<br/>");
                $("textarea").val("");
                getdata();
            }
        });
    }
    getdata();
</script>
	
```

### redis.php
```
<?php
    header("content-type:text/html;charset=utf-8");
     
    ini_set('default_socket_timeout',-1);
     
    $redis = new Redis();
    $redis -> pconnect('localhost',6379);
	
```

### pub.php

```
<?php
include './redis.php';
 
$redis -> publish('tv1',$_POST['content']);
	
```

### sub.php

```
<?php
include './redis.php';
 
$redis -> subscribe(array('tv1'),'callback');
 
function callback($redis,$channel,$contect){
    echo $channel;
    echo ":";
    echo $contect;
    echo "<br/>";
    exit();
}
	
```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```

作者
@承鹏辉
