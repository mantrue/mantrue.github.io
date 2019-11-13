---
layout: post
title: golang MQTT 硬件通信
author: 承鹏辉
category: php
---

```
MQTT 订阅

```

### 安装与启动 配置

```

安装Mosquitto

sudo apt-get install mosquitto mosquitto-clients
	
	mosquitto中可以添加多个用户，只有使用用户名和密码登陆服务器才允许用户进行订阅与发布操作。可以说用户机制是mosquitto重要的安全机制，增强服务器的安全性。
用户与权限配置需要修改3处地方：

1、mosquitto中最最最重要的配置文件mosquitto.conf
2、pwfile.example (保存用户名与密码)
3、aclfile.example (保存权限配置)

　首先我们来新增两个用户 1： admin/admin 2: mosquitto/mosquitto 具体步骤：

　　1： 打开mosquitto.conf文件，找到allow_anonymous节点，这个节点作用是，是否开启匿名用户登录，默认是true。打开此项配置（将前面的 # 号去掉）之后将其值改为false

　　　　修改前：#allow_anonymous

　　　　修改后：allow_anonymous false

　　2： 找到password_file节点，这个节点是告诉服务器你要配置的用户将存放在哪里。打开此配置并指定pwfile.example文件路劲（注意是绝对路劲）

　　　　修改前：#password_file

　　　　修改后：password_file  /etc/mosquitto/pwfile.example （这里的地址根据自己文件实际位置填写）

　　3： 创建用户名和密码、打开命令窗口 键入如下命令：　　

1
mosquitto_passwd -c /etc/mosquitto/pwfile.example admin
       提示连续两次输入密码、创建成功。命令解释： -c 创建一个用户、/etc/mosquitto/pwfile.example 是将用户创建到 pwfile.example  文件中、admin 是用户名。

　　4： 创建mosquitto用户。在命令窗口键入如下命令：

1
mosquitto_passwd /etc/mosquitto/pwfile.example mosquitto
　同样连续会提示连续输入两次密码。注意第二次创建用户时不用加 -c 如果加 -c 会把第一次创建的用户覆盖。

至此两个用户创建成功，此时如果查看 pwfile.example 文件会发现其中多了两个用户。



 

注意,mosquitto_passwd -c命令每次都只会生成只包含一个用户的文件,如果你想在passwd.conf中存放多个用户， 可以使用mosquitto_passwd -b 命令

    mosquitto_passwd -b [最终生成的password_file文件]  [用户名]  [密码]

    mosquitto_passwd -b命令必须在控制台输入明文的密码，且每次只是在passwd.conf中新增一个用户，不会覆盖之前已生成的用户

   同时也可以使用mosquitto_passwd -D命令删除一个用户

   mosquitto_passwd的具体应用可以参考 http://mosquitto.org/man/mosquitto_passwd-1.html


此时所有客户端连接 Mosquitto 服务都需要输入用户名密码、

Mosquitto 权限是根据 topic 控制的、类似与目录管理。您可以设定每个用户订阅/发布权限、也可以设定每个用户可访问的topic范围、从而达到权限控制的目的

　1： 给这两个用户配置不同的权限（假定已经创建了admin 和 mosquitto这两个用户）

　　　　admin 设置为订阅权限，并且只能访问的主题为"root/topic/#"

　　　　mosquitto 设置为发布权限，并且只能访问的主题为"root/topic/#"

　　　　如果用 admin 进行发布是不会成功的、反过来用 mosquitto 进行订阅同样不会接受到任何信息。因为他们的权限不同。

　　2： 增加权限配置

　　　　打开配置文件 aclfile.example 在其中添加如下配置信息。

1
2
3
4
user admin
topic read root/topic/#
user mosquitto
topic write root/topic#
　　

read 订阅权限 、write 发布权限、# 通配符表示所有的、保存退出。

　　3：修改 Mosquitto.conf 配置选项

　　　　打开mosquitto.conf文件，找到acl_file节点。打开配置做如下修改：
　　　　修改前：#acl_file
　　　　修改后：acl_file /etc/mosquitto/aclfile.example 根据自己文件实际位置填写

至此admin 、 Mosquitto 两个用户的权限已配置完成。

 

修改mosquitto端口 ①、默认情况下，mosquitto使用的是1883端口 
在.conf文件找到port，改成自己需要的端口号。我改成了1884，不能使用已占用的端口。


最后配置项
pid_file /var/run/mosquitto.pid
persistence true
persistence_location /var/lib/mosquitto/
log_dest file /var/log/mosquitto/mosquitto.log
include_dir /etc/mosquitto/conf.d
persistence_file mosquitto.db
max_queued_messages 100000
allow_anonymous false
password_file  /etc/mosquitto/pwfile.example
port 1884



```

### 代码示例
```

订阅
package main

import (
	"fmt"
	"github.com/eclipse/paho.mqtt.golang"
)

func main() {
	opts := mqtt.NewClientOptions().AddBroker("tcp://118.190.65.33:1884").SetClientID("sample")
	opts.SetCleanSession(false)
	opts.Username = "xxx"
	opts.Password = "xxxx!"

	c := mqtt.NewClient(opts)
	if token := c.Connect(); token.Wait() && token.Error() != nil {
		panic(token.Error())
	} else {
		go subscribe(c) //监听一个订阅=====
	}

	fmt.Println("=====模拟发送mqtt======")
	select {}
}

func subscribe(c mqtt.Client) {
	//定义，接收到数据后的回调函数
	var num int
	c.Subscribe("test", 1, func(mqtt mqtt.Client, msg mqtt.Message) {
		fmt.Printf("Success SubscribeUplink with msg:%s\n", msg.Payload())
		num++
		fmt.Println("执行次数", num)
	})
}


发布
package main

import (
	"fmt"
	"github.com/eclipse/paho.mqtt.golang"
)

func main() {
	opts := mqtt.NewClientOptions().AddBroker("tcp://118.190.65.33:1884").SetClientID("sampleone")
	opts.SetCleanSession(false)
	opts.Username = "xxx"
	opts.Password = "xxxx!"

	c := mqtt.NewClient(opts)
	if token := c.Connect(); token.Wait() && token.Error() != nil {
		panic(token.Error())
	}

	if token := c.Publish("test", 1, false, "{name:'helloone'}"); token.Wait() && token.Error() != nil {
		fmt.Println(token.Error())
	}
	fmt.Println("=====模拟发送mqtt======")
}
	
```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```

作者
@承鹏辉
