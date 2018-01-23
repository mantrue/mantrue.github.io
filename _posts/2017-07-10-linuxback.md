---
layout: post
title:  rsync+inotify实现服务器之间文件实时同步
author: 承鹏辉
category: php
---

```
今天配置了下文件同步 文章摘抄了一些，修改了一些bug，亲测没问题

```

适应场景服务器代码自动同步

```

一、主服务器（server端，我这里是nginx）
其中主服务器需要安装rsync与inotify，主服务器作为server，向备份服务器client传输文件

1、安装rsync
wget  http://rsync.samba.org/ftp/rsync/src/rsync-3.0.9.tar.gz
tar zxvf rsync-3.0.9.tar.gz
cd rsync-3.0.9
./configure --prefix=/usr/local/rsync
make && make install

2.建立密码认证文件
cd /usr/local/rsync/
echo "Jingbanyun426!" >/usr/local/rsync/rsync.passwd
其中rsync-pwd可以自己设置密码，rsync.passwd名字也可以自己设置
chmod 600 rsync.passwd
无论是为了安全，还是为了避免出现以下错误，密码文件都需要给600权限

3、安装inotify
wget http://cloud.github.com/downloads/rvoicilas/inotify-tools/inotify-tools-3.14.tar.gz 
tar zxvf inotify-tools-3.14.tar.gz
cd inotify-tools-3.14
./configure --prefix=/usr/local/inotify
make && make install

4、创建rsync复制脚本
vim /usr/bin/rsync.sh

#!/bin/bash
host=115.28.78.221 #inotify-slave的ip地址 从服务器ip
src=/home/wwwroot/web/ #本地监控的目录
des=web #inotify-slave的rsync服务的模块名
user=root #inotify-slave的rsync服务的虚拟用户
/usr/local/inotify/bin/inotifywait -mrq --timefmt '%d/%m/%y %H:%M' --format '%T %w%f%e' -e modify,delete,create,attrib $src \
| while read files
do
/usr/bin/rsync -zrtopg --exclude ".svn" --exclude ".log" --delete --progress --password-file=/usr/local/rsync/rsync.passwd $src $user@$host::$des
echo "${files} was rsynced" > /var/log/rsyncd.log 2>&1
done

chmod +x /usr/bin/rsync.sh

到此为止，源服务器的所有操作就完成了。下面配置目标服务器。
des是客户端设置的模块名称

```

配置目标服务器

```

1、目标服务器也要安装 rsync，安装方式跟源服务器一样，这里就不在赘述了。

2、建立密码文件：

cd /usr/local/rsync/
echo "root:Jingbanyun426!" >/usr/local/rsync/rsync.passwd

同样要给此文件一个600的权限
chmod 600 /usr/local/rsync/rsync.passwd


注：在源服务器建立的密码文件，只有密码，没有用户名；而在目标服务器里建立的密码文件，用户名与密码都有。

3、写rsync的配置文件：

vim /etc/rsyncd.conf

uid = root
gid = root
use chroot = no
max connections = 10
strict modes = yes
pid file = /var/run/rsyncd.pid
lock file = /var/run/rsync.lock
log file = /var/log/rsyncd.log
#log format = %t %a %m %f %b # 脠志录脟录赂帽[web]
[web]
path = /home/webjtypt #需要备份的目录
comment = web file
ignore errors
read only = no
write only = no
hosts allow = 114.215.106.208 #主服务器ip
hosts deny = *
list = false
uid = root
gid = root
auth users = root
secrets file = /usr/local/rsync/rsync.passwd

4、目标服务器启动 rsync

/usr/bin/rsync --daemon --config=/etc/rsyncd.conf

5、源服务器启动同步：

//直接执行
/usr/bin/rsync.sh
守护进程
nohup /usr/bin/rsync.sh &

到这里，所有的都已完成。可以到源服务器下的/home/back目录下建一个文件，然后再看一下目标服务器下的/home/back下是否有

```

### 本地同步文件命令
rsync -a --stats src/one.txt dest
rsync -avzS --partial src dest

远程
rsync -auqz --delete  /home/wwwroot/server/ x.x.x.x:/home/webjtypt/
 

### scp远程传输文件
scp -r /wwwbackup/server/server20170718_000001/&nbsp;&nbsp;root@115.28.78.221:/home/webjtypt

### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉