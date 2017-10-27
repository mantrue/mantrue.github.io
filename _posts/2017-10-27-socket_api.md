---
layout: post
title:  Linux网络编程 基础API
author: 承鹏辉
category: linux
---

```
Liunx网络编程篇，会持续一段时间
c和php的方式进行实现

```

### 直接流程吧

```
socket 在网络编程接口中表示socket地址的 是socketaddr_un结构体 c语言 sa_family_t 地址类型 char sun_path socekt地址值
socketaddr_in 是ipv4 socketaddr_in6 是ipv6

inet_addr 把十进制的ipv4转化为网络字节整数表示

创建socket socket就是可读可写 可控制 可关闭的文件描述符
socket 失败会返回-1
bind 就是为socket命名 绑定把地址绑定在哪个端口上

socket命名后还不能接受客户端的链接 需要一个监听的队列

下面编写一个服务器代码

```

### C语言实现

```
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <signal.h>
#include <assert.h>
#include <stdio.h>
#include <string.h>
#include <stdbool.h>

static bool stop = false;

static void handle_term( int sig ){
	stop = true;
}

int main( int argc, char* argv[]) {
	signal(SIGTERM,handle_term); //程序结束(terminate)信号, 与SIGKILL不同的是该信号可以被阻塞和处理。通常用来要求程序自己正常退出，shell命令kill缺省产生这个信号。如果进程终止不了，我们才会尝试SIGKIL
	if ( argc <=3 ) {
		printf("%s\n","请输入参数" );
		return 1;
	}
	const char* ip = argv[1]; //第一个参数ip地址
	int port = atoi( argv[2] ); //第二个参数端口号 将字符串转换为整型值
	int backlog = atoi( argv[3] ); //第三个参数backlog的值 将字符串转换为整型值 连接数

	int sock = socket( PF_INET,SOCK_STREAM, 0 ); //创建socket//AF_INET,PF_INET	IPv4 Internet协议  SOCK_STREAM	Tcp连接  SOCK_DGRAM	支持UDP连接
	assert( sock>= 0 ); //判断是否错误

	struct sockaddr_in address; //socket套接字结构体
	bzero( &address,sizeof( address ) );//bzero() 会将内存块（字符串）的前n个字节清零
	address.sin_family = AF_INET; //设置为IP通信
	inet_pton( AF_INET,ip,&address.sin_addr ); //检查ip地址合法性
	address.sin_port = htons( port ); //大端模式发送数据

	int ret = bind( sock,( struct sockaddr* )&address, sizeof(address) );
	assert( ret != -1 );

	ret = listen( sock,backlog ); //监听

	while ( !stop ) { //一直循环 直到监听到退出的信号
		sleep( 1 );
	}

	close( sock );
	return 0;

}

```

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
$port = 12345;  
//创建socket：AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6 ， SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM  
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() failed : ".socket_strerror(socket_last_error()). "\n");  
//阻塞模式  
socket_set_block($sock) or die("socket_set_block() failed : ".socket_strerror(socket_last_error()) ."\n");  
  
//绑定到socket端口  
$result = socket_bind($sock, $address, $port) or die("socket_bind() failed : ". socket_strerror(socket_last_error()) . "\n");  
//开始监听  
$result = socket_listen($sock, 4) or die("socket_listen() failed : ". socket_strerror(socket_last_error()) . "\n");  //定义连接数4个

while ( !$stop ) { //一直循环 直到监听到退出的信号
    sleep( 1 );
}


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

