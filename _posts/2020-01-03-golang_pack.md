---
layout: post
title: golang 解决go get下载包失败问题
category: golang
---

```
复制加修改 也符合自己的心境吧。
```

## 悲观锁
由于某些不可抗力的原因，国内使用go get命令安装包时会经常会出现timeout的问题。本文介绍几个常用的解决办法。

## 从github克隆
golang在github上建立了一个镜像库，如https://github.com/golang/net就对应是 https://golang.org/x/net的镜像库。 要下载golang.org/x/net包，可以在本地创建包的目录后使用git clone来拉取相应包的源代码文件，具体操作如下：
mkdir -p $GOPATH/src/golang.org/x
cd $GOPATH/src/golang.org/x
git clone https://github.com/golang/net.git

## 使用gopm
go get -u github.com/gpmgo/gopm
不加-g参数，会把依赖包下载.vendor目录下面； 加上-g参数，可以把依赖包下载到GOPATH目录中
gopm get -g golang.org/x/net


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```

作者
@承鹏辉  


