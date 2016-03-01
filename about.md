---
layout: page
title: 关于
menu: About
---
{% assign current_year = site.time | date: '%Y' %}

承鹏辉
===
男 90后

## 概况

- 邮箱：penghui219@163.com
- 主页：[http://penghui.link](http://penghui.link)
- 微博：[@承鹏辉](http://weibo.com/peen219)

电子商务专业肄业，{{ current_year | minus: 2012 }} 年在职工作经验，{{ current_year | minus: 2013 }} 年 web 开发经验。

## 教育
- 江西渝州科技职业技术学院 — 大专 2010 - 2012(肄业)

## keywords
<div class="btn-inline">
{% for keyword in site.skill_keywords %} <button class="btn btn-outline" type="button">{{ keyword }}</button> {% endfor %}
</div>

### 综合技能

| 名称 | 熟悉程度
|--:|:--|
| HTML+CSS | ★★★☆☆ |
| javascript | ★★★☆☆ |
| PHP | ★★★★☆ |
| Linux | ★★★★☆ |
| Shell | ★★★☆☆ |
| Python | ★★☆☆☆ |


### 成长经历
* 2013/06 就职于 亲亲宝贝
* 2014/09 就职于 [华胜天成](http://www.teamsun.com.cn/)
* 2015/07 就职于 [寅午伟业](http://www.yinwuweiye.com/)
* 走自己的路,来不及匆忙

### 项目经验
* <a href='http://maidian3.com'>买点啥</a> (网站爬虫类,抓取主流商城商品详情页   附:支持ios客服端)
* 指针导游 (及时通信类仿微信,支持文字,图片,语音聊天,支持websocket协议,tcp协议 附:支持ios客服端)
* 星企组合 (综合电商类,用户&&订单)
* <a href='http://www.bjxgmh.com'>企业站</a> (企业展示)

