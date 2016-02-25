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
- 主页：[http://mantrue.me](http://mantrue.me)
- 微博：[@承鹏辉](http://weibo.com/peen219)

电子商务专业肄业，{{ current_year | minus: 2012 }} 年在职工作经验，{{ current_year | minus: 2010 }} 年 web 开发经验。

## 教育
- 江西渝州科技职业技术学院 — 大专 2010 - 2012(肄业)

## keywords
<div class="btn-inline">
{% for keyword in site.skill_keywords %} <button class="btn btn-outline" type="button">{{ keyword }}</button> {% endfor %}
</div>

### 综合技能

| 名称 | 熟悉程度
|--:|:--|
| PHP | ★★★★★ |
| javascript | ★★★★☆ |
| Linux | ★★★★☆ |
