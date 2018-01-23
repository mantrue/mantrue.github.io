---
layout: post
title:  Nginx升级https
author: 承鹏辉
category: nginx
---

```
转载加实际操作 已为服务器成功升级为https
Let's Encrypt 使用教程，免费的SSL证书，让你的网站拥抱 HTTPS
Nginx升级https
配置下免费的


```


### Let's Encrypt 简介

```

如果要启用HTTPS，我们就需要从证书授权机构(以下简称CA) 处获取一个证书，Let's Encrypt 就是一个 CA。
我们可以从 Let's Encrypt 获得网站域名的免费的证书。

```

### Certbot 简介

```

Certbot 是Let's Encrypt官方推荐的获取证书的客户端，可以帮我们获取免费的Let's Encrypt 证书。
Certbot 是支持所有 Unix 内核的操作系统的，服务器系统是CentOS 7

安装Certbot

Certbot是EFF开发的一个简单的工具，比起我之前研究的时候弄的ACME要简单得多，访问https://certbot.eff.org/ 之后，选择你的服务器（webserver）和操作系统（Operating System），就会给出简单的步骤了，我们还是一步一步来吧。

我的服务器是Nginx，操作系统是Ubuntu 16.04。由于自带了软件包，安装只需要一行命令即可：

sudo apt-get install letsencrypt
如果是其他版本的Ubuntu，只需要下载一个脚本就行了（下面的命令在需要下载到的目录里执行）：

wget https://dl.eff.org/certbot-auto
chmod a+x certbot-auto
./certbot-auto

```

### 获取免费证书

```

安装Certbot客户端
yum install certbot

获取证书
certbot certonly --webroot -w /var/www/example -d example.com -d www.example.com

这个命令会为 example.com 和 www.example.com 这两个域名生成一个证书，
使用 --webroot 模式会在 /var/www/example 中创建 .well-known 文件夹，
这个文件夹里面包含了一些验证文件，certbot 会通过访问 example.com/.well-known/acme-challenge 来验证你的域名是否绑定的这个服务器。
这个命令在大多数情况下都可以满足需求，


但是有些时候我们的一些服务并没有根目录，例如一些微服务，这时候使用 --webroot 就走不通了
certbot 还有另外一种模式 --standalone ， 这种模式不需要指定网站根目录
他会自动启用服务器的443端口，来验证域名的归属。我们有其他服务（例如nginx）占用了443端口
就必须先停止这些服务，在证书生成完毕后，再启用。
certbot certonly --standalone -d example.com -d www.example.com

证书生成完毕后，我们可以在 /etc/letsencrypt/live/ 目录下看到对应域名的文件夹，里面存放了指向证书的一些快捷方式。

这时候我们的第一生成证书已经完成了，接下来就是配置我们的web服务器，启用HTTPS。

server {
        server_name diamondfsd.com www.diamondfsd.com;
        listen 443 ssl;
        ssl on;
        ssl_certificate /etc/letsencrypt/live/diamondfsd.com/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/diamondfsd.com/privkey.pem;
}

主要是监听 443 端口，启用 SSL，并配置 SSL 的证书路径（公钥，私钥的路径）。
通过这些配置 我们就已经成功的完成了 Https 的启用。
现在打开我的博客 https://diamondfsd.com 就可以看到标有 安全 的字样。

配置HTTP请求重定向

server {
	listen       80;
	server_name  www.yourdomain.com;
	rewrite ^ https://$http_host$request_uri? permanent;    # force redirect http to https
	#return 301 https://$http_host$request_uri;
}

也可以配置同时启用http和https
server {
    listen              80;
    listen              443 ssl;
    server_name         www.example.com;
    ssl_certificate     www.example.com.crt;
    ssl_certificate_key www.example.com.key;
    ...
}



```

### 自动更新 SSL 证书

```

配置完这些过后，我们的工作还没有完成。 Let's Encrypt 提供的证书只有90天的有效期，我们必须在证书到期之前
重新获取这些证书，certbot 给我们提供了一个很方便的命令，那就是 certbot renew。
通过这个命令，他会自动检查系统内的证书，并且自动更新这些证书。
我们可以运行这个命令测试一下

certbot renew --dry-run 

我在运行的时候出现了这个错误

Attempting to renew cert from /etc/letsencrypt/renewal/api.diamondfsd.com.conf produced an unexpected error: At least one of the required ports is already taken.. Skipping.
说明443端口nginx占用 然后关闭nginx
再次执行就成功
证书是90天才过期，我们只需要在过期之前执行更新操作就可以了。 这件事情就可以直接交给定时任务来完成。linux 系统上有 cron 可以来搞定这件事情。
我新建了一个文件 certbot-auto-renew-cron， 这个是一个 cron 计划，这段内容的意思就是 每隔 两个月的 凌晨 2:15 执行 更新操作。

15 2 * */2 * certbot renew --pre-hook "service nginx stop" --post-hook "service nginx start"

--pre-hook 这个参数表示执行更新操作之前要做的事情，因为我有 --standalone 模式的证书，所以需要 停止 nginx 服务，解除端口占用。
--post-hook 这个参数表示执行更新操作完成后要做的事情，这里就恢复 nginx 服务的启用


```




### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉
