---
layout: post
title:  php多进程批量入库数据
author: 承鹏辉
category: php
---

```
原创 批量入库，批量操作，批量请求都可以
cli模式下执行文件

```

### 上代码
```

<?php
// 必须加载扩展
if (!function_exists("pcntl_fork")) {
    die("pcntl extention is must !");
}
//总进程的数量
$totals = 50;
// 执行的脚本数量的数组
pcntl_signal(SIGCHLD, SIG_IGN);    //如果父进程不关心子进程什么时候结束,子进程结束后，内核会回收。

for ($i = 0; $i < $totals; $i++) {
    $pid = pcntl_fork();    //创建子进程

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
        for ($i=0; $i < 10000; $i++) { 
            $pid = getmypid();
            $sql="INSERT INTO bb (xx, yy) VALUES ('$i','$pid')";   
            mysql_query($sql, $conn);
        }

        exit($i);
    }
}

50万的数据松松的几秒的事情 可以做很多事情，比如批量请求，批量抓取
相同的服务器下单进程php循环入库50万，数据基本相同的情况下，速度太慢了
多进程时间大概在8秒左右
单进程时间我的天  

```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉