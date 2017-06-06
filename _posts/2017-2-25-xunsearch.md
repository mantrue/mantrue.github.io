---
layout: post
title: xunsearch全文检索应用
author: 承鹏辉
category: php
---

```
全文检索,应用场景也挺多的
特别是数据量大的论坛

```

### xunsearch介绍

```

全文检索的应用有很多，知名的有sphinx 今天介绍一款xunsearch
主要是国人开发的哈哈
http://www.xunsearch.com/ 这是地址

```

### xunsearch安装

```
wget http://www.xunsearch.com/download/xunsearch-full-latest.tar.bz2
tar -xjf xunsearch-full-latest.tar.bz2

cd xunsearch-full-1.3.0/
sh setup.sh  直接回车用默认地址就可以了

cd $prefix ; bin/xs-ctl.sh restart

启动后记得查看下进程

```


### xunsearch相关命令

```

# 清空 demo 项目的索引数据 
util/Indexer.php --clean demo

# 导入 JSON 数据文件 file.json 到 demo 项目
util/Indexer.php --source=json demo file.json

# 导入 MySQL 数据库的 dbname.tbl_post 表到 demo 项目中，并且平滑重建
util/Indexer.php --rebuild --source=mysql://root:pass@localhost/dbname --sql="SELECT * FROM tbl_post" --project=demo

# 查看 demo 项目在服务端的相关信息
util/Indexer.php --info -p demo

# 强制刷新 demo 项目的搜索日志
util/Indexer.php --flush-log --project demo

# 强制停止重建
util/Indexer.php --stop-rebuild demo

导入csv索引
util/Indexer.php --source=csv --clean demo

util/Quest.php demo 项目 进行搜索测试

经过测试 60万数据量 查到40万存在的用时 0.1秒  如果几千条 就是0.01几了。很强悍了

如果是按上面的配置 搜索的结果就出来了 fid就是数据源的id  我用的mysql导入的

引入库直接happy吧

//配置文件也很简单
project.name = demo
project.default_charset = utf-8
server.index = 8383
server.search = 8384

[id]
type = id

[access_url]
type = title

就搜索了一个title

```

### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉