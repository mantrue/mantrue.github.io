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


ascii码 空格 对应的是32
大写+32 = 小写
小写-32 = 大写
ascii码 其实就是规定字符对应的二进制 十进制是多少
从而达到一种编码规范
ascii码就是对字符进行编码
而ascii码又分了三部分
ASCII码使用一个字节编码，所以它的范围基本是只有英文字母、数字和一些特殊符号 ，只有256个字符

GB2312 是对 ASCII 的中文扩展
GBK是编码汉字的 使用双字节编码 是对GB2312的扩展

为了解决各国的编码互相认识的事情

废了所有的地区性编码方案，重新搞一个包括了地球上所有文化、所有字母和符号 的编码！
所以unicode编码诞生了
unicode编码又扩展
UTF-8为解决unicode如何在网络上传输的问题又诞生

```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉