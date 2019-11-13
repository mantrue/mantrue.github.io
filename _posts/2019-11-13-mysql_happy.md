---
layout: post
title: mysql 悲观锁与乐观锁的理解
category: php
---

```
复制加修改 也符合自己的心境吧。
```

## 悲观锁
顾名思义，就是对于数据的处理持悲观态度，总认为会发生并发冲突，获取和修改数据时，别人会修改数据。所以在整个数据处理过程中，需要将数据锁定。

悲观锁的实现，通常依靠数据库提供的锁机制实现，比如mysql的排他锁，select .... for update来实现悲观锁。

例子：商品秒杀过程中，库存数量的减少，避免出现超卖的情况。

CREATE TABLE `tb_goods_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` bigint(20) unsigned DEFAULT '0' COMMENT '商品ID',
  `nums` int(11) unsigned DEFAULT '0' COMMENT '商品库存数量',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `modify_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品库存表';
将商品库存数量nums字段类型设为unsigned，保证在数据库层面不会发生负数的情况。

注意，使用悲观锁，需要关闭mysql的自动提交功能，将 set autocommit = 0;

注意，mysql中的行级锁是基于索引的，如果sql没有走索引，那将使用表级锁把整张表锁住。

1、开启事务，查询要卖的商品，并对该记录加锁。

begin;
select nums from tb_goods_stock where goods_id = {$goods_id} for update;
2、判断商品数量是否大于购买数量。如果不满足，就回滚事务。

3、如果满足条件，则减少库存，并提交事务。

update tb_goods_stock set nums = nums - {$num} where goods_id = {$goods_id} and nums >= {$num};
commit;
事务提交时会释放事务过程中的锁。

悲观锁在并发控制上采取的是先上锁然后再处理数据的保守策略，虽然保证了数据处理的安全性，但也降低了效率。


## 乐观锁

顾名思义，就是对数据的处理持乐观态度，乐观的认为数据一般情况下不会发生冲突，只有提交数据更新时，才会对数据是否冲突进行检测。

如果发现冲突了，则返回错误信息给用户，让用户自已决定如何操作。

乐观锁的实现不依靠数据库提供的锁机制，需要我们自已实现，实现方式一般是记录数据版本，一种是通过版本号，一种是通过时间戳。

给表加一个版本号或时间戳的字段，读取数据时，将版本号一同读出，数据更新时，将版本号加1。

当我们提交数据更新时，判断当前的版本号与第一次读取出来的版本号是否相等。如果相等，则予以更新，否则认为数据过期，拒绝更新，让用户重新操作。

CREATE TABLE `tb_goods_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` bigint(20) unsigned DEFAULT '0' COMMENT '商品ID',
  `nums` int(11) unsigned DEFAULT '0' COMMENT '商品库存数量',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `modify_time` datetime DEFAULT NULL COMMENT '更新时间',
  `version` bigint(20) unsigned DEFAULT '0' COMMENT '版本号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='商品库存表';
1、查询要卖的商品，并获取版本号。

begin;
select nums, version from tb_goods_stock where goods_id = {$goods_id};
2、判断商品数量是否大于购买数量。如果不满足，就回滚事务。

3、如果满足条件，则减少库存。(更新时判断当前version与第1步中获取的version是否相同)

update tb_goods_stock set nums = nums - {$num}, version = version + 1 where goods_id = {$goods_id} and version = {$version} and nums >= {$num};
4、判断更新操作是否成功执行，如果成功，则提交，否则就回滚。

乐观锁是基于程序实现的，所以不存在死锁的情况，适用于读多的应用场景。如果经常发生冲突，上层应用不断的让用户进行重新操作，这反而降低了性能，这种情况下悲观锁就比较适用


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```

作者
@承鹏辉  


