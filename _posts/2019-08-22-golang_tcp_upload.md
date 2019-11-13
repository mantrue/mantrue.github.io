---
layout: post
title: golang  socket文件上传 php读取发送
author: 承鹏辉
category: php
---

```
socket文件上传

```

### main.go

```
package main

import (
	"fmt"
	"io"
	"net"
	"os"
)

func revFile(fileName string, conn net.Conn) {
	defer conn.Close()
	fs, err := os.Create(fileName)
	defer fs.Close()
	fmt.Println("服务器创建文件名为：", fileName)

	if err != nil {
		fmt.Println("os.Create err =", err)
		return
	}

	// 拿到数据
	buf := make([]byte, 1024)
	var num int
	for {
		n, err := conn.Read(buf)
		if err != nil {
			fmt.Println("conn.Read err =", err)
			if err == io.EOF {
				fmt.Println("文件结束了", err)
			}
			break
		}
		if n == 0 {
			fmt.Println("文件结束了", err)
			break
		}
		fs.Write(buf[:n])
		num++
	}
	fmt.Println("读取次数为", num)
	return
}
func main() {
	// 创建一个服务器
	Server, err := net.Listen("tcp", "127.0.0.1:8001")
	if err != nil {
		fmt.Println("net.Listen err =", err)
		return
	}
	fmt.Println("server start listent 127.0.0.1:8001")

	defer Server.Close()
	// 接受文件名
	for {
		conn, err := Server.Accept()
		fmt.Println("========有链接进来了========")
		defer conn.Close()
		if err != nil {
			fmt.Println("Server.Accept err =", err)
			return
		}
		buf := make([]byte, 1024)
		n, err1 := conn.Read(buf)
		if err1 != nil {
			fmt.Println("conn.Read err =", err1)
			return
		}
		// 拿到了文件的名字
		fileName := string(buf[:n])

		fmt.Println(fileName)
		// 返回ok
		conn.Write([]byte("success"))
		// 接收文件,
		revFile(fileName, conn)

	}
}

	
```

### client.php
```
<?php
$host="127.0.0.1";

$port=8001;

 //创建一个socket

$socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP)or die("cannot create socket\n");

$conn=socket_connect($socket,$host,$port) or die("cannot connect server\n");

if($conn){
	echo "client connect ok!";
}

socket_write($socket,"C:/Users/hack/Desktop/penghui.mp4") or die("cannot write data\n"); //往连接中写入数据

$buffer=socket_read($socket,125);

if($buffer && $buffer=="success"){ //创建文件成功的信号
	$file_path = "./2222.mp4";
	if(file_exists($file_path)){
		$fp = fopen($file_path,"r");
		
		while(!feof($fp)){//循环读取，直至读取完整个文件
			$str = fread($fp, 1024);
			socket_write($socket,$str);
		} 
		fclose($fp);
	}
}
socket_close($socket);
echo "==========程序执行完毕==========";
	
```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```

作者
@承鹏辉
