---
layout: post
title:  php多进程信号捕获与学习
author: 承鹏辉
category: php
---

```
php多进程信号捕获与学习
包括socket链接，多进程维护

```


### 上代码

```

<?php

if (file_exists("nginx.pid")) {
	unlink("nginx.pid");
}

$host = '0.0.0.0';
$port = 9999;

$listen_socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );

socket_bind( $listen_socket, $host, $port );

socket_listen( $listen_socket );


pcntl_signal(SIGTERM, 'signalHandal');
pcntl_signal(SIGHUP, 'signalHandal');
pcntl_signal(SIGUSR1, 'signalHandal');


  
cli_set_process_title( 'phpserverNginx master process' );

for( $i = 1; $i <= 10; $i++ ){
	$pid = pcntl_fork();
	if( 0 == $pid ){
		file_put_contents("nginx.pid", posix_getpid()."|", FILE_APPEND);
		cli_set_process_title( 'phpserverNginx worker process' );
		while( true ){
			$conn_socket = socket_accept( $listen_socket );
			$msg = "helloworld\r\n";
			socket_write( $conn_socket, $msg, strlen( $msg ) );
			sleep(5);
			socket_close( $conn_socket );
			exit;
		}
	}
}

JoinRun();


function JoinRun() {
	while( true ){
		sleep( 1 );
		pcntl_signal_dispatch();
	}
}

function signalHandal($signal)
{
	$pid = getmypid();
	switch ($signal) {
		case SIGTERM:
			echo "SIGHUP";
			echo "{$pid}  :SIGINT|exit";
			$pidinfo = file_get_contents("nginx.pid");
			$pidlist = explode("|",$pidinfo); 
			if (!empty($pidlist)) {
				foreach($pidlist as $k=>$id) {
					if (!empty($id)) {
						posix_kill($id,SIGINT);
					}
				}
			}
			unlink("nginx.pid");
			exit;
			break;
		case SIGHUP:
			echo "SIGHUP";
			echo "{$pid}  :SIGHUP|exit";
			break;
		case SIGUSR1:
			echo "SIGUSR1";
			echo "{$pid}  :SIGUSR1|exit";
			break;
		case SIGINT:
			unlink("nginx.pid");
			break;
		default :
			echo "{$pid}  :empty";
			unlink("nginx.pid");
			break;
	}	
}
socket_close( $connection_socket );


```



### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉
