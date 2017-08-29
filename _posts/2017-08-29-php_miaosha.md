---
layout: post
title:  php多进程队列秒杀实现方式
author: 承鹏辉
category: php
---

```
原创 
cli模式下执行文件
add.php负责入库redis队列
redis.php负责多进程监听进行出栈操作

```

### 上代码 redis.php
```

开了5个worker

<?php

if (!function_exists("pcntl_fork")) {
    die("pcntl extention is must !");
}
//总进程的数量
$totals = 5;
// 执行的脚本数量的数组
pcntl_signal(SIGCHLD, SIG_IGN);    //如果父进程不关心子进程什么时候结束,子进程结束后，内核会回收。

for ($i = 0; $i < $totals; $i++) {
    $pid = pcntl_fork();    //创建子进程

    $redis = new \Redis();
    $redis->connect('127.0.0.1',6378);
    $redis->auth('xxx');
	
    $conn = mysql_connect('xxx', 'xxx', 'xxx', true);
    //父进程和子进程都会执行下面代码
    if ($pid == -1) {
        //错误处理：创建子进程失败时返回-1.
        die('could not fork');
    } else if ($pid) { //这里$pid>0
        //父进程会得到子进程号，所以这里是父进程执行的逻辑
        //如果不需要阻塞进程，而又想得到子进程的退出状态，则可以注释掉pcntl_wait($status)语句，或写成：
        $conn = mysql_connect('xxx', 'xxx', 'xxx', true);
        pcntl_wait($status,WNOHANG); //等待子进程中断，防止子进程成为僵尸进程。
    } else { //这里$pid=0
        //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
        $cpid = posix_getpid();
        $select_db = mysql_select_db('xxx');
        
        while (true) {
                $res = $redis->lpop('demolist');
                if ($res) {
                        $sql="INSERT INTO bb (xx, yy) VALUES ('$res','$cpid')";   
                        mysql_query($sql, $conn);
                }
            
        }

        exit($i);
    }
}

```

### 上代码 add.php
```
<?php
	$redis = new \Redis();
    $redis->connect('127.0.0.1',6378);
    $redis->auth('xxxx');
	
	for ($i=0; $i < 2000; $i++) { 
		
		$len = $redis->lLen('demolist');
		if ($len>100) {
			echo "秒杀失败";
		} else {
			$redis->rpush('demolist','hello');
		}
	}	

	
php add.php 然后自动同步到队列 同步到mysql

这个秒杀客户端模拟不是并行的，阻塞的 
但是也能看到redis瞬间执行完毕
而mysql正慢慢的在同步

```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉