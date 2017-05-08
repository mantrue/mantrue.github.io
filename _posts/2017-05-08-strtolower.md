---
layout: post
title:  从字符串转化大小写引申的字符编码问题
author: 承鹏辉
category: php
---

```
好久没更新日志了，不知道写什么，接下来往底层走吧 C go
字符串编码问题
转化大小写
单个字符转化为ascii码的固定对应数值
ascii码的固定对应数值转化为单个字符

```

### 字符代码

```

<?php
$string = 'ABCDEFGHIGKLMNOPQRSTUVWXYZ';

function stringtolower( $str ) {
	$model = '';
	for( $i=0; $i<strlen($str);$i++) {
		$ord = ord($str[$i]);
		$chr = chr($ord+32);
		$model .=$chr;
	}
	return $model;
}

$strinfo = stringtolower( $string );
print_r($strinfo);die();

```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

```


@承鹏辉