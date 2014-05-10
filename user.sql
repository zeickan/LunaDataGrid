/*
 Navicat Premium Data Transfer

 Source Server         : XAMPP
 Source Server Type    : MySQL
 Source Server Version : 50144
 Source Host           : localhost
 Source Database       : dev_luna

 Target Server Type    : MySQL
 Target Server Version : 50144
 File Encoding         : utf-8

 Date: 05/09/2014 19:03:42 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `user_auth`
-- ----------------------------
DROP TABLE IF EXISTS `user_auth`;
CREATE TABLE `user_auth` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(100) DEFAULT NULL,
	`passwd` varchar(150) DEFAULT NULL,
	`email` varchar(200) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=`InnoDB` AUTO_INCREMENT=4 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ROW_FORMAT=COMPACT COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0;

-- ----------------------------
--  Records of `user_auth`
-- ----------------------------
BEGIN;
INSERT INTO `user_auth` VALUES ('1', 'zeickan', '810cabe7285ab8b80513cbdb4214c346', 'zeickan@gmail.com'), ('2', 'laura', '810cabe7285ab8b80513cbdb4214c346', 'laura@dominio.co'), ('3', 'liam', '810cabe7285ab8b80513cbdb4214c346', 'link@dominio.co');
COMMIT;

-- ----------------------------
--  Table structure for `user_profile`
-- ----------------------------
DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
	`id` int(6) NOT NULL AUTO_INCREMENT,
	`parent_id` int(6) DEFAULT NULL,
	`first_name` varchar(50) DEFAULT NULL,
	`last_name` varchar(50) DEFAULT NULL,
	`country` varchar(50) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=`InnoDB` AUTO_INCREMENT=4 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ROW_FORMAT=COMPACT COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0;

-- ----------------------------
--  Records of `user_profile`
-- ----------------------------
BEGIN;
INSERT INTO `user_profile` VALUES ('1', '1', 'Andros', 'Romo', 'Mexico'), ('2', '2', 'Martha Laura', 'Guzman', 'USA'), ('3', '3', 'Liam', 'Neeson', 'UK');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
