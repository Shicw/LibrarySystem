# Host: localhost  (Version 5.5.53)
# Date: 2018-11-16 17:28:19
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "book"
#

CREATE TABLE `book` (
  `id` varchar(20) NOT NULL DEFAULT '' COMMENT '图书编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '书名',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '图书类别id',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `price` double(6,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `publisher` varchar(25) NOT NULL DEFAULT '' COMMENT '出版社',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0:可借阅;1:已借阅;2:暂停借阅',
  `location` varchar(50) NOT NULL DEFAULT '' COMMENT '图书所在位置',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  `delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图书表';

#
# Data for table "book"
#

INSERT INTO `book` VALUES ('BC000001','JavaScript高级程序设计',3,'《JavaScript高级程序设计》是2006年人民邮电出版社出版的图书，作者是(美)(Nicholas C.Zakas)扎卡斯。本书适合有一定编程经验的开发人员阅读，也可作为高校相关专业课程的教材。',99.00,'人民邮电出版社',0,'2F03',1542352941,0),('BC000002','Redis入门指南',3,'Redis入门指南',39.00,'人民邮电出版社',0,'2F03',1542355457,0);

#
# Structure for table "book_borrow"
#

CREATE TABLE `book_borrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` varchar(20) NOT NULL DEFAULT '' COMMENT '图书编号',
  `user_id` varchar(20) NOT NULL DEFAULT '' COMMENT '借阅者编号',
  `begin_time` int(11) NOT NULL DEFAULT '0' COMMENT '借阅时间(时间戳)',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '归还时间',
  `real_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '实际归还时间',
  `renew_times` tinyint(1) NOT NULL DEFAULT '1' COMMENT '剩余续借次数',
  `overdue_price` double(6,2) NOT NULL DEFAULT '0.00' COMMENT '超期需支付费用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图书借阅记录表';

#
# Data for table "book_borrow"
#

INSERT INTO `book_borrow` VALUES (1,'BC000001','2015014001',1542357101,1542443501,0,1,0.00);

#
# Structure for table "book_type"
#

CREATE TABLE `book_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '类别名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图书类别表';

#
# Data for table "book_type"
#

INSERT INTO `book_type` VALUES (1,'英语类','英语类图书',0),(2,'文学类','文学类图书',0),(3,'计算机编程类','编程类',0),(4,'计算机硬件类','',0),(5,'电子类','',0);

#
# Structure for table "class"
#

CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `major_id` int(11) NOT NULL DEFAULT '0' COMMENT '专业id',
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '班级名称',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='班级表';

#
# Data for table "class"
#

INSERT INTO `class` VALUES (1,1,'无',0),(2,2,'151',0),(3,2,'152',0),(4,3,'151',0),(5,3,'152',0);

#
# Structure for table "log"
#

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL DEFAULT '' COMMENT '操作',
  `user_id` varchar(20) NOT NULL DEFAULT '0' COMMENT '操作者id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='系统日志表';

#
# Data for table "log"
#

INSERT INTO `log` VALUES (1,'admin01登录','admin01',1542251355),(2,'admin01注销','admin01',1542252231),(3,'admin01登录','admin01',1542252306),(4,'admin01注销','admin01',1542252314),(5,'admin01登录','admin01',1542252375),(6,'admin01注销','admin01',1542252380),(7,'admin01登录','admin01',1542252409),(8,'admin01注销','admin01',1542252415),(9,'admin01登录','admin01',1542252462),(10,'管理员注销','admin01',1542253176),(11,'管理员登录','admin01',1542253184),(12,'管理员注销','admin01',1542253991),(13,'管理员登录','admin01',1542253997),(14,'修改密码','admin01',1542254276),(15,'修改密码','admin01',1542254320),(16,'管理员登录','admin01',1542258074),(17,'管理员注销','admin01',1542259625),(18,'管理员登录','admin01',1542259673),(19,'管理员注销','admin01',1542261094),(20,'管理员登录','admin01',1542261100),(21,'修改密码','admin01',1542261196),(22,'修改密码','admin01',1542261206),(23,'管理员注销','admin01',1542264454),(24,'管理员登录','admin01',1542264486),(25,'添加专业:软件工程','admin01',1542266664),(26,'删除专业:软件工程','admin01',1542273490),(27,'修改专业:软件工程','admin01',1542331141),(28,'修改专业:信息工程','admin01',1542331255),(29,'删除专业:软件工程','admin01',1542331264),(30,'添加专业:计算机','admin01',1542331301),(31,'修改专业:计算机','admin01',1542331313),(32,'添加班级:151','admin01',1542332173),(33,'添加班级:152','admin01',1542333574),(34,'删除专业:152','admin01',1542333579),(35,'修改专业:152','admin01',1542333595),(36,'修改专业:153','admin01',1542333601),(37,'添加图书类别:英语类','admin01',1542334928),(38,'添加图书类别:文学类','admin01',1542336090),(39,'修改图书类别:英语类','admin01',1542336105),(40,'删除图书类别:文学类','admin01',1542336109),(41,'添加图书类别:计算机编程类','admin01',1542336136),(42,'添加图书类别:计算机硬件类','admin01',1542336145),(43,'添加图书类别:电子类','admin01',1542336154),(44,'修改图书类别:文学类','admin01',1542336179),(45,'删除图书类别:电子类','admin01',1542337277),(46,'删除用户:2015014001','admin01',1542339744),(47,'添加用户:2015014002','admin01',1542349003),(48,'删除用户:2015014002','admin01',1542349071),(49,'删除班级:152','admin01',1542349347),(50,'修改用户信息:2015014001','admin01',1542350706),(51,'修改图书类别:计算机编程类','admin01',1542352533),(52,'删除图书:BC000001','admin01',1542354213),(53,'添加图书:BC000002','admin01',1542355457),(54,'修改图书信息:BC000002','admin01',1542356309),(55,'删除图书:BC000002','admin01',1542356447),(56,'删除图书:BC000001','admin01',1542356453);

#
# Structure for table "major"
#

CREATE TABLE `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '专业名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='专业表';

#
# Data for table "major"
#

INSERT INTO `major` VALUES (1,'无','无',0),(2,'信息工程','信息工程专业',0),(3,'软件工程','软件工程专业',0),(4,'计算机','计算机科学与技术专业',0);

#
# Structure for table "user"
#

CREATE TABLE `user` (
  `id` varchar(20) NOT NULL DEFAULT '' COMMENT '用户编号',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(15) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0:未设置;1:男;2:女',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类别 0:admin;1:学生;2:教师',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `major_id` int(11) NOT NULL DEFAULT '1' COMMENT '专业id',
  `class_id` int(11) NOT NULL DEFAULT '1' COMMENT '班级id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0:禁用 1:启用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  `last_borrow_time` int(11) NOT NULL DEFAULT '0' COMMENT '上一次借阅时间(时间戳)',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间(时间戳)',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='读者用户表';

#
# Data for table "user"
#

INSERT INTO `user` VALUES ('2015014001','2015014001','e10adc3949ba59abbe56e057f20f883e','王小明',1,1,'17898571100',2,2,1,1542264086,0,0,0),('2015014002','2015014002','e10adc3949ba59abbe56e057f20f883e','李华',1,1,'17855553421',2,3,1,1542349003,0,0,0),('admin01','admin01','e10adc3949ba59abbe56e057f20f883e','admin01',0,0,'',0,0,1,1542260000,0,1542264486,0);
