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
  `overdue_money` double(6,2) NOT NULL DEFAULT '0.00' COMMENT '超期需支付费用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='图书借阅记录表';

#
# Structure for table "book_type"
#

CREATE TABLE `book_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '类别名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='图书类别表';

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='班级表';

#
# Data for table "class"
#

INSERT INTO `class` VALUES (1,1,'无',0);

#
# Structure for table "log"
#

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL DEFAULT '' COMMENT '操作',
  `user_id` varchar(20) NOT NULL DEFAULT '0' COMMENT '操作者id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COMMENT='系统日志表';

#
# Structure for table "major"
#

CREATE TABLE `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '专业名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='专业表';

#
# Data for table "major"
#

INSERT INTO `major` VALUES (1,'无','无',0);

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

INSERT INTO `user` VALUES ('admin01','admin01','e10adc3949ba59abbe56e057f20f883e','admin01',0,0,'',0,0,1,1542260000,0,1543130693,0);
