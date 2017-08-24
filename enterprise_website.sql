/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.6.33 : Database - enterprise_website
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`enterprise_website` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `enterprise_website`;

/*Table structure for table `ent_auth_group` */

DROP TABLE IF EXISTS `ent_auth_group`;

CREATE TABLE `ent_auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL COMMENT '权限组名称',
  `rulesId` varchar(255) DEFAULT NULL COMMENT '权限规则ID，以''|''分隔',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限组表';

/*Table structure for table `ent_auth_rules` */

DROP TABLE IF EXISTS `ent_auth_rules`;

CREATE TABLE `ent_auth_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL COMMENT '父ID',
  `name` varchar(40) NOT NULL COMMENT '权限名称',
  `rules` varchar(100) NOT NULL COMMENT '权限路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限规则表';

/*Table structure for table `ent_auth_verify` */

DROP TABLE IF EXISTS `ent_auth_verify`;

CREATE TABLE `ent_auth_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `userId` int(11) NOT NULL COMMENT '用户ID',
  `gropuId` int(11) NOT NULL COMMENT '权限组ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限授权表';

/*Table structure for table `ent_cate` */

DROP TABLE IF EXISTS `ent_cate`;

CREATE TABLE `ent_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(20) NOT NULL COMMENT '栏目标题',
  `type` tinyint(1) DEFAULT NULL COMMENT '栏目分类:1最新动态2文章3图片',
  `desc` varchar(40) NOT NULL COMMENT '栏目描述',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `addAtId` int(11) NOT NULL COMMENT '添加者ID',
  `modifytime` int(11) DEFAULT NULL COMMENT '修改时间',
  `modifyAtId` int(11) DEFAULT NULL COMMENT '修改者ID',
  `sort` tinyint(4) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ent_user` */

DROP TABLE IF EXISTS `ent_user`;

CREATE TABLE `ent_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) NOT NULL COMMENT '昵称',
  `realName` varchar(20) NOT NULL COMMENT '真实姓名',
  `sex` tinyint(4) NOT NULL COMMENT '性别:0不确定1女2男',
  `birthday` datetime NOT NULL COMMENT '生日',
  `phone` varchar(20) NOT NULL COMMENT '手机',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `avatar` varchar(100) DEFAULT NULL COMMENT '实名头像',
  `qq` varchar(20) DEFAULT NULL COMMENT 'qq号',
  `email` varchar(40) DEFAULT NULL COMMENT '邮箱',
  `areaid` tinyint(4) DEFAULT NULL COMMENT '区域坐标ID',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:0正常1锁定',
  `regTime` int(11) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
