---
layout: post
title:  Linux网络编程 socket读写
author: 承鹏辉
category: linux
---

```
Liunx网络编程篇，会持续一段时间
php的方式进行实现

```

### 直接流程吧


### PHP实现

```
<?php  

$stop = false;

function handle_term( $sig ) {
    $stop = true;
}

pcntl_signal(SIGINT, "handle_term"); //设置退出的信号
//设置地址与端口  
$address = '127.0.0.1'; //服务端ip  
$port = 1234;  
//创建socket：AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6 ， SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM  
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() failed : ".socket_strerror(socket_last_error()). "\n");  

#通过设置这个选项，进行端口的重用
socket_set_option($sock,SOL_SOCKET,SO_REUSEADDR,1);

//阻塞模式  
socket_set_block($sock) or die("socket_set_block() failed : ".socket_strerror(socket_last_error()) ."\n");  
  
//绑定到socket端口  
$result = socket_bind($sock, $address, $port) or die("socket_bind() failed : ". socket_strerror(socket_last_error()) . "\n");  
//开始监听  
$result = socket_listen($sock, 4) or die("socket_listen() failed : ". socket_strerror(socket_last_error()) . "\n");  //定义连接数4个


$clients = array($sock);

do{

    $read = $clients;
    $write = null;
    $expect = null;
    //当没有套字节可以读写继续等待， 第四个参数为null为阻塞， 为0位非阻塞， 为 >0 为等待时间
    if(socket_select($read, $write, $expect, 0) < 1) {
        continue;
    }

    $clients[] = $fd = socket_accept($sock);

    if ( $fd === false) { //该函数是阻塞的 直到有一个链接进来
        echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }

     /* Send instructions. */
    $msg = "Welcome to the PHP Test Server. \n";
    socket_write($fd, $msg, strlen($msg));

    do {
        if (false === ($buf = socket_read($fd, 2048, PHP_NORMAL_READ))) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($fd)) . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            continue;
        }
        if ($buf == 'quit') {
            break;
        }
        if ($buf == 'shutdown') {
            socket_close($fd);
            break 2;
        }
        $talkback = "PHP: You said '$buf'.\n";
        socket_write($fd, $talkback, strlen($talkback));
        echo "$buf\n";
    } while (true);
    
    socket_close($fd);
    
} while (true) ;
    
socket_close($sock);

```

### 执行方式

```
telnet 127.0.0.1 12345 
netstat -ant | grep 12345 
看链接状态  ESTABLISHED SYN_RCVD

ESTABLISHED状态是表示两台机器正在传输数据

SYN_RCVD 半链接状态

```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉
