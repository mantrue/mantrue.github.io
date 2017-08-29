---
layout: post
title:  php多进程批量下载图片
author: 承鹏辉
category: php
---

```
原创 并发执行，一秒完毕
当前执行脚本目录创建Uploads目录
cli模式下执行文件

```

### 上代码
```

<?php

$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
$imgrep[] = 'http://img.alicdn.com/bao/uploaded/i3/TB1PghnRFXXXXcJXXXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg';
// 必须加载扩展
if (!function_exists("pcntl_fork")) {
    die("pcntl extention is must !");
}
//总进程的数量
//$totals = 50;
// 执行的脚本数量的数组
pcntl_signal(SIGCHLD, SIG_IGN);    //如果父进程不关心子进程什么时候结束,子进程结束后，内核会回收。

for ($i = 0; $i < count($imgrep); $i++) {
    $pid = pcntl_fork();    //创建子进程
    $filepath = 'Uploads'; //远程图片要保存的路径
    //父进程和子进程都会执行下面代码
    if ($pid == -1) {
        //错误处理：创建子进程失败时返回-1.
        die('could not fork');
    } else if ($pid) { //这里$pid>0
        //父进程会得到子进程号，所以这里是父进程执行的逻辑
        //如果不需要阻塞进程，而又想得到子进程的退出状态，则可以注释掉pcntl_wait($status)语句，或写成：
        pcntl_wait($status,WNOHANG); //等待子进程中断，防止子进程成为僵尸进程。
    } else { //这里$pid=0
        //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
        writeImage($imgrep[$i],$filepath);
	    file_put_contents("test.txt", $imgrep[$i], FILE_APPEND);
        exit($i);
    }
}

echo "wanbi";

function writeImage($url, $filepath) {
    if ($url == '') {  
        return false;  
    }  
    $ext = strrchr($url, '.');  
  
    if ($ext != '.gif' && $ext != '.jpg' && $ext != '.png') {  
        return false;  
    }
    //判断路经是否存在
    !is_dir($filepath)?mkdir($filepath):null;  
  
    //获得随机的图片名，并加上后辍名  
    $filetime = time();  
    $filename = date("YmdHis",$filetime).rand(100,999).'.'.substr($url,-3,3);

    //读取图片  
    ob_start();  
    @readfile($url); 
    $img=ob_get_contents();  
    ob_end_clean();
    //指定打开的文件
    $fp = @ fopen($filepath.'/'.$filename, 'a');
    //写入图片到指定的文本  
    fwrite($fp, $img);
    fclose($fp);
    return '/'.$filepath.'/'.$filename;
}

function sendCurl ( $url ) {
    $curl = curl_init();
    curl_setopt( $curl,CURLOPT_URL,$url );
    curl_setopt( $curl,CURLOPT_RETURNTRANSFER,true );
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//规避证书
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 防止302 盗链
    $output = curl_exec( $curl );
    curl_close( $curl );
    return $output;
}

1秒完毕 下载松松的 发散下思维，接下来实现一个多进程基于redis的队列

```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉