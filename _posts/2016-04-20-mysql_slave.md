---
layout: post
title: Window Mysql主从配置
author: 承鹏辉
category: mysql
---

```
该文简单记录下自己安装的过程  了解下主从设置原理
像这样的文章本不该存在  以免误导别人
还是推荐linux下主从配置  不过安装配置都大同小异  win下不该存在仅记录
博文都是原创  复制请谨慎

```

### 复制ini主配置文件
	1.进入mysql bin目录下找到my.ini主配置文件复制一份改为自己的名字  比如：my1.ini
    2.修改my1.ini中的配置参数
    3.修改配置别忘记修改从库id server-id	= 2 (后面的这个数字可以自己设置，不重复就ok)

### 进行初始化操作
	1.执行命令 mysql_install_db --datadir="F:/webroot/mysql/data2"   datadir参数指向的目录为刚刚修改的my1.ini中参数指定的目录，他们相同
    2.执行完命令后会生成一些文件的和文件夹
    3.如果没有生成成功不要继续下面的操作   有可能路径没有填正确，正确后生成就ok了

### 启动mysql进程
	1.执行命令 mysqld --defaults-file="F:\webroot\mysql\bin\my1.ini"  这个是你的配置文件目录。目录地址记得写正确
    2.执行netstat -an 查看端口
    3.如果my1.ini配置文件中的端口在控制台能看到，那么恭喜你这个mysql实例已经启动成功了

### 进行主库操作
	1.执行命令 mysqld --defaults-file="F:\webroot\mysql\bin\my.ini" 启动主库进程
	2.进入mysql主库 mysql -uroot -p 不用指定端口  默认mysql用的主库的3306
	3.在主库进行授权操作 grant Replication slave on *.* to 'slave'@'localhost'  identified by '123456';
	4.退出mysql

### 数据库备份
	1.cmd执行命令 mysqldump -uroot -p --skip-opt --create-option --add-drop-table --single-transaction -q -e --set-charset --master-data=2  --hex-blob -A > new_all.sql
	2.执行成功后会在你用户的文件夹下生成该文件的。当然你还可以在后面指定生成的路径的。
	3.执行mysql -uroot -P3307 < new_all.sql  大P的端口号一定是你my1.ini指定的端口号的。把刚刚导出的数据，导入到3307进程中

### 进入主库进行操作
	1.执行命令 show master status; 
	2.会生成MASTER_LOG_FILE  和 MASTER_LOG_POS 的值  这个两个值下面的从库命令要用到的。

### 从库配置
	1.stop slave; 停止
	2.reset slave; 重置
	3.执行命令 CHANGE MASTER TO MASTER_HOST='localhost', MASTER_USER='slave', MASTER_PASSWORD='123456',MASTER_PORT = 3306, MASTER_LOG_FILE='mysql-bin.000002',  MASTER_LOG_POS=581;
start slave;   MASTER_LOG_FILE和 MASTER_LOG_POS 的值就是刚刚在主库中查看的值。填写命令语句中执行就ok了
	4.start slave; 重启
	5.show slave status\G; 查看从库状态
	6.如果状态中	
	Slave_IO_Running: Yes
	Slave_SQL_Running: Yes
	恭喜配置成功
	
### 每次修改ini配置文件，记得重启mysql
	
	关闭mysql      mysqladmin -uroot -P3307 shutdown
	
	
	flush tables with read lock;
	unlock tables;
	
### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```
 
作者
@承鹏辉
