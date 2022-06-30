<?php

use yii\db\Migration;

/**
 * Class m210910_104222_modify_address
 */
class m210910_104222_modify_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $sql = <<<EOF
//ALTER TABLE `swim_central_platform`.`swim_address`
//CHANGE COLUMN `name` `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '游泳馆类型 Code' AFTER `id`,
//ADD COLUMN `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '场馆名称' AFTER `type`,
//ADD COLUMN `avatar` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '游泳馆头像照片' AFTER `name`,
//ADD COLUMN `license_url` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '许可证照片' AFTER `avatar`,
//MODIFY COLUMN `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '泳馆地址-详细详址' AFTER `neighborhood_id`,
//ADD COLUMN `travel_information` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '交通信息' AFTER `address`,
//ADD COLUMN `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '场所固定电话' AFTER `travel_information`,
//ADD COLUMN `trade_situation` tinyint(2) NULL DEFAULT NULL COMMENT '营业情况（01-正常；02-休业；）' AFTER `phone`,
//ADD COLUMN `swim_service_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '提供服务信息' AFTER `trade_situation`,
//ADD COLUMN `water_acreage` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '池水面积（㎡）' AFTER `account_id`,
//ADD COLUMN `remark` varchar(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '场所开放时间：全年开放；夏季开放' AFTER `water_acreage`,
//ADD COLUMN `open_license` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '开放许可证编号' AFTER `remark`,
//ADD COLUMN `principal` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '负责人姓名' AFTER `open_license`,
//ADD COLUMN `open_object` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '场所开放性质：对内开放；对外开放；' AFTER `principal`,
//ADD COLUMN `last_access` int(20) NULL DEFAULT NULL COMMENT '最后更新时间' AFTER `open_object`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address`
//CHANGE COLUMN `type` `address_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '游泳馆 ID' AFTER `id`,
//ADD COLUMN `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '游泳馆类型 Code' AFTER `address_id`,
//MODIFY COLUMN `neighborhood_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '街道名称' AFTER `district`,
//MODIFY COLUMN `neighborhood_id` int(11) NULL DEFAULT 0 COMMENT '街道id' AFTER `neighborhood_name`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE,
//ADD INDEX `idx_address_id`(`address_id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address`
//MODIFY COLUMN `last_access` bigint(20) NOT NULL DEFAULT 0 COMMENT '最后更新时间' AFTER `open_object`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address`
//ADD COLUMN `high_risk_deadline` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '高危许可证截止时间' AFTER `last_access`,
//ADD COLUMN `high_risk_status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '高危许可证状态 1有效 2过期' AFTER `high_risk_deadline`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address`
//MODIFY COLUMN `address_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '游泳馆 ID' AFTER `id`,
//MODIFY COLUMN `lane` tinyint(2) NOT NULL DEFAULT 0 AFTER `latitude`;
//ALTER TABLE `swim_central_platform`.`swim_address`
//ADD COLUMN `legal_representative` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '法定代表人' AFTER `high_risk_deadline`,
//ADD COLUMN `social_credit_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '社会信用代码' AFTER `legal_representative`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//CREATE TABLE `swim_address_water_quality` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `address_id` varchar(32) NOT NULL DEFAULT '' COMMENT '场所主键id',
//  `address_name` varchar(32) NOT NULL DEFAULT '' COMMENT '场所名称',
//  `ph` varchar(10) NOT NULL DEFAULT '' COMMENT 'PH',
//  `ci` varchar(10) NOT NULL DEFAULT '' COMMENT 'CI',
//  `temperature` varchar(10) NOT NULL DEFAULT '' COMMENT '温度',
//  `turbidity` varchar(10) NOT NULL DEFAULT '' COMMENT '浊度',
//  `orp` varchar(10) NOT NULL DEFAULT '' COMMENT 'ORP',
//  `cod` varchar(10) NOT NULL DEFAULT '' COMMENT 'COD',
//  `conductivity` varchar(10) NOT NULL DEFAULT '' COMMENT '电导率',
//  `usea` varchar(10) NOT NULL DEFAULT '' COMMENT '尿素',
//  `device_no` varchar(10) NOT NULL DEFAULT '' COMMENT '设备编号',
//  `sampling_point` varchar(10) NOT NULL DEFAULT '' COMMENT '采样点',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
//  `create_time` int(11) NOT NULL DEFAULT '0',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_quality_id` (`quality_id`) USING BTREE,
//  KEY `idx_address_id` (`address_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='泳馆水质信息表';
//CREATE TABLE `swim_address_training_experience` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `experience_id` varchar(32) NOT NULL DEFAULT '' COMMENT '从业人员培训记录id',
//  `three_personnel_id` varchar(32) NOT NULL DEFAULT '' COMMENT '从业人员id',
//  `id_card` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证号',
//  `card_no` varchar(100) NOT NULL DEFAULT '' COMMENT '制卡卡号',
//  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '角色：01-池主任；02-救生组 长；03-水质处理员；03-检查',
//  `learning_date` varchar(10) NOT NULL DEFAULT '' COMMENT '学习日期',
//  `learning_content` varchar(500) NOT NULL DEFAULT '' COMMENT '学习内容',
//  `results` varchar(10) NOT NULL DEFAULT '' COMMENT '成绩',
//  `address_str` varchar(500) NOT NULL DEFAULT '' COMMENT '服务泳馆',
//  `last_access` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
//  `create_time` int(11) NOT NULL DEFAULT '0',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_experience_id` (`experience_id`) USING BTREE,
//  KEY `idx_three_personnel_id` (`three_personnel_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='从业人员培训记录表';
//CREATE TABLE `swim_address_three_personnel` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `personnel_id` varchar(32) NOT NULL DEFAULT '' COMMENT '从业人员id',
//  `id_card` varchar(50) NOT NULL DEFAULT '' COMMENT '身份证号',
//  `card_no` varchar(100) NOT NULL DEFAULT '' COMMENT '制卡卡号',
//  `date_of_issuance` varchar(100) NOT NULL DEFAULT '' COMMENT '发证日期-起始',
//  `date_of_issuance_end` varchar(100) NOT NULL DEFAULT '' COMMENT '发证日期-截止',
//  `id_card_image` varchar(100) NOT NULL DEFAULT '' COMMENT '身份证照片',
//  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
//  `nation` varchar(10) NOT NULL DEFAULT '' COMMENT '民族',
//  `gender` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '性别：0-女；1-男；',
//  `education` varchar(20) NOT NULL DEFAULT '' COMMENT '学历',
//  `account_address` varchar(500) NOT NULL DEFAULT '' COMMENT '户籍所在地地址',
//  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
//  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '人员类型：01-池主任；02-救 生组长；03-水质管理员；04- 检查人员；',
//  `level` varchar(10) NOT NULL DEFAULT '0' COMMENT '级别',
//  `age` tinyint(4) NOT NULL DEFAULT '0' COMMENT '年龄',
//  `card_status` varchar(10) NOT NULL DEFAULT '0' COMMENT '证件状态',
//  `work_year` int(11) NOT NULL DEFAULT '0' COMMENT '工作年限',
//  `service_area` varchar(10) NOT NULL DEFAULT '0' COMMENT '服务区域，如：黄埔区，杨浦 区（多个区域逗号分隔）',
//  `last_access` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
//  `create_time` int(11) NOT NULL DEFAULT '0',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_ id_card` (`id_card`) USING BTREE,
//  KEY `idx_presonnel_id` (`personnel_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='从业人员信息表';
//CREATE TABLE `swim_address_coach` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `coach_id` varchar(32) NOT NULL DEFAULT '' COMMENT '救生员/教练id',
//  `address_id` varchar(32) NOT NULL DEFAULT '' COMMENT '所属泳馆id',
//  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '人员类型（如：01-救生员、02-教练）',
//  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像 Url 地址',
//  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
//  `gender` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '性别：0-女；1-男；',
//  `birth` varchar(10) NOT NULL DEFAULT '' COMMENT '出生年月',
//  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
//  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
//  `introduction` varchar(6000) NOT NULL DEFAULT '0' COMMENT '个人简介',
//  `level` tinyint(4) NOT NULL DEFAULT '1' COMMENT '专业级别：01-初级；02-中级；03-高级；',
//  `last_access` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
//  `create_time` int(11) DEFAULT NULL,
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_address_id` (`address_id`) USING BTREE,
//  KEY `idx_ coach_id` (`coach_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游泳救生员/教练信息表';
//CREATE TABLE `swim_address_contact_person` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `contact_id` varchar(32) NOT NULL DEFAULT '' COMMENT '联系人id',
//  `address_id` varchar(32) NOT NULL DEFAULT '' COMMENT '所属泳馆id',
//  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
//  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '称呼',
//  `position` varchar(100) NOT NULL DEFAULT '' COMMENT '职位',
//  `gender` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '性别：0-女；1-男；',
//  `landline_phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
//  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
//  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
//  `is_default` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认联系人：0-是；1-否；',
//  `last_access` bigint(20) DEFAULT '0' COMMENT '最后更新时间',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
//  `create_time` int(11) DEFAULT NULL,
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_ contact_id` (`contact_id`) USING BTREE,
//  KEY `idx_address_id` (`address_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆联系人信息表';
//drop table swim_bk_user;
//drop table swim_auth_assignment;
//DROP TABLE swim_auth_item_child;
//DROP TABLE swim_auth_item;
//CREATE TABLE `swim_bk_user` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `gid` int(11) unsigned DEFAULT NULL COMMENT '企业id',
//  `username` varchar(32) NOT NULL,
//  `auth_key` varchar(255) NOT NULL,
//  `password_hash` varchar(256) NOT NULL,
//  `password_reset_token` varchar(256) DEFAULT NULL,
//  `nickname` varchar(256) NOT NULL,
//  `pid` int(11) DEFAULT NULL COMMENT '上级id',
//  `status` int(11) NOT NULL DEFAULT '2',
//  `create_time` int(11) NOT NULL,
//  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  `allowance` int(11) DEFAULT NULL,
//  `allowance_updated_at` int(11) DEFAULT NULL,
//  `avatar` varchar(255) DEFAULT NULL,
//  `role` varchar(255) DEFAULT '[]' COMMENT '1,赞助商',
//  `phone` varchar(32) DEFAULT NULL COMMENT '联系电话',
//  `email` varchar(100) DEFAULT NULL COMMENT 'Email',
//  `unionid` varchar(100) DEFAULT NULL,
//  `mpinfo` text COMMENT '微信其他信息',
//  `wsaf_urid` varchar(64) DEFAULT NULL COMMENT 'wsaf对应的用户ID',
//  `hp_urid` int(11) DEFAULT NULL COMMENT '黄浦平台用户id',
//  `asid` int(11) NOT NULL DEFAULT '0' COMMENT '协会id',
//  `password_lock_time` int(11) unsigned DEFAULT '0' COMMENT '用户密码锁定时间',
//  `password_lock_inventory` tinyint(4) unsigned DEFAULT '5' COMMENT '用户密码可以使用次数',
//  `last_login_time` datetime DEFAULT NULL COMMENT '最后一次登录时间',
//  `realname` varchar(255) DEFAULT NULL COMMENT '真实姓名',
//  `created_at` int(10) DEFAULT NULL,
//  `updated_at` timestamp NULL DEFAULT NULL,
//  PRIMARY KEY (`id`) USING BTREE,
//  UNIQUE KEY `idx_user_gid` (`username`) USING BTREE,
//  KEY `idx_user_token` (`auth_key`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;
//CREATE TABLE `swim_auth_role` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
//  `name` varchar(64) NOT NULL DEFAULT '',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
//  `gid` int(10) DEFAULT NULL COMMENT '组织ID',
//  PRIMARY KEY (`id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色表';
//CREATE TABLE `swim_auth_role_item` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
//  `role_id` int(11) NOT NULL DEFAULT '0',
//  `auth_item_id` int(11) NOT NULL DEFAULT '0',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
//  `actions` text COMMENT '方法集合',
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_role_item_id` (`role_id`,`auth_item_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限表';
//CREATE TABLE `swim_auth_assignment` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
//  `bkurid` int(11) NOT NULL DEFAULT '0',
//  `auth_id` int(11) NOT NULL DEFAULT '0',
//  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-role；2-auth item',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_bkurid_auth_type` (`bkurid`,`auth_id`,`type`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限分配表';
//CREATE TABLE `swim_bk_assignment` (
//  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
//  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
//  `created_at` int(11) DEFAULT NULL,
//  PRIMARY KEY (`item_name`,`user_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
//CREATE TABLE `swim_bk_rule` (
//  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
//  `data` blob,
//  `created_at` int(11) DEFAULT NULL,
//  `updated_at` int(11) DEFAULT NULL,
//  PRIMARY KEY (`name`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
//CREATE TABLE `swim_bk_item` (
//  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
//  `type` smallint(6) NOT NULL,
//  `description` text COLLATE utf8_unicode_ci,
//  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
//  `data` blob,
//  `created_at` int(11) DEFAULT NULL,
//  `updated_at` int(11) DEFAULT NULL,
//  PRIMARY KEY (`name`) USING BTREE,
//  KEY `rule_name` (`rule_name`) USING BTREE,
//  KEY `idx-auth_item-type` (`type`) USING BTREE,
//  CONSTRAINT `swim_bk_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `swim_bk_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
//CREATE TABLE `swim_bk_item_child` (
//  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
//  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
//  PRIMARY KEY (`parent`,`child`) USING BTREE,
//  KEY `child` (`child`) USING BTREE,
//  CONSTRAINT `swim_bk_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `swim_bk_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
//  CONSTRAINT `swim_bk_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `swim_bk_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
//CREATE TABLE `swim_auth_item` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
//  `pid` int(11) NOT NULL DEFAULT '0',
//  `path` varchar(64) NOT NULL DEFAULT '',
//  `name` varchar(64) NOT NULL DEFAULT '',
//  `label` varchar(64) NOT NULL DEFAULT '',
//  `component` varchar(64) NOT NULL DEFAULT '',
//  `redirect` varchar(64) DEFAULT NULL,
//  `hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-显示 1-隐藏',
//  `meta_title` varchar(64) NOT NULL DEFAULT '',
//  `meta_icon` varchar(64) NOT NULL DEFAULT '',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
//  `actions` text COMMENT '页面功能按钮',
//  `weight` int(11) unsigned DEFAULT '0' COMMENT '权重',
//  `jump_url` varchar(255) DEFAULT NULL COMMENT '第三方跳转',
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_pid` (`pid`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限表';
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (1, 0, 'couponlist', 'Rouponlist', '页面权限', '/coupon/index', '', 0, 'couponlist', 'el-icon-s-ticket', 2, 1607417910, '2020-12-10 10:22:55', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (2, 0, '/role', 'Role', '权限管理', '#', '/role/directory', 0, 'role', 'el-icon-s-custom', 1, 1607567569, '2021-03-17 11:25:15', NULL, 10, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (3, 2, 'roleindex', 'Rolendex', '页面权限', '/role/index', '', 0, 'pagerole', 'el-icon-s-management', 1, 1607568265, '2020-12-10 10:44:25', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (4, 2, 'directory', 'Airectory', '路由设置', '/role/directory', '', 0, 'directory', 'el-icon-s-order', 1, 1607568308, '2021-05-20 17:13:22', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (5, 0, '/coupon', 'Coupon', '优惠券管理', '#', '/coupon/couponlist', 0, 'coupon', 'el-icon-s-management', 2, 1607568391, '2021-02-24 14:13:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (6, 5, 'couponlist', 'Rouponlist', '优惠券列表', '/coupon/index', '', 0, 'couponlist', 'el-icon-s-ticket', 2, 1607568460, '2021-02-24 14:13:42', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (7, 0, '/coupon', 'Coupon', '优惠券管理', '#', '/coupon/couponlist', 0, 'coupon', 'el-icon-s-management', 2, 1607576105, '2020-12-10 12:55:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (8, 2, 'bkuser', 'Bkuser', '后台用户管理', '/role/user', '', 0, 'bkuser', 'el-icon-user-solid', 1, 1607654300, '2020-12-11 10:38:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (9, 0, '/match', 'Match', '活动管理', '#', '/match/matchindex', 0, 'match', 'el-icon-s-flag', 1, 1607680776, '2021-04-08 19:44:28', NULL, 99, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (10, 9, 'matchlist', 'Matchlist', '活动列表', '/match/index', '', 0, 'matchlist', 'el-icon-s-data', 1, 1607680950, '2021-02-26 12:30:45', '新增活动:macthadd;编辑活动:macthedit;删除:matchdel;审核展示:matchshow;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (11, 0, '/member', 'Member', '会员管理', '#', '/member/memberlist', 0, 'member', 'el-icon-user-solid', 1, 1608022594, '2021-03-17 11:25:46', NULL, 80, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (12, 11, 'memberlist', 'Memberlist', '会员列表', '/member/index', '', 0, 'memberlist', 'el-icon-star-on', 1, 1608022777, '2020-12-15 17:00:08', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (13, 11, 'aipush', 'Aipush', 'AI推送', '/member/aipush', '', 0, 'aipush', 'el-icon-s-promotion', 2, 1608022937, '2021-02-24 14:40:48', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (14, 11, 'tag', 'Tag', '标签列表', '/member/tag', '', 0, 'taglist', 'el-icon-s-claim', 2, 1608087992, '2021-02-24 14:40:55', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (15, 0, '/market', 'Market', '营销管理', '#', '/market/adlist', 0, 'market', 'el-icon-s-marketing', 1, 1608119785, '2021-03-17 11:25:59', NULL, 70, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (16, 15, 'adlist', 'Adlist', '消息管理', '/market/ad', '', 0, 'adlist', 'el-icon-picture', 1, 1608119965, '2020-12-16 19:59:25', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (17, 15, 'sms', 'Sms', '短信管理', '/market/sms', '', 0, 'smslist', 'el-icon-message', 1, 1608268794, '2020-12-18 13:19:54', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (18, 0, '/shop', 'Shop', '商品管理', '#', '/shop/shoptype', 0, 'shop', 'el-icon-s-shop', 1, 1608286439, '2021-03-17 11:26:27', NULL, 60, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (19, 18, 'shoptype', 'Shoptype', '商品标签', '/shop/shoptype', '', 0, 'shoptype', 'el-icon-menu', 1, 1608286908, '2020-12-18 18:26:27', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (20, 18, 'goods', 'Goods', '商品列表', '/shop/shoplist', '', 0, 'shoplist', 'el-icon-s-goods', 1, 1608286983, '2020-12-18 18:25:38', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (21, 18, 'orderlist', 'Orderlist', '订单列表', '/shop/orderlist', '', 0, 'orderlist', 'el-icon-s-order', 1, 1608287061, '2020-12-18 18:13:59', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (22, 0, 'testing', 'testing', '场馆test', 'testing', '', 0, '场馆管理', 'el-icon-menu', 2, 1608602801, '2020-12-22 16:19:05', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (23, 22, 'testing', 'testing', '篮球场馆1', 'testing', '', 0, '篮球', 'el-icon-user-solid', 2, 1608602877, '2020-12-22 15:03:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (24, 15, '/draw', 'Draw', '抽奖管理', '/market/draw', '', 0, 'draw', 'el-icon-s-marketing', 1, 1609138800, '2020-12-28 15:00:23', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (25, 9, 'registration/:matchid', 'Registration', '报名列表', '/match/registration', '', 1, 'registration', 'el-icon-s-management', 1, 1609316092, '2020-12-30 16:14:52', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (26, 11, 'm-user-detail/:id', 'MUserDetail', '会员详情', '/member/userdetail', '', 1, 'userdetail', 'el-icon-user', 2, 1609316190, '2021-02-24 15:43:43', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (27, 11, 'tag-user/:id', 'TagUser', '标签用户', '/member/taguser', '', 1, 'taguser', 'el-icon-user', 2, 1609316282, '2021-02-24 15:43:46', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (28, 11, 'push-user/:id', 'PushUser', '收件人列表', '/member/pushuser', '', 1, 'pushuser', 'el-icon-user', 2, 1609316365, '2021-02-24 15:45:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (29, 2, 'infouser', 'InfoUser', '更新信息', '/role/info', '', 1, 'info', 'el-icon-user', 1, 1609316420, '2020-12-30 16:20:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (30, 5, 'cou-detail/:id', 'Coupondetail', '券编辑', '/coupon/detail', '', 1, 'coupondetail', 'el-icon-user', 2, 1609316475, '2021-02-24 15:43:53', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (31, 18, 'goods-detail/:id', 'Shopdetail', '商品详情', '/shop/detail', '', 1, 'goodsdetail', 'el-icon-user', 1, 1609316534, '2020-12-30 16:22:14', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (32, 15, 'sms-detail/:id', 'Smsdetail', '发送详情', '/market/detail', '', 1, 'smsdetail', 'el-icon-user', 1, 1609316599, '2020-12-30 16:23:19', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (33, 15, 'sms-add', 'Addsms', '创建短信', '/market/add', '', 1, 'addsms', 'el-icon-user', 1, 1609316671, '2020-12-30 16:24:31', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (34, 15, 'draw-subset/:acid', 'SubsetDraw', '项目配置', '/market/drawsubset', '', 1, 'subsetdraw', 'el-icon-user', 1, 1609316750, '2020-12-30 16:25:50', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (35, 15, 'draw-user/:acid', 'UserDraw', '报名用户', '/market/drawuser', '', 1, 'userdraw', 'el-icon-user', 1, 1609316793, '2021-02-25 16:31:11', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (38, 0, 'couponlist', 'Rouponlist', '页面权限', '/coupon/index', '', 1, 'couponlist', 'el-icon-s-ticket', 2, 1609317162, '2021-02-24 15:45:28', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (39, 2, 'buttonrole', 'Buttonrole', '按钮功能', '/role/button', '', 0, 'btnrole', 'el-icon-setting', 2, 1611905628, '2021-02-25 16:16:34', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (40, 0, '/news', 'News', '新闻管理', '#', '/news/index', 0, 'news', 'el-icon-s-platform', 1, 1614249213, '2021-03-17 11:26:37', NULL, 50, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (41, 40, 'newslist', 'Newslist', '新闻列表', '/news/index', '', 0, 'newslist', 'el-icon-s-claim', 1, 1614249522, '2021-03-01 13:10:18', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (42, 9, 'macthdetail/:matchid', 'Macthdetail', '活动详情', '/match/matchdetail', '', 1, 'matchdetail', 'el-icon-picture', 1, 1614306864, '2021-07-19 16:17:07', '报名:mdodt;选手:mdodb;照片流:mdph;更多功能:mdoth;签到:masi;邀请码:mdcode;增值服务:addservice;', 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (43, 9, 'audit', 'Audit', '活动列表（审核）', '/match/audit', '', 0, 'auditlist', 'el-icon-s-claim', 1, 1614307703, '2021-02-26 16:50:36', '新增审核:auditadd;编辑审核:auditedit;删除审核:auditdel;报名:auditdetail;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (44, 9, 'upload/:matchid/:type', 'Upload', '上传材料', '/match/upload', '', 1, '上传材料', 'el-icon-upload', 1, 1614324241, '2021-03-02 18:52:19', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (45, 40, 'newsdetail/:id?', 'Newsdetail', '新闻详情', '/news/detail', '', 1, 'newsdetail', 'el-icon-s-order', 1, 1614578375, '2021-03-01 15:00:45', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (46, 9, 'distribution', 'Distribution', '配送列表', '/match/distribution', '', 0, 'distribution', 'el-icon-truck', 1, 1614760034, '2021-03-03 16:36:41', '新增配送:disadd;编辑配送:disedit;删除配送:disdel;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (47, 9, 'result/:matchid', 'Result', '成绩管理', '/match/result/index', '', 1, 'result', 'el-icon-tickets', 1, 1615200607, '2021-03-08 18:51:52', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (48, 9, 'result-config/:matchid', 'ResultConfig', '成绩配置', '/match/result/config', '', 1, 'resultConfig', 'el-icon-setting', 2, 1615202675, '2021-03-16 17:27:43', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (49, 9, 'image/:matchid', 'Image', '照片管理', '/match/image', '', 1, 'image', 'el-icon-picture', 1, 1615449692, '2021-03-11 16:01:32', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (50, 9, 'func/:matchid', 'Func', '更多功能', '/match/func', '', 1, 'func', 'el-icon-camera-solid', 1, 1615539995, '2021-03-12 17:06:35', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (51, 9, 'result/cert/:groupid', 'Cert', '证书配置', '/match/result/component/cert', '', 1, 'Cert', 'el-icon-menu', 1, 1615886813, '2021-03-16 17:33:16', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (52, 9, 'matchindex', 'Matchindex', '赛事列表', '/match/match', '', 0, 'matchindex', 'el-icon-s-data', 1, 1616047171, '2021-09-07 15:41:29', '新增赛事:mat_add;', 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (53, 9, 'match', 'Match', '赛事列表', '/match/match', '', 0, 'matchindex', 'el-icon-s-data', 2, 1616047203, '2021-03-18 14:00:21', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (54, 0, 'dads', 'dsadas', 'sdas', 'dasdd', '', 0, 'dasdas', 'dasd', 2, 1616117619, '2021-03-19 09:33:44', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (55, 9, 'publishmatch/:matchid', 'Publishmatch', '赛事编辑', '/match/publishmatch', '', 1, 'matchedit', 'el-icon-edit-outline', 1, 1616119866, '2021-03-19 10:11:06', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (56, 0, '/training', 'Training', '培训管理', '#', '/training/traininglist', 0, 'training', 'el-icon-menu', 1, 1616485360, '2021-04-08 10:47:22', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (57, 56, 'traininglist', 'Traininglist', '培训列表', '/training/list', '', 0, 'traininglist', 'el-icon-menu', 1, 1616485478, '2021-04-08 10:49:48', '创建培训:TrainingCreate;删除培训:TrainingDel;编辑培训:TrainingEdit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (58, 56, 'trainingedit/:training_id?', 'Trainingedit', '创建培训', '/training/edit', '', 1, 'trainingedit', 'el-icon-message', 1, 1616485628, '2021-04-02 09:59:18', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (59, 56, 'trainingdetail/:training_id', 'Trainingdetail', '培训详情', '/training/detail', '', 1, 'trainingdetail', 'el-icon-message', 1, 1616492179, '2021-04-07 17:25:39', '培训开设:TrainingYue;报名订单:TrainingOpen;学员管理:TrainingCert;预约管理:TrainingOrder;培训证书:TrainingUser;培训报名:TrainingRegister;培训编辑:TrainingEdit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (60, 15, 'template', 'Template', '订阅模版', '/market/template', '', 0, 'template', 'el-icon-s-comment', 1, 1616648461, '2021-03-25 13:01:01', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (61, 0, '/activity', 'Activity', '活动管理', '#', '/activity/acindex', 0, 'activity', 'el-icon-s-opportunity', 1, 1616983639, '2021-03-29 13:30:41', NULL, 90, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (62, 61, 'acindex', 'Acindex', '活动列表', '/activity/index', '', 0, 'activityindex', 'el-icon-s-promotion', 1, 1616984348, '2021-03-29 10:19:08', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (63, 61, 'acdetail/:matchid', 'Acdetail', '活动详情', '/activity/acdetail', '', 1, 'activitydel', 'el-icon-s-order', 1, 1616994178, '2021-03-29 14:17:40', '报名:acmdodt;选手:acmdodb;照片流:acmdph;更多功能:acmdoth;签到:acmasi;邀请码:acmdcode;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (64, 61, 'acedit/:matchid', 'Acedit', '活动编辑', '/activity/publishac', '', 1, 'activityedit', 'el-icon-s-order', 1, 1616994263, '2021-03-29 13:35:05', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (65, 61, 'acrtype/:matchid', 'Acrtype', '报名选手', '/activity/registertype', '', 1, 'registertype', 'el-icon-user-solid', 1, 1617000572, '2021-03-29 14:49:32', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (66, 61, 'acregistration/:matchid', 'Acregistration', '报名订单', '/activity/registration', '', 1, 'registration', 'el-icon-s-management', 1, 1617000733, '2021-03-29 14:52:13', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (67, 15, 'direction', 'Direction', '定向赛', '/market/direction', '', 0, 'direction', 'el-icon-s-promotion', 1, 1617010849, '2021-03-29 17:40:49', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (68, 15, 'diresubset/:activity_id', 'Diresubset', '任务列表', '/market/diresubset', '', 1, 'diresubset', 'el-icon-s-finance', 1, 1617011799, '2021-03-29 17:56:39', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (69, 56, 'trainingopen/:training_id', 'Trainingopen', '培训开设', '/training/trainopen', '', 1, 'trainingopen', 'el-icon-s-finance', 1, 1617075147, '2021-03-30 14:50:51', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (70, 15, 'direuser/:activity_id', 'Direuser', '报名列表', '/market/direuser', '', 1, 'direuser', 'el-icon-s-order', 1, 1617080552, '2021-03-30 13:02:33', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (71, 56, 'student/:training_id/:period_id?', 'Student', '学员列表', '/training/student', '', 1, 'student', 'el-icon-user-solid', 1, 1617168337, '2021-03-31 16:39:16', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (72, 56, 'perioduser/:training_id', 'Perioduser', '预约管理', '/training/perioduser', '', 1, 'perioduser', 'el-icon-s-order', 1, 1617180319, '2021-04-11 17:27:54', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (73, 56, 'trainorder/:training_id', 'Trainorder', '报名订单', '/training/order', '', 1, 'trainorder', 'el-icon-s-order', 1, 1617265661, '2021-04-01 16:38:15', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (74, 56, 'white/:training_id/:period_id', 'White', '学员白名单', '/training/white', '', 1, 'white', 'el-icon-user', 1, 1617679650, '2021-04-06 11:27:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (75, 56, 'traincert/:training_id', 'Traincert', '证书配置', '/training/cert', '', 1, 'cert', 'el-icon-picture-outline', 1, 1617688226, '2021-04-06 14:08:10', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (76, 56, 'trainingregister/:training_id', 'Trainingregister', '培训报名', '/training/trainingregister', '', 1, 'trainingregister', 'el-icon-s-management', 1, 1617759522, '2021-04-07 09:38:42', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (77, 56, 'perioduserlist', 'Perioduserlist', '学员列表', '/training/perioduserlist', '', 0, 'perioduserlist', 'el-icon-s-order', 1, 1618133289, '2021-04-11 17:30:04', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (78, 56, 'trainorderlist', 'Trainorderlist', '培训订单', '/training/trainorderlist', '', 0, 'trainorderlist', 'el-icon-s-order', 1, 1618212210, '2021-04-12 15:23:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (79, 56, 'banlist', 'banlist', '培训班次', '/training/banlist', '', 0, 'banlist', 'el-icon-s-order', 1, 1618215450, '2021-04-12 16:18:20', '导出:Export', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (80, 56, 'whiteuserimport/:training_id/:period_id', 'Whiteuserimport', '白名单导入', '/training/import', '', 1, '白名单导入', 'el-icon-s-order', 1, 1618280907, '2021-04-13 11:04:41', '导入:Import;提交:Submit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (81, 9, 'image-watermark', 'imageWatermark', '照片水印配置', '/match/imagewatermark', '', 1, '水印管理', 'el-icon-picture-outline', 1, 1620640789, '2021-05-10 18:00:11', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (82, 0, '/service', 'service', '服务管理', '#', '/service/ServiceIndex', 0, '服务管理', 'el-icon-s-cooperation', 1, 1620975588, '2021-06-25 13:32:28', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (83, 82, 'ServiceIndex', 'serviceIndex', '服务列表', '/service/index', '/service/index', 0, '服务列表', 'el-icon-s-management', 2, 1620975784, '2021-05-14 16:47:16', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (84, 0, '/record', 'Record', '申请记录', '#', '/record/ticketsrecord', 1, '申请记录', 'el-icon-s-order', 1, 1620976305, '2021-05-24 10:06:53', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (85, 82, 'ticketsrecord', 'Ticketsrecord', '订票记录', '/qyh/record/tickets', '', 1, '订票记录', 'el-icon-s-ticket', 1, 1620976382, '2021-07-22 13:40:11', NULL, 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (86, 82, 'roomsrecord', 'Roomsrecord', '订房记录', '/qyh/record/rooms', '', 1, '订房记录', 'el-icon-office-building', 1, 1620976674, '2021-06-04 17:58:50', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (87, 82, 'carsrecords', 'Carsrecords', '订车记录', '/qyh/record/cars', '', 1, '订车记录', 'el-icon-truck', 1, 1620976709, '2021-06-04 17:59:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (88, 84, 'allcars', 'allcars', '运营车辆', '/record/allcars', '/record/allcars', 1, '运营车辆', 'el-icon-truck', 2, 1620976772, '2021-05-14 15:41:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (89, 84, 'allcarstable', 'allcarstable', '车辆统计', '/record/allcarstable', '/record/allcarstable', 1, '车辆统计', 'el-icon-truck', 2, 1620976811, '2021-05-14 15:41:26', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (90, 84, 'distributionrooms', 'distributionrooms', '分配房间', '/record/distributionrooms', '/record/distributionrooms', 1, '分配房间', 'el-icon-truck', 2, 1620976847, '2021-05-14 15:43:40', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (91, 0, '/qyhmember', 'Member', '成员管理', '#', '/qyhmember/qyhmember', 0, '成员管理', 'el-icon-user', 1, 1620976909, '2021-05-20 15:03:37', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (92, 91, 'qyhmember', 'member', '成员列表', '/qyh/qyhmember/index', '', 0, '成员列表', 'el-icon-user', 1, 1620976945, '2021-05-20 14:42:38', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (93, 0, '/setting', 'Setting', '配置管理', '#', '/setting/Settingrooms', 0, '配置管理', 'el-icon-setting', 1, 1620977032, '2021-05-20 15:03:50', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (94, 93, 'Settingrooms', 'settingrooms', '客房管理', '/qyh/setting/rooms', '', 0, '客房管理', 'el-icon-office-building', 1, 1620977079, '2021-05-20 14:43:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (95, 93, 'Settingcars', 'settingcars', '车辆管理', '/qyh/setting/cars', '', 0, '车辆管理', 'el-icon-truck', 1, 1620977117, '2021-05-20 14:43:07', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (96, 0, '/apply', 'Apply', '我要申报', '#', '/apply/ticketsList', 1, '我要申报', 'el-icon-setting', 2, 1620977173, '2021-05-14 17:36:35', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (97, 82, 'ticketsList', 'TicketsList', '订票申报', '/service/ticketsList', '', 1, '订票申报', 'el-icon-office-building', 2, 1620977215, '2021-05-17 09:41:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (98, 82, 'carsList', 'CarsList', '订车申报', '/service/carsList', '', 1, '订车申报', 'el-icon-truck', 2, 1620977257, '2021-05-17 09:41:06', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (99, 82, 'roomsList', 'RoomsList', '订房申报', '/service/roomsList', '', 1, '订房申报', 'el-icon-truck', 2, 1620977289, '2021-05-17 09:41:02', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (100, 0, '/', 'index', 'shouye', '*', '', 0, 'shouye', 'dashboard', 2, 1620980395, '2021-05-14 16:34:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (101, 100, 'dashboard', 'Dashboard', 'dashboard', '/dashboard/index', '', 0, 'home', 'dashboard', 2, 1620980472, '2021-05-14 16:34:02', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (102, 0, '/', 'index', '首页', '#', '', 0, 'home', 'dashboard', 2, 1620980566, '2021-05-14 16:34:04', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (103, 102, 'dashboard', 'Dashboard', 'dashboard', '/dashboard/index', '', 0, 'home', 'dashboard', 2, 1620980665, '2021-05-14 16:34:06', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (104, 82, 'ServiceIndex', 'serviceIndex', '服务列表', '/service/index', '', 0, '服务列表', 'el-icon-s-management', 2, 1620982093, '2021-05-14 17:23:36', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (105, 82, 'Allcars', 'allcars', '运营车辆', '/qyh/record/allcars', '', 1, '运营车辆', 'el-icon-truck', 1, 1620982493, '2021-06-04 18:03:32', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (106, 82, 'Allcarstable', 'allcarstable', '车辆统计', '/qyh/record/allcarstable', '', 1, '车辆统计', 'el-icon-truck', 1, 1620982535, '2021-06-04 18:03:37', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (107, 82, 'Distributionrooms', 'distributionrooms', '分配房间', '/qyh/record/distributionrooms', '', 1, '分配房间', 'el-icon-truck', 1, 1620982573, '2021-06-04 18:03:41', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (108, 115, 'Applyservice', 'applyservice', '服务申报', '/qyh/service/applyser', '', 0, '服务申报', 'el-icon-s-claim', 2, 1620984201, '2021-06-21 10:34:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (109, 82, 'ServiceIndex', 'serviceIndex', '服务列表', '/qyh/service/index', '', 0, '服务列表', 'el-icon-s-management', 1, 1620984258, '2021-05-20 14:43:44', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (110, 115, 'ticketsList', 'TicketsList', '订票申报', '/qyh/service/ticketsList', '', 1, '订票申报', 'el-icon-s-management', 2, 1621215751, '2021-06-21 10:37:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (111, 115, 'carsList', 'CarsList', '订车申报', '/qyh/service/carsList', '', 1, '订车申报', 'el-icon-s-management', 2, 1621215809, '2021-06-21 11:21:49', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (112, 115, 'roomsList', 'RoomsList', '订房申报', '/qyh/service/roomsList', '', 1, '订房申报', 'el-icon-s-management', 2, 1621215862, '2021-06-21 11:22:53', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (113, 82, 'importfile', 'Importfile', '上传明细', '/qyh/service/importFile', '', 1, '上传明细', 'el-icon-s-management', 1, 1621233354, '2021-05-20 14:44:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (114, 82, 'AllroomTable', 'allroomtable', '住房汇总', '/qyh/record/allroomtable', '', 1, '住房汇总', 'el-icon-truck', 1, 1623377125, '2021-06-11 10:05:25', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (115, 0, '/service2', 'service2', '申报管理', '#', '/service2/Applyservice', 0, '申报管理', 'el-icon-s-cooperation', 1, 1624241490, '2021-06-25 13:30:18', NULL, 2, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (116, 115, 'Applyservice', 'applyservice', '服务申报', '/qyh/service/applyser', '', 0, '服务申报', 'el-icon-s-claim', 1, 1624242888, '2021-06-21 10:34:48', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (117, 115, 'ticketsList', 'TicketsList', '订票申报', '/qyh/service/ticketsList', '', 1, '订票申报', 'el-icon-s-management', 1, 1624243080, '2021-06-21 10:38:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (118, 115, 'carsList', 'CarsList', '订车申报', '/qyh/service/carsList', '', 1, '订车申报', 'el-icon-s-management', 1, 1624245751, '2021-06-21 11:22:31', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (119, 115, 'roomsList', 'RoomsList', '订房申报', '/qyh/service/roomsList', '', 1, '订房申报', 'el-icon-s-management', 1, 1624245809, '2021-06-21 11:23:29', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (120, 115, 'importfile2', 'Importfile2', '上传', '/qyh/service/importFile', '', 1, '上传', 'el-icon-s-management', 1, 1624246218, '2021-06-21 11:58:23', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (121, 119, 'carsList', 'CarsList', '订车申报', '/qyh/service/carsList', '', 1, '订车申报', 'el-icon-s-management', 1, 1624250731, '2021-07-16 13:33:41', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (122, 119, 'roomsList', 'RoomsList', '订房申报', '/qyh/service/roomsList', '', 1, '订房申报', 'el-icon-s-management', 1, 1624250772, '2021-07-16 13:33:44', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (123, 119, 'importfile2', 'Importfile2', '上传', '/qyh/service/importFile', '', 1, '上传', 'el-icon-s-management', 1, 1624250813, '2021-07-16 13:33:46', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (124, 119, 'Applyservice', 'applyservice', '服务申报', '/qyh/service/applyser', '', 0, '服务申报', 'el-icon-s-claim', 1, 1624250895, '2021-07-16 13:33:47', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (212, 0, '/venue', 'Venue', '场馆管理', '#', '/venue/list', 0, '场馆管理', 'el-icon-s-order', 1, 1623398654, '2021-06-29 14:38:26', NULL, 90, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (213, 212, 'venuelist', 'venuelist', '场馆列表', '/venue/list', '', 0, '场馆列表', 'el-icon-truck', 1, 1623398727, '2021-07-16 13:41:56', '新建场馆:addVenue;修改场馆:editVenue;删除场馆:delVenue', 100, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (214, 212, 'venueedit', 'Venueedit', '场馆编辑', '/venue/edit', '', 1, '场馆编辑', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:41:58', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (215, 212, 'venuenotice', 'venuenotice', '场馆通知', '/venue/notice/index', '', 0, '场馆通知', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (216, 212, 'venuenoticedetail', 'venuenoticedetail', '场馆通知详情', '/venue/notice/detail', '', 1, '场馆通知详情', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:02', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (217, 212, 'venueorder', 'Venueorder', '散客预约列表', '/venue/order/index', '', 0, '散客预约列表', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:04', '查看:venueorderview;修改状态:venuechangestate;取消预订:venueCancelOrder;创建:venueordercreate', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (218, 212, 'venuedrew', 'Venuedrew', '兑换管理', '/venue/drew/index', '', 0, '兑换管理', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:06', ';新建:venuedrewadd', 90, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (219, 212, 'venuedrewuser/:acid?', 'Venuedrewuser', '兑换记录', '/venue/drew/user', '', 1, '兑换记录', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:07', '新建:venuedrewadd', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (220, 212, '/venue/order/import', 'VenueOrderImport', '创建预订订单', '/venue/order/import', '', 1, '创建预订订单', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:09', '导入:reserveImport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (221, 212, '/venue/order/group', 'VenueOrderGroup', '团队预约记录', '/venue/order/group', '', 0, '团队预约记录', 'el-icon-truck', 1, 1623398787, '2021-08-06 16:23:10', '新建:reserveGroupAdd;取消:venueCancelOrder;', 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (222, 212, '/venue/order/groupinfo', 'VenueOrderGroupInfo', '团队详情', '/venue/order/groupinfo', '', 1, '团队详情', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:13', '编辑:reserveGroupInfoEdit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (232, 0, '/statistics', 'Statistics', '数据统计', '#', '/statistics/reserve', 0, '数据统计', 'el-icon-s-order', 1, 1623398654, '2021-07-09 14:46:51', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (233, 232, 'reserve', 'Statisticsreserve', '预约人数统计', '/statistics/reserve', '', 0, '预约人数统计', 'el-icon-truck', 1, 1623398787, '2021-07-16 14:35:32', '导出:reserveExport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (234, 232, 'exchange', 'Statisticsexchange', '兑换统计', '/statistics/exchange', '', 0, '兑换统计', 'el-icon-truck', 1, 1623398787, '2021-07-16 14:35:33', '导出:exchangeExport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (235, 232, 'inout', 'Statisticsinout', '出入统计', '/statistics/inout', '', 0, '出入统计', 'el-icon-truck', 1, 1623398787, '2021-07-16 14:48:45', '导出:inoutExport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (237, 9, 'addservice/:matchid', 'Addservice', '增值服务', '/match/service', '', 1, 'addser', 'el-icon-s-order', 1, 1626683560, '2021-07-19 16:32:40', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (238, 40, 'newsaudit', 'Newsaudit', '新闻审核', '/news/audit', '', 0, 'newsaudit', 'el-icon-s-release', 1, 1626833726, '2021-07-21 10:15:26', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (239, 40, 'newsreview', 'Newsreview', '审核新闻', '/news/review', '', 0, 'nreview', 'el-icon-s-release', 1, 1626833865, '2021-07-21 10:17:45', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (240, 40, 'renewsdetail/:id?', 'ReNewsdetail', '新闻详情', '/news/redetail', '', 1, 'newsdetail', 'el-icon-s-order', 1, 1626933357, '2021-07-22 13:55:57', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (241, 212, '/venue/order/close', 'VenueClose', '封馆记录', '/venue/close', '', 0, '封馆记录', 'el-icon-truck', 1, 1623398787, '2021-07-27 14:04:59', NULL, 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (242, 82, 'ticketcount', 'ticketCount', '订票汇总', '/qyh/record/ticketcount', '', 1, '订票汇总', 'el-icon-document-copy', 1, 1631242603, '2021-09-10 10:56:43', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (1, 2, 1, 2, 1607420751, '2020-12-10 13:35:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (2, 2, 2, 1, 1607578556, '2020-12-10 13:35:56', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (3, 2, 3, 1, 1607652060, '2020-12-11 10:01:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (4, 2, 4, 1, 1607652060, '2020-12-11 10:01:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (5, 2, 5, 2, 1607652060, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (6, 2, 6, 2, 1607652060, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (7, 5, 2, 2, 1607653964, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (8, 5, 3, 2, 1607653964, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (9, 5, 4, 2, 1607653964, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (10, 5, 5, 2, 1607653965, '2021-02-26 18:12:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (11, 5, 6, 2, 1607653965, '2021-02-26 18:12:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (12, 4, 5, 2, 1607653971, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (13, 4, 6, 2, 1607653971, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (14, 2, 8, 1, 1607654318, '2020-12-11 10:38:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (15, 2, 9, 2, 1607680965, '2021-02-26 10:55:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (16, 2, 10, 2, 1607680966, '2021-04-08 19:44:52', '[\"macthadd\",\"macthedit\",\"matchdel\",\"matchshow\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (17, 5, 8, 2, 1607682564, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (18, 4, 9, 2, 1607682631, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (19, 4, 10, 1, 1607682631, '2021-03-04 10:55:48', '[\"macthadd\",\"macthedit\",\"matchdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (20, 2, 11, 1, 1608022974, '2021-02-25 20:09:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (21, 2, 12, 1, 1608022974, '2020-12-15 17:02:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (22, 2, 13, 2, 1608022974, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (23, 2, 14, 2, 1608120030, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (24, 2, 15, 1, 1608120030, '2020-12-16 20:00:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (25, 2, 16, 1, 1608120030, '2020-12-16 20:00:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (26, 4, 11, 1, 1608205573, '2020-12-17 19:46:13', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (27, 4, 12, 1, 1608205573, '2020-12-17 19:46:13', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (28, 4, 13, 2, 1608205573, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (29, 4, 14, 2, 1608205573, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (30, 4, 15, 2, 1608205573, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (31, 4, 16, 2, 1608205574, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (32, 2, 17, 1, 1608287154, '2020-12-18 18:25:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (33, 2, 18, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (34, 2, 19, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (35, 2, 20, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (36, 2, 21, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (37, 2, 18, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (38, 2, 19, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (39, 2, 20, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (40, 2, 21, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (41, 6, 5, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (42, 6, 6, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (43, 6, 9, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (44, 6, 10, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (45, 6, 11, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (46, 6, 12, 2, 1608540989, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (47, 6, 13, 2, 1608540989, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (48, 6, 14, 2, 1608540989, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (49, 2, 22, 2, 1608602823, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (50, 6, 16, 2, 1608618997, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (51, 6, 22, 2, 1608620600, '2020-12-22 15:03:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (52, 4, 17, 2, 1608807199, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (53, 4, 18, 2, 1608807199, '2020-12-28 19:35:04', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (54, 4, 19, 1, 1608807199, '2020-12-24 18:53:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (55, 4, 20, 2, 1608807199, '2020-12-28 19:35:04', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (56, 4, 21, 1, 1608807199, '2020-12-24 18:53:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (57, 4, 24, 2, 1609155304, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (58, 4, 18, 1, 1609155316, '2020-12-28 19:35:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (59, 4, 20, 1, 1609155316, '2020-12-28 19:35:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (60, 7, 11, 2, 1609232167, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (61, 7, 12, 2, 1609232167, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (62, 7, 13, 2, 1609232167, '2021-02-26 10:52:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (63, 7, 14, 2, 1609232167, '2021-02-26 10:52:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (64, 8, 12, 2, 1611117191, '2021-02-26 17:57:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (65, 2, 29, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (66, 2, 25, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (67, 2, 24, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (68, 2, 32, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (69, 2, 33, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (70, 2, 34, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (71, 2, 35, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (72, 2, 31, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (73, 2, 40, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (74, 2, 41, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (75, 7, 25, 2, 1614307937, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (76, 7, 42, 2, 1614307937, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (77, 7, 43, 2, 1614307937, '2021-03-23 17:14:33', '[\"auditadd\",\"auditedit\",\"auditdel\",\"auditdetail\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (78, 7, 15, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (79, 7, 16, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (80, 7, 17, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (81, 7, 24, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (82, 7, 32, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (83, 7, 33, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (84, 7, 34, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (85, 7, 35, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (86, 7, 18, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (87, 7, 19, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (88, 7, 20, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (89, 7, 21, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (90, 7, 31, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (91, 7, 40, 2, 1614307938, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (92, 7, 41, 2, 1614307938, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (93, 2, 42, 1, 1614308106, '2021-07-19 16:18:28', '[\"mdodt\",\"mdodb\",\"mdph\",\"mdoth\",\"masi\",\"mdcode\",\"addservice\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (94, 2, 9, 2, 1614308461, '2021-02-26 11:06:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (95, 2, 43, 2, 1614308461, '2021-02-26 11:06:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (96, 2, 9, 2, 1614309981, '2021-02-26 11:34:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (97, 2, 43, 2, 1614309981, '2021-02-26 11:34:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (98, 2, 9, 2, 1614314751, '2021-02-26 13:34:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (99, 2, 43, 2, 1614314751, '2021-02-26 13:34:52', '[\"auditadd\",\"auditedit\",\"auditdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (100, 2, 9, 2, 1614318498, '2021-02-26 14:32:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (101, 2, 43, 2, 1614318498, '2021-02-26 14:32:29', '[\"auditadd\",\"auditedit\",\"auditdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (102, 2, 9, 2, 1614323466, '2021-02-26 16:51:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (103, 2, 43, 2, 1614323466, '2021-02-26 16:51:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (104, 2, 44, 2, 1614325263, '2021-02-26 16:51:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (105, 7, 44, 2, 1614329497, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (106, 8, 9, 2, 1614333440, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (107, 8, 10, 2, 1614333440, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (108, 8, 25, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (109, 8, 42, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (110, 8, 43, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (111, 8, 44, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (112, 8, 40, 1, 1614333441, '2021-02-26 17:57:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (113, 8, 41, 1, 1614333441, '2021-02-26 17:57:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (114, 5, 29, 2, 1614334350, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (115, 8, 2, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (116, 8, 3, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (117, 8, 4, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (118, 8, 8, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (119, 8, 29, 1, 1614334601, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (120, 8, 11, 1, 1614334601, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (121, 8, 12, 1, 1614334601, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (122, 5, 9, 2, 1614334622, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (123, 5, 10, 2, 1614334622, '2021-03-23 13:29:52', '[\"macthadd\",\"macthedit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (124, 5, 25, 1, 1614334622, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (125, 5, 42, 1, 1614334622, '2021-03-05 10:40:55', '[\"mdodt\",\"mdodb\",\"mdph\",\"mdoth\",\"masi\",\"mdcode\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (126, 5, 43, 2, 1614334622, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (127, 5, 44, 2, 1614334622, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (128, 5, 40, 1, 1614334622, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (129, 5, 41, 1, 1614334622, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (130, 2, 43, 2, 1614338498, '2021-02-26 19:23:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (131, 2, 45, 1, 1614581421, '2021-03-01 14:50:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (132, 2, 46, 2, 1614760460, '2021-03-05 10:46:29', '[\"disadd\",\"disedit\",\"disdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (133, 5, 46, 2, 1614760481, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (134, 5, 45, 1, 1614760481, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (135, 5, 8, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (136, 5, 29, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (137, 5, 11, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (138, 5, 12, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (139, 5, 15, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (140, 5, 16, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (141, 5, 17, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (142, 5, 24, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (143, 5, 32, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (144, 5, 33, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (145, 5, 34, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (146, 5, 35, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (147, 5, 18, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (148, 5, 19, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (149, 5, 20, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (150, 5, 21, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (151, 5, 31, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (152, 4, 8, 1, 1614826535, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (153, 4, 29, 1, 1614826535, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (154, 4, 25, 1, 1614826535, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (155, 4, 42, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (156, 4, 43, 2, 1614826536, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (157, 4, 44, 2, 1614826536, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (158, 4, 46, 2, 1614826536, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (159, 4, 32, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (160, 4, 33, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (161, 4, 34, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (162, 4, 35, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (163, 4, 31, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (164, 4, 40, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (165, 4, 41, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (166, 4, 45, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (167, 2, 47, 1, 1615200796, '2021-03-08 18:53:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (168, 2, 48, 2, 1615202716, '2021-03-16 17:29:25', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (169, 2, 49, 1, 1615456236, '2021-03-11 17:50:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (170, 2, 50, 1, 1615540012, '2021-03-12 17:06:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (171, 2, 51, 1, 1615886965, '2021-03-16 17:29:25', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (172, 2, 52, 2, 1616047490, '2021-04-08 19:45:09', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (173, 2, 55, 1, 1616119948, '2021-03-19 10:12:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (174, 5, 3, 1, 1616477369, '2021-03-23 13:29:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (175, 5, 47, 1, 1616477391, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (176, 5, 49, 1, 1616477391, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (177, 5, 50, 1, 1616477391, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (178, 5, 51, 1, 1616477392, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (179, 5, 52, 1, 1616477392, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (180, 5, 55, 1, 1616477392, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (181, 2, 56, 1, 1616486131, '2021-03-23 15:55:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (182, 2, 57, 1, 1616486131, '2021-04-08 10:50:26', '[\"TrainingCreate\",\"TrainingDel\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (183, 2, 58, 1, 1616486131, '2021-03-23 15:55:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (184, 9, 29, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (185, 9, 16, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (186, 9, 24, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (187, 9, 32, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (188, 9, 34, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (189, 9, 35, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (190, 9, 18, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (191, 9, 19, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (192, 9, 20, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (193, 9, 21, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (194, 9, 31, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (195, 9, 9, 1, 1616488286, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (196, 9, 25, 1, 1616488286, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (197, 9, 42, 1, 1616488286, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (198, 9, 47, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (199, 9, 49, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (200, 9, 50, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (201, 9, 51, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (202, 9, 52, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (203, 9, 55, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (204, 9, 40, 2, 1616488287, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (205, 9, 41, 2, 1616488287, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (206, 9, 45, 2, 1616488287, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (207, 9, 10, 1, 1616490862, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (208, 9, 43, 1, 1616490862, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (209, 9, 44, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (210, 9, 46, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (211, 9, 11, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (212, 9, 12, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (213, 9, 15, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (214, 9, 17, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (215, 9, 33, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (216, 7, 56, 1, 1616490873, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (217, 7, 57, 1, 1616490873, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (218, 7, 58, 1, 1616490873, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (219, 7, 40, 1, 1616490881, '2021-03-23 17:14:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (220, 7, 41, 1, 1616490881, '2021-03-23 17:14:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (221, 7, 45, 1, 1616490881, '2021-03-23 17:14:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (222, 2, 59, 1, 1616492266, '2021-04-07 17:25:52', '[\"TrainingYue\",\"TrainingOrder\",\"TrainingOpen\",\"TrainingCert\",\"TrainingUser\",\"TrainingRegister\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (223, 2, 60, 1, 1616648475, '2021-03-25 13:01:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (224, 2, 61, 1, 1616983953, '2021-03-29 10:12:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (225, 2, 62, 1, 1616984384, '2021-03-29 10:19:45', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (226, 2, 63, 1, 1616994271, '2021-04-29 20:29:36', '[\"acmdodt\",\"acmdodb\",\"acmdph\",\"acmdoth\",\"acmasi\",\"acmdcode\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (227, 2, 64, 1, 1616994271, '2021-03-29 13:04:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (228, 2, 65, 1, 1617000947, '2021-03-29 14:55:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (229, 2, 66, 1, 1617000947, '2021-03-29 14:55:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (230, 2, 67, 1, 1617010896, '2021-03-29 17:41:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (231, 2, 68, 1, 1617012147, '2021-03-29 18:02:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (232, 2, 70, 1, 1617081156, '2021-03-30 13:12:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (233, 2, 69, 1, 1617081156, '2021-03-30 13:12:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (234, 2, 71, 1, 1617168390, '2021-03-31 13:26:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (235, 2, 72, 1, 1617180346, '2021-03-31 16:45:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (236, 2, 73, 1, 1617265704, '2021-04-01 16:28:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (237, 2, 74, 1, 1617679663, '2021-04-06 11:27:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (238, 2, 75, 1, 1617688231, '2021-04-06 13:50:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (239, 2, 76, 1, 1617759641, '2021-04-07 09:40:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (240, 2, 46, 1, 1617776217, '2021-04-07 14:17:17', '[\"disadd\",\"disedit\",\"disdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (241, 2, 10, 1, 1617882308, '2021-08-19 14:43:13', '[\"macthedit\",\"matchdel\",\"matchshow\",\"macthadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (242, 2, 77, 1, 1618133324, '2021-04-11 17:28:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (243, 2, 78, 1, 1618212278, '2021-04-12 15:24:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (244, 2, 79, 1, 1618215474, '2021-04-12 16:17:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (245, 2, 80, 1, 1618280915, '2021-04-13 10:28:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (246, 11, 56, 1, 1618454022, '2021-04-15 10:33:42', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (247, 11, 57, 1, 1618454023, '2021-04-15 10:33:59', '[\"TrainingCreate\",\"TrainingDel\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (248, 11, 58, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (249, 11, 59, 1, 1618454023, '2021-04-15 10:33:59', '[\"TrainingYue\",\"TrainingOpen\",\"TrainingCert\",\"TrainingOrder\",\"TrainingUser\",\"TrainingRegister\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (250, 11, 69, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (251, 11, 71, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (252, 11, 72, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (253, 11, 73, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (254, 11, 74, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (255, 11, 75, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (256, 11, 76, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (257, 11, 77, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (258, 11, 78, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (259, 11, 79, 1, 1618454023, '2021-04-15 10:33:59', '[\"Export\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (260, 11, 80, 1, 1618454023, '2021-04-15 10:33:59', '[\"Import\",\"Submit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (261, 10, 56, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (262, 10, 57, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (263, 10, 58, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (264, 10, 59, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (265, 10, 69, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (266, 10, 71, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (267, 10, 72, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (268, 10, 73, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (269, 10, 74, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (270, 10, 75, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (271, 10, 76, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (272, 10, 77, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (273, 10, 78, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (274, 10, 79, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (275, 10, 80, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (276, 2, 9, 1, 1619699349, '2021-04-29 20:29:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (277, 2, 43, 1, 1619699349, '2021-04-29 20:29:36', '[\"auditadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (278, 2, 44, 1, 1619699349, '2021-04-29 20:29:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (279, 2, 52, 1, 1619699349, '2021-09-07 15:41:43', '[\"mat_add\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (280, 2, 81, 1, 1620640840, '2021-05-10 18:00:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (281, 13, 2, 1, 1620975988, '2021-05-14 15:06:28', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (282, 13, 3, 1, 1620975988, '2021-05-14 15:06:28', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (283, 13, 4, 1, 1620975988, '2021-05-14 15:06:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (284, 13, 8, 1, 1620975988, '2021-05-14 15:06:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (285, 13, 29, 1, 1620975988, '2021-05-14 15:06:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (286, 13, 82, 1, 1620975999, '2021-05-14 15:06:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (287, 13, 83, 1, 1620975999, '2021-05-14 15:06:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (288, 12, 2, 2, 1620976008, '2021-05-14 15:31:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (289, 12, 3, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (290, 12, 4, 2, 1620976008, '2021-05-14 15:31:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (291, 12, 8, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (292, 12, 29, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (293, 12, 82, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (294, 12, 83, 2, 1620976008, '2021-05-14 16:48:28', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (295, 12, 84, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (296, 12, 85, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (297, 12, 86, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (298, 12, 87, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (299, 12, 88, 2, 1620977512, '2021-05-14 16:26:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (300, 12, 89, 2, 1620977512, '2021-05-14 16:26:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (301, 12, 90, 2, 1620977512, '2021-05-14 16:26:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (302, 12, 91, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (303, 12, 92, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (304, 12, 93, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (305, 12, 94, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (306, 12, 95, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (307, 12, 96, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (308, 12, 97, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (309, 12, 98, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (310, 12, 99, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (311, 14, 8, 2, 1620977585, '2021-06-15 16:53:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (312, 14, 29, 2, 1620977585, '2021-06-15 16:53:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (313, 14, 82, 2, 1620977585, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (314, 14, 83, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (315, 14, 84, 2, 1620977585, '2021-06-04 18:04:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (316, 14, 85, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (317, 14, 86, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (318, 14, 87, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (319, 14, 88, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (320, 14, 89, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (321, 14, 90, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (322, 14, 91, 2, 1620977585, '2021-05-31 09:44:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (323, 14, 92, 2, 1620977585, '2021-05-31 09:44:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (324, 14, 93, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (325, 14, 94, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (326, 14, 95, 1, 1620977585, '2021-05-14 15:33:06', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (327, 14, 96, 2, 1620977585, '2021-05-14 17:37:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (328, 14, 97, 2, 1620977586, '2021-05-17 09:44:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (329, 14, 98, 2, 1620977586, '2021-05-17 09:44:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (330, 14, 99, 2, 1620977586, '2021-05-17 09:44:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (331, 15, 82, 2, 1620977612, '2021-05-14 17:28:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (332, 15, 83, 2, 1620977612, '2021-05-14 17:25:56', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (333, 15, 91, 1, 1620977612, '2021-05-14 15:33:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (334, 15, 92, 1, 1620977612, '2021-05-14 15:33:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (335, 15, 96, 2, 1620977612, '2021-05-14 17:37:59', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (336, 15, 97, 2, 1620977612, '2021-05-17 09:44:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (337, 15, 98, 2, 1620977612, '2021-05-17 09:44:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (338, 15, 99, 2, 1620977612, '2021-05-17 09:44:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (339, 12, 100, 2, 1620980761, '2021-05-14 16:41:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (340, 12, 101, 2, 1620980761, '2021-05-14 16:41:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (341, 12, 104, 2, 1620982108, '2021-05-14 17:24:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (342, 12, 84, 2, 1620982586, '2021-06-04 18:03:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (343, 12, 85, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (344, 12, 86, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (345, 12, 87, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (346, 12, 105, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (347, 12, 106, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (348, 12, 107, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (349, 12, 91, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (350, 12, 92, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (351, 12, 93, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (352, 12, 94, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (353, 12, 95, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (354, 12, 96, 2, 1620982586, '2021-05-14 17:37:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (355, 12, 97, 2, 1620982586, '2021-05-17 09:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (356, 12, 98, 2, 1620982586, '2021-05-17 09:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (357, 12, 99, 2, 1620982586, '2021-05-17 09:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (358, 12, 108, 2, 1620984271, '2021-06-21 10:35:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (359, 12, 109, 1, 1620984271, '2021-05-14 17:24:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (360, 14, 108, 2, 1620984335, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (361, 14, 109, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (362, 14, 105, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (363, 14, 106, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (364, 14, 107, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (365, 15, 108, 2, 1620984356, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (366, 15, 109, 2, 1620984356, '2021-05-14 17:28:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (367, 12, 110, 2, 1621215872, '2021-06-21 10:39:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (368, 12, 111, 2, 1621215872, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (369, 12, 112, 2, 1621215872, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (370, 14, 110, 2, 1621215886, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (371, 14, 111, 2, 1621215886, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (372, 14, 112, 2, 1621215886, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (373, 12, 2, 1, 1621228135, '2021-05-17 13:08:55', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (374, 12, 4, 1, 1621228135, '2021-05-17 13:08:55', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (375, 12, 113, 1, 1621233368, '2021-05-17 14:36:09', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (376, 14, 113, 1, 1621233375, '2021-05-17 14:36:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (377, 15, 113, 2, 1621233381, '2021-06-21 11:59:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (378, 15, 110, 2, 1622425734, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (379, 15, 111, 2, 1622425734, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (380, 15, 112, 2, 1622425734, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (381, 14, 82, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (382, 14, 108, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (383, 14, 110, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (384, 14, 111, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (385, 14, 112, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (386, 12, 114, 1, 1623377264, '2021-06-11 10:07:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (387, 14, 114, 1, 1623747194, '2021-06-15 16:53:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (388, 15, 115, 1, 1624241833, '2021-06-21 10:17:13', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (389, 12, 115, 1, 1624242021, '2021-06-21 10:20:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (390, 12, 116, 1, 1624242905, '2021-06-21 10:35:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (391, 12, 117, 1, 1624243169, '2021-06-21 10:39:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (392, 12, 118, 1, 1624245837, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (393, 12, 119, 1, 1624245837, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (394, 15, 116, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (395, 15, 117, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (396, 15, 118, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (397, 15, 119, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (398, 15, 120, 1, 1624247973, '2021-06-21 11:59:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (399, 12, 120, 1, 1624415031, '2021-06-23 10:23:51', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (400, 14, 29, 1, 1624425042, '2021-06-23 13:10:42', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (401, 14, 82, 1, 1624425042, '2021-06-23 13:10:42', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (402, 15, 29, 1, 1624425050, '2021-06-23 13:10:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (403, 15, 82, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (404, 15, 85, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (405, 15, 86, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (406, 15, 87, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (407, 15, 105, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (408, 15, 106, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (409, 15, 107, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (410, 15, 109, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (411, 15, 113, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (412, 15, 114, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (413, 15, 84, 2, 1624599390, '2021-06-25 13:38:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (414, 15, 93, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (415, 15, 94, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (416, 15, 95, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (417, 2, 131, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (418, 2, 136, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (419, 2, 132, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (420, 2, 133, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (421, 2, 134, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (422, 2, 135, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (423, 2, 140, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (424, 2, 141, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (425, 2, 142, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (426, 2, 143, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (427, 2, 144, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (428, 2, 125, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (429, 2, 126, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (430, 2, 127, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (431, 2, 137, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (432, 2, 138, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (433, 2, 139, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (434, 2, 212, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (435, 2, 213, 1, 1626414106, '2021-07-22 14:35:38', '[\"editVenue\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (436, 2, 214, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (437, 2, 215, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (438, 2, 216, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (439, 2, 217, 1, 1626414106, '2021-08-06 16:16:38', '[\"venueordercreate\",\"venueCancelOrder\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (440, 2, 218, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (441, 2, 219, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (442, 2, 220, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (443, 2, 221, 1, 1626414106, '2021-07-19 18:09:01', '[\"reserveGroupAdd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (444, 2, 222, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (445, 16, 11, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (446, 16, 12, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (447, 16, 16, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (448, 16, 24, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (449, 16, 212, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (450, 16, 213, 1, 1626415820, '2021-07-19 14:38:26', '[\"addVenue\",\"editVenue\",\"delVenue\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (451, 16, 214, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (452, 16, 215, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (453, 16, 216, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (454, 16, 217, 1, 1626415820, '2021-07-19 12:53:58', '[\"venueorderview\",\"venuechangestate\",\"venueCancelOrder\",\"venueordercreate\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (455, 16, 218, 1, 1626415820, '2021-07-19 14:38:26', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (456, 16, 219, 1, 1626415820, '2021-07-19 14:38:26', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (457, 16, 220, 1, 1626415820, '2021-07-19 14:38:26', '[\"reserveImport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (458, 16, 221, 1, 1626415820, '2021-07-19 12:53:58', '[\"reserveGroupAdd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (459, 16, 222, 1, 1626415820, '2021-07-19 14:38:27', '[\"reserveGroupInfoEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (460, 2, 232, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (461, 2, 233, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (462, 2, 234, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (463, 2, 235, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (464, 2, 237, 1, 1626683571, '2021-07-19 16:32:51', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (465, 2, 238, 1, 1626833992, '2021-07-21 10:19:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (466, 2, 239, 1, 1626833992, '2021-07-21 10:19:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (467, 2, 240, 1, 1626933623, '2021-07-22 14:00:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (468, 2, 241, 1, 1627365908, '2021-07-27 14:05:08', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (469, 17, 9, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (470, 17, 10, 1, 1630996455, '2021-09-07 15:39:06', '[\"macthadd\",\"macthedit\",\"matchdel\",\"matchshow\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (471, 17, 25, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (472, 17, 42, 1, 1630996455, '2021-09-07 15:39:06', '[\"mdodt\",\"mdodb\",\"mdph\",\"mdoth\",\"masi\",\"mdcode\",\"addservice\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (473, 17, 43, 1, 1630996455, '2021-09-07 15:44:05', '[\"auditadd\",\"auditedit\",\"auditdetail\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (474, 17, 44, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (475, 17, 46, 1, 1630996455, '2021-09-07 15:44:05', '[\"disedit\",\"disadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (476, 17, 47, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (477, 17, 49, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (478, 17, 50, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (479, 17, 51, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (480, 17, 52, 1, 1630996455, '2021-09-07 15:41:49', '[\"mat_add\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (481, 17, 55, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (482, 17, 81, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (483, 17, 237, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (484, 17, 11, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (485, 17, 12, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (486, 17, 15, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (487, 17, 16, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (488, 17, 17, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (489, 17, 24, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (490, 17, 32, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (491, 17, 33, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (492, 17, 34, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (493, 17, 35, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (494, 17, 60, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (495, 17, 67, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (496, 17, 68, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (497, 17, 70, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (498, 17, 18, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (499, 17, 19, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (500, 17, 20, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (501, 17, 21, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (502, 17, 31, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (503, 17, 40, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (504, 17, 41, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (505, 17, 45, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (506, 17, 238, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (507, 17, 239, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (508, 17, 240, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (509, 17, 56, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (510, 17, 57, 1, 1630996456, '2021-09-07 15:44:05', '[\"TrainingCreate\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (511, 17, 58, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (512, 17, 59, 1, 1630996456, '2021-09-07 15:44:06', '[\"TrainingRegister\",\"TrainingYue\",\"TrainingEdit\",\"TrainingOpen\",\"TrainingCert\",\"TrainingOrder\",\"TrainingUser\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (513, 17, 69, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (514, 17, 71, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (515, 17, 72, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (516, 17, 73, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (517, 17, 74, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (518, 17, 75, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (519, 17, 76, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (520, 17, 77, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (521, 17, 78, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (522, 17, 79, 1, 1630996456, '2021-09-07 15:44:06', '[\"Export\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (523, 17, 80, 1, 1630996456, '2021-09-07 15:44:06', '[\"Submit\",\"Import\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (524, 17, 61, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (525, 17, 62, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (526, 17, 63, 1, 1630996456, '2021-09-07 15:44:06', '[\"acmdodt\",\"acmdodb\",\"acmdph\",\"acmdoth\",\"acmasi\",\"acmdcode\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (527, 17, 64, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (528, 17, 65, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (529, 17, 66, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (530, 17, 212, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (531, 17, 213, 1, 1630996456, '2021-09-07 15:44:06', '[\"editVenue\",\"addVenue\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (532, 17, 214, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (533, 17, 215, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (534, 17, 216, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (535, 17, 217, 1, 1630996457, '2021-09-07 15:44:06', '[\"venueorderview\",\"venuechangestate\",\"venueordercreate\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (536, 17, 218, 1, 1630996457, '2021-09-07 15:44:06', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (537, 17, 219, 1, 1630996457, '2021-09-07 15:44:06', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (538, 17, 220, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveImport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (539, 17, 221, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveGroupAdd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (540, 17, 222, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveGroupInfoEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (541, 17, 241, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (542, 17, 232, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (543, 17, 233, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveExport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (544, 17, 234, 1, 1630996457, '2021-09-07 15:44:06', '[\"exchangeExport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (545, 17, 235, 1, 1630996457, '2021-09-07 15:44:06', '[\"inoutExport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (546, 17, 29, 1, 1631000324, '2021-09-07 15:38:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (547, 12, 242, 1, 1631242790, '2021-09-10 10:59:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (548, 14, 242, 1, 1631242799, '2021-09-10 10:59:59', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (549, 18, 9, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (550, 18, 10, 1, 1631599063, '2021-09-14 13:58:31', '[\"macthedit\",\"macthadd\",\"matchdel\",\"matchshow\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (551, 18, 25, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (552, 18, 42, 1, 1631599063, '2021-09-14 13:58:31', '[\"mdodt\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (553, 18, 43, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (554, 18, 44, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (555, 18, 46, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (556, 18, 47, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (557, 18, 49, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (558, 18, 50, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (559, 18, 51, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (560, 18, 52, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (561, 18, 55, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (562, 18, 81, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (563, 18, 237, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//CREATE TABLE `swim_address_swimmers_info` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//  `stat_date` varchar(50) NOT NULL DEFAULT '' COMMENT '统计日期',
//  `swimmers_total_number` int(11) NOT NULL DEFAULT '0' COMMENT '泳客总人数',
//  `swimmers_healthy_number` int(11) NOT NULL DEFAULT '0' COMMENT '泳客健康承诺人数',
//  `swimmers_Insurance_number` int(11) NOT NULL DEFAULT '0' COMMENT '泳客保险人数',
//  `daily_passenger_flow` int(11) NOT NULL DEFAULT '0' COMMENT '每日客流',
//  `create_time` int(11) NOT NULL DEFAULT '0',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  UNIQUE KEY `idx_stat_date` (`stat_date`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='泳客信息统计表';
//ALTER TABLE `swim_central_platform`.`swim_address_coach`
//MODIFY COLUMN `introduction` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '个人简介' AFTER `email`,
//ADD COLUMN `practice_certificate_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '执业证书编号' AFTER `introduction`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//MODIFY COLUMN `swim_address_id` int(11) NOT NULL DEFAULT 0 AFTER `id`,
//ADD COLUMN `lifeguard_id` varchar(32) NOT NULL DEFAULT '' COMMENT '救生员/教练id' AFTER `swim_address_id`,
//ADD COLUMN `type` tinyint(4) UNSIGNED NOT NULL DEFAULT 1 COMMENT '人员类型（如：01-救生员、02-教练）\'' AFTER `coach_id`,
//MODIFY COLUMN `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名' AFTER `swim_address_id`,
//ADD COLUMN `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '头像 Url 地址' AFTER `name`,
//ADD COLUMN `birth` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '出生年月' AFTER `avatar`,
//ADD COLUMN `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱' AFTER `birth`,
//ADD COLUMN `introduction` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '个人简介' AFTER `email`,
//ADD COLUMN `practice_certificate_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '执业证书编号' AFTER `introduction`,
//MODIFY COLUMN `mobile` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机' AFTER `gender`,
//MODIFY COLUMN `id_card` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '身份证' AFTER `mobile`,
//MODIFY COLUMN `cert_level` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '证书级别 初级 中级 高级' AFTER `cert_type`,
//ADD COLUMN `last_access` bigint(20) NOT NULL DEFAULT 0 COMMENT '最后更新时间' AFTER `cert_level`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//MODIFY COLUMN `lifeguard_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '救生员天健id' AFTER `swim_address_id`,
//ADD COLUMN `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名' AFTER `lifeguard_id`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//ADD COLUMN `practice_certificate_url` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '执业证书图片地址' AFTER `practice_certificate_code`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//ADD COLUMN `certificate_effective_date` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '证书生效日期' AFTER `practice_certificate_code`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//ADD COLUMN `recent_training_date` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最近培训日期' AFTER `certificate_effective_date`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_check_info`
//ADD COLUMN `user_channel_id` int(11) NOT NULL DEFAULT 0 COMMENT '检察员绑定的user channel id' AFTER `mobile`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE,
//ADD INDEX `idx_user_channel_id`(`user_channel_id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_work_order_index`
//MODIFY COLUMN `venue_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '工单所属场馆名称' AFTER `info`,
//ADD COLUMN `principal_channel_id` int(11) NOT NULL DEFAULT 0 COMMENT '被委托处理人的channel id' AFTER `commit_type`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_work_order_index`
//ADD INDEX `principal_channel_id`(`principal_channel_id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//DROP COLUMN `practice_certificate_code`,
//DROP COLUMN `certificate_effective_date`,
//DROP COLUMN `recent_training_date`,
//DROP COLUMN `practice_certificate_url`,
//DROP COLUMN `cert_type`,
//DROP COLUMN `cert_level`,
//COMMENT = '场馆救生员表';
//CREATE TABLE `swim_address_lifeguard_certificate` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `lifeguard_id` int(11) NOT NULL DEFAULT '0' COMMENT '救生员id',
//  `practice_certificate_code` varchar(100) NOT NULL DEFAULT '' COMMENT '执业证书编号',
//  `certificate_effective_date` varchar(100) NOT NULL DEFAULT '' COMMENT '证书生效日期',
//  `recent_training_date` varchar(100) NOT NULL DEFAULT '' COMMENT '最近培训日期',
//  `practice_certificate_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '执业证书图片地址',
//  `cert_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '证件类型 1-救生员证；2-国职证书',
//  `cert_level` varchar(16) NOT NULL DEFAULT '' COMMENT '证书级别 初级 中级 高级',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
//  `create_time` int(11) DEFAULT NULL,
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_lifeguard_id` (`lifeguard_id`) USING BTREE,
//  KEY `idx_practice_certificate_code` (`practice_certificate_code`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆救生员证书表';
//ALTER TABLE `swim_central_platform`.`swim_work_order_index`
//ADD COLUMN `source_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '工单来源类型 1检查整改 2用户意见反馈' AFTER `type`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//ADD COLUMN `tianjian_pool_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '天健的场馆id' AFTER `swim_address_id`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_check_info`
//ADD COLUMN `age` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '年龄' AFTER `user_channel_id`,
//ADD COLUMN `img_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '图片' AFTER `age`,
//ADD COLUMN `gender` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '性别 1男 2女' AFTER `img_url`,
//ADD COLUMN `area_code` tinyint(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '区域code' AFTER `gender`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_check_info`
//ADD COLUMN `id_card` varchar(64) NOT NULL DEFAULT 0 COMMENT '身份证号码' AFTER `user_channel_id`,
//ADD COLUMN `certificates_code` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '检查证编号' AFTER `id_card`,
//ADD COLUMN `effective_date` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '有效期时间' AFTER `certificates_code`,
//ADD COLUMN `certificates_status` tinyint(4) UNSIGNED NOT NULL DEFAULT 1 COMMENT '证状态 1有效 2无效' AFTER `effective_date`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_check_info`
//ADD COLUMN `grant_date` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '发证日期' AFTER `id_card`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard_certificate`
//ADD COLUMN `three_personnel_id` int(11) NOT NULL DEFAULT 0 COMMENT '三类人员id' AFTER `lifeguard_id`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard_certificate`
//ADD INDEX `idx_three_personnel_id`(`three_personnel_id`) USING BTREE,
//COMMENT = '各类人员证书表';
//ALTER TABLE `swim_central_platform`.`swim_address_three_personnel`
//ADD COLUMN `address_id` int(11) NOT NULL DEFAULT 0 COMMENT '场馆id' AFTER `id`,
//ADD COLUMN `address_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '场馆名称' AFTER `address_id`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE,
//ADD INDEX `idx_address_id`(`address_id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_three_personnel`
//MODIFY COLUMN `id_card_image` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '身份证照片' AFTER `date_of_issuance_end`;
//ALTER TABLE `swim_central_platform`.`swim_bk_user`
//ADD COLUMN `area_code` int(11) NOT NULL DEFAULT 0 COMMENT '人员所属区域' AFTER `nickname`,
//ADD COLUMN `channel_id` int(11) NOT NULL DEFAULT 0 COMMENT '关联的channel_id' AFTER `area_code`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_check`
//ADD COLUMN `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '检查类型' AFTER `user_channel_id`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_address_check`
//ADD INDEX `idx_type`(`type`) USING BTREE;
//DROP TABLE swim_pool;
//CREATE TABLE `swim_pool` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '场馆id',
//  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '池子名称',
//  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '泳池类型',
//  `temperature` varchar(20) NOT NULL DEFAULT '' COMMENT '温度类型',
//  `long` varchar(10) NOT NULL DEFAULT '' COMMENT '泳池长',
//  `wide` varchar(10) NOT NULL DEFAULT '' COMMENT '泳池宽',
//  `max_water_depth` varchar(10) NOT NULL DEFAULT '' COMMENT '最大水深',
//  `area` varchar(10) NOT NULL DEFAULT '' COMMENT '面积',
//  `quantity` tinyint(4) NOT NULL DEFAULT '1' COMMENT '数量',
//  `weight` tinyint(4) NOT NULL DEFAULT '1' COMMENT '排序权重',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_status` (`status`) USING BTREE,
//  KEY `idx_type` (`type`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='赛事场馆泳池表';
//CREATE TABLE `swim_address_fitness_card_signin` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//  `signin_id` varchar(32) NOT NULL DEFAULT '' COMMENT '天健id',
//  `swim_pool_id` varchar(32) NOT NULL DEFAULT '' COMMENT '关联天健场馆id',
//  `address_name` varchar(200) NOT NULL DEFAULT '' COMMENT '游泳馆商户名 \n',
//  `district` varchar(50) NOT NULL DEFAULT '' COMMENT '场馆所属区',
//  `last_access` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
//  `create_time` int(11) NOT NULL DEFAULT '0',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_signin_id` (`signin_id`) USING BTREE,
//  KEY `idx_district` (`district`) USING BTREE,
//  KEY `idx_swim_pool_id` (`swim_pool_id`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='泳客健康承诺入场记录表';
//ALTER TABLE `swim_central_platform`.`swim_address`
//ADD COLUMN `issuing_authority` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '发证机构' AFTER `high_risk_status`,
//ADD COLUMN `nature_business` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '经营范围' AFTER `Issuing_authority`,
//ADD COLUMN `address_person` tinyint(4) UNSIGNED NOT NULL DEFAULT 1 COMMENT '场馆社会体育指导员和救助人员数量' AFTER `nature_business`,
//DROP PRIMARY KEY,
//ADD PRIMARY KEY (`id`) USING BTREE;
//ALTER TABLE `swim_central_platform`.`swim_pool`
//ADD INDEX `idx_sid`(`sid`) USING BTREE;
//CREATE TABLE `swim_address_facilities` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '场馆id',
//  `locke_room` tinyint(2) NOT NULL DEFAULT '0' COMMENT '更衣室 0无 1有',
//  `toilet` tinyint(2) NOT NULL DEFAULT '0' COMMENT '公共卫生间 0无 1有',
//  `clinic` tinyint(2) NOT NULL DEFAULT '0' COMMENT '医务室 0无 1有',
//  `shower_room` tinyint(2) NOT NULL DEFAULT '0' COMMENT '淋浴房 0无 1有',
//  `circulating_equipment` tinyint(2) NOT NULL DEFAULT '0' COMMENT '池水循环设备 0无 1有',
//  `ventilation_facilities` tinyint(2) NOT NULL DEFAULT '0' COMMENT '通风设施 0无 1有',
//  `foot_soaking_tank` tinyint(2) NOT NULL DEFAULT '0' COMMENT '浸脚池 0无 1有',
//  `disinfection_facilities` tinyint(2) NOT NULL DEFAULT '0' COMMENT '公共用品消毒设施 0无 1有',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_ sid` (`sid`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='赛事场馆设施表';
//CREATE TABLE `swim_address_licence` (
//  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//  `address_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联场馆id',
//  `imgurl` varchar(255) NOT NULL DEFAULT '' COMMENT '证照图片',
//  `remarks` varchar(128) NOT NULL DEFAULT '' COMMENT '备注',
//  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型 1-其他证照 2-场馆其他照片',
//  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
//  `create_time` int(11) NOT NULL DEFAULT '0',
//  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//  PRIMARY KEY (`id`) USING BTREE,
//  KEY `idx_address_id` (`address_id`) USING BTREE,
//  KEY `idx_type` (`type`) USING BTREE
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆其他证照及其他照片表';
//ALTER TABLE `swim_central_platform`.`swim_address_lifeguard`
//DROP INDEX `idx_address_idcard`,
//ADD UNIQUE INDEX `idx_address_id_card`(`swim_address_id`,`id_card`) USING BTREE;
//EOF;
//        $sql = <<<EOF

//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (1, 0, 'couponlist', 'Rouponlist', '页面权限', '/coupon/index', '', 0, 'couponlist', 'el-icon-s-ticket', 2, 1607417910, '2020-12-10 10:22:55', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (2, 0, '/role', 'Role', '权限管理', '#', '/role/directory', 0, 'role', 'el-icon-s-custom', 1, 1607567569, '2021-03-17 11:25:15', NULL, 10, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (3, 2, 'roleindex', 'Rolendex', '页面权限', '/role/index', '', 0, 'pagerole', 'el-icon-s-management', 1, 1607568265, '2020-12-10 10:44:25', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (4, 2, 'directory', 'Airectory', '路由设置', '/role/directory', '', 0, 'directory', 'el-icon-s-order', 1, 1607568308, '2021-05-20 17:13:22', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (5, 0, '/coupon', 'Coupon', '优惠券管理', '#', '/coupon/couponlist', 0, 'coupon', 'el-icon-s-management', 2, 1607568391, '2021-02-24 14:13:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (6, 5, 'couponlist', 'Rouponlist', '优惠券列表', '/coupon/index', '', 0, 'couponlist', 'el-icon-s-ticket', 2, 1607568460, '2021-02-24 14:13:42', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (7, 0, '/coupon', 'Coupon', '优惠券管理', '#', '/coupon/couponlist', 0, 'coupon', 'el-icon-s-management', 2, 1607576105, '2020-12-10 12:55:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (8, 2, 'bkuser', 'Bkuser', '后台用户管理', '/role/user', '', 0, 'bkuser', 'el-icon-user-solid', 1, 1607654300, '2020-12-11 10:38:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (9, 0, '/match', 'Match', '活动管理', '#', '/match/matchindex', 0, 'match', 'el-icon-s-flag', 1, 1607680776, '2021-04-08 19:44:28', NULL, 99, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (10, 9, 'matchlist', 'Matchlist', '活动列表', '/match/index', '', 0, 'matchlist', 'el-icon-s-data', 1, 1607680950, '2021-02-26 12:30:45', '新增活动:macthadd;编辑活动:macthedit;删除:matchdel;审核展示:matchshow;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (11, 0, '/member', 'Member', '会员管理', '#', '/member/memberlist', 0, 'member', 'el-icon-user-solid', 1, 1608022594, '2021-03-17 11:25:46', NULL, 80, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (12, 11, 'memberlist', 'Memberlist', '会员列表', '/member/index', '', 0, 'memberlist', 'el-icon-star-on', 1, 1608022777, '2020-12-15 17:00:08', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (13, 11, 'aipush', 'Aipush', 'AI推送', '/member/aipush', '', 0, 'aipush', 'el-icon-s-promotion', 2, 1608022937, '2021-02-24 14:40:48', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (14, 11, 'tag', 'Tag', '标签列表', '/member/tag', '', 0, 'taglist', 'el-icon-s-claim', 2, 1608087992, '2021-02-24 14:40:55', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (15, 0, '/market', 'Market', '营销管理', '#', '/market/adlist', 0, 'market', 'el-icon-s-marketing', 1, 1608119785, '2021-03-17 11:25:59', NULL, 70, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (16, 15, 'adlist', 'Adlist', '消息管理', '/market/ad', '', 0, 'adlist', 'el-icon-picture', 1, 1608119965, '2020-12-16 19:59:25', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (17, 15, 'sms', 'Sms', '短信管理', '/market/sms', '', 0, 'smslist', 'el-icon-message', 1, 1608268794, '2020-12-18 13:19:54', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (18, 0, '/shop', 'Shop', '商品管理', '#', '/shop/shoptype', 0, 'shop', 'el-icon-s-shop', 1, 1608286439, '2021-03-17 11:26:27', NULL, 60, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (19, 18, 'shoptype', 'Shoptype', '商品标签', '/shop/shoptype', '', 0, 'shoptype', 'el-icon-menu', 1, 1608286908, '2020-12-18 18:26:27', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (20, 18, 'goods', 'Goods', '商品列表', '/shop/shoplist', '', 0, 'shoplist', 'el-icon-s-goods', 1, 1608286983, '2020-12-18 18:25:38', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (21, 18, 'orderlist', 'Orderlist', '订单列表', '/shop/orderlist', '', 0, 'orderlist', 'el-icon-s-order', 1, 1608287061, '2020-12-18 18:13:59', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (22, 0, 'testing', 'testing', '场馆test', 'testing', '', 0, '场馆管理', 'el-icon-menu', 2, 1608602801, '2020-12-22 16:19:05', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (23, 22, 'testing', 'testing', '篮球场馆1', 'testing', '', 0, '篮球', 'el-icon-user-solid', 2, 1608602877, '2020-12-22 15:03:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (24, 15, '/draw', 'Draw', '抽奖管理', '/market/draw', '', 0, 'draw', 'el-icon-s-marketing', 1, 1609138800, '2020-12-28 15:00:23', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (25, 9, 'registration/:matchid', 'Registration', '报名列表', '/match/registration', '', 1, 'registration', 'el-icon-s-management', 1, 1609316092, '2020-12-30 16:14:52', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (26, 11, 'm-user-detail/:id', 'MUserDetail', '会员详情', '/member/userdetail', '', 1, 'userdetail', 'el-icon-user', 2, 1609316190, '2021-02-24 15:43:43', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (27, 11, 'tag-user/:id', 'TagUser', '标签用户', '/member/taguser', '', 1, 'taguser', 'el-icon-user', 2, 1609316282, '2021-02-24 15:43:46', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (28, 11, 'push-user/:id', 'PushUser', '收件人列表', '/member/pushuser', '', 1, 'pushuser', 'el-icon-user', 2, 1609316365, '2021-02-24 15:45:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (29, 2, 'infouser', 'InfoUser', '更新信息', '/role/info', '', 1, 'info', 'el-icon-user', 1, 1609316420, '2020-12-30 16:20:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (30, 5, 'cou-detail/:id', 'Coupondetail', '券编辑', '/coupon/detail', '', 1, 'coupondetail', 'el-icon-user', 2, 1609316475, '2021-02-24 15:43:53', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (31, 18, 'goods-detail/:id', 'Shopdetail', '商品详情', '/shop/detail', '', 1, 'goodsdetail', 'el-icon-user', 1, 1609316534, '2020-12-30 16:22:14', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (32, 15, 'sms-detail/:id', 'Smsdetail', '发送详情', '/market/detail', '', 1, 'smsdetail', 'el-icon-user', 1, 1609316599, '2020-12-30 16:23:19', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (33, 15, 'sms-add', 'Addsms', '创建短信', '/market/add', '', 1, 'addsms', 'el-icon-user', 1, 1609316671, '2020-12-30 16:24:31', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (34, 15, 'draw-subset/:acid', 'SubsetDraw', '项目配置', '/market/drawsubset', '', 1, 'subsetdraw', 'el-icon-user', 1, 1609316750, '2020-12-30 16:25:50', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (35, 15, 'draw-user/:acid', 'UserDraw', '报名用户', '/market/drawuser', '', 1, 'userdraw', 'el-icon-user', 1, 1609316793, '2021-02-25 16:31:11', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (38, 0, 'couponlist', 'Rouponlist', '页面权限', '/coupon/index', '', 1, 'couponlist', 'el-icon-s-ticket', 2, 1609317162, '2021-02-24 15:45:28', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (39, 2, 'buttonrole', 'Buttonrole', '按钮功能', '/role/button', '', 0, 'btnrole', 'el-icon-setting', 2, 1611905628, '2021-02-25 16:16:34', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (40, 0, '/news', 'News', '新闻管理', '#', '/news/index', 0, 'news', 'el-icon-s-platform', 1, 1614249213, '2021-03-17 11:26:37', NULL, 50, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (41, 40, 'newslist', 'Newslist', '新闻列表', '/news/index', '', 0, 'newslist', 'el-icon-s-claim', 1, 1614249522, '2021-03-01 13:10:18', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (42, 9, 'macthdetail/:matchid', 'Macthdetail', '活动详情', '/match/matchdetail', '', 1, 'matchdetail', 'el-icon-picture', 1, 1614306864, '2021-07-19 16:17:07', '报名:mdodt;选手:mdodb;照片流:mdph;更多功能:mdoth;签到:masi;邀请码:mdcode;增值服务:addservice;', 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (43, 9, 'audit', 'Audit', '活动列表（审核）', '/match/audit', '', 0, 'auditlist', 'el-icon-s-claim', 1, 1614307703, '2021-02-26 16:50:36', '新增审核:auditadd;编辑审核:auditedit;删除审核:auditdel;报名:auditdetail;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (44, 9, 'upload/:matchid/:type', 'Upload', '上传材料', '/match/upload', '', 1, '上传材料', 'el-icon-upload', 1, 1614324241, '2021-03-02 18:52:19', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (45, 40, 'newsdetail/:id?', 'Newsdetail', '新闻详情', '/news/detail', '', 1, 'newsdetail', 'el-icon-s-order', 1, 1614578375, '2021-03-01 15:00:45', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (46, 9, 'distribution', 'Distribution', '配送列表', '/match/distribution', '', 0, 'distribution', 'el-icon-truck', 1, 1614760034, '2021-03-03 16:36:41', '新增配送:disadd;编辑配送:disedit;删除配送:disdel;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (47, 9, 'result/:matchid', 'Result', '成绩管理', '/match/result/index', '', 1, 'result', 'el-icon-tickets', 1, 1615200607, '2021-03-08 18:51:52', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (48, 9, 'result-config/:matchid', 'ResultConfig', '成绩配置', '/match/result/config', '', 1, 'resultConfig', 'el-icon-setting', 2, 1615202675, '2021-03-16 17:27:43', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (49, 9, 'image/:matchid', 'Image', '照片管理', '/match/image', '', 1, 'image', 'el-icon-picture', 1, 1615449692, '2021-03-11 16:01:32', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (50, 9, 'func/:matchid', 'Func', '更多功能', '/match/func', '', 1, 'func', 'el-icon-camera-solid', 1, 1615539995, '2021-03-12 17:06:35', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (51, 9, 'result/cert/:groupid', 'Cert', '证书配置', '/match/result/component/cert', '', 1, 'Cert', 'el-icon-menu', 1, 1615886813, '2021-03-16 17:33:16', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (52, 9, 'matchindex', 'Matchindex', '赛事列表', '/match/match', '', 0, 'matchindex', 'el-icon-s-data', 1, 1616047171, '2021-09-07 15:41:29', '新增赛事:mat_add;', 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (53, 9, 'match', 'Match', '赛事列表', '/match/match', '', 0, 'matchindex', 'el-icon-s-data', 2, 1616047203, '2021-03-18 14:00:21', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (54, 0, 'dads', 'dsadas', 'sdas', 'dasdd', '', 0, 'dasdas', 'dasd', 2, 1616117619, '2021-03-19 09:33:44', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (55, 9, 'publishmatch/:matchid', 'Publishmatch', '赛事编辑', '/match/publishmatch', '', 1, 'matchedit', 'el-icon-edit-outline', 1, 1616119866, '2021-03-19 10:11:06', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (56, 0, '/training', 'Training', '培训管理', '#', '/training/traininglist', 0, 'training', 'el-icon-menu', 1, 1616485360, '2021-04-08 10:47:22', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (57, 56, 'traininglist', 'Traininglist', '培训列表', '/training/list', '', 0, 'traininglist', 'el-icon-menu', 1, 1616485478, '2021-04-08 10:49:48', '创建培训:TrainingCreate;删除培训:TrainingDel;编辑培训:TrainingEdit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (58, 56, 'trainingedit/:training_id?', 'Trainingedit', '创建培训', '/training/edit', '', 1, 'trainingedit', 'el-icon-message', 1, 1616485628, '2021-04-02 09:59:18', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (59, 56, 'trainingdetail/:training_id', 'Trainingdetail', '培训详情', '/training/detail', '', 1, 'trainingdetail', 'el-icon-message', 1, 1616492179, '2021-04-07 17:25:39', '培训开设:TrainingYue;报名订单:TrainingOpen;学员管理:TrainingCert;预约管理:TrainingOrder;培训证书:TrainingUser;培训报名:TrainingRegister;培训编辑:TrainingEdit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (60, 15, 'template', 'Template', '订阅模版', '/market/template', '', 0, 'template', 'el-icon-s-comment', 1, 1616648461, '2021-03-25 13:01:01', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (61, 0, '/activity', 'Activity', '活动管理', '#', '/activity/acindex', 0, 'activity', 'el-icon-s-opportunity', 1, 1616983639, '2021-03-29 13:30:41', NULL, 90, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (62, 61, 'acindex', 'Acindex', '活动列表', '/activity/index', '', 0, 'activityindex', 'el-icon-s-promotion', 1, 1616984348, '2021-03-29 10:19:08', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (63, 61, 'acdetail/:matchid', 'Acdetail', '活动详情', '/activity/acdetail', '', 1, 'activitydel', 'el-icon-s-order', 1, 1616994178, '2021-03-29 14:17:40', '报名:acmdodt;选手:acmdodb;照片流:acmdph;更多功能:acmdoth;签到:acmasi;邀请码:acmdcode;', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (64, 61, 'acedit/:matchid', 'Acedit', '活动编辑', '/activity/publishac', '', 1, 'activityedit', 'el-icon-s-order', 1, 1616994263, '2021-03-29 13:35:05', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (65, 61, 'acrtype/:matchid', 'Acrtype', '报名选手', '/activity/registertype', '', 1, 'registertype', 'el-icon-user-solid', 1, 1617000572, '2021-03-29 14:49:32', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (66, 61, 'acregistration/:matchid', 'Acregistration', '报名订单', '/activity/registration', '', 1, 'registration', 'el-icon-s-management', 1, 1617000733, '2021-03-29 14:52:13', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (67, 15, 'direction', 'Direction', '定向赛', '/market/direction', '', 0, 'direction', 'el-icon-s-promotion', 1, 1617010849, '2021-03-29 17:40:49', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (68, 15, 'diresubset/:activity_id', 'Diresubset', '任务列表', '/market/diresubset', '', 1, 'diresubset', 'el-icon-s-finance', 1, 1617011799, '2021-03-29 17:56:39', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (69, 56, 'trainingopen/:training_id', 'Trainingopen', '培训开设', '/training/trainopen', '', 1, 'trainingopen', 'el-icon-s-finance', 1, 1617075147, '2021-03-30 14:50:51', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (70, 15, 'direuser/:activity_id', 'Direuser', '报名列表', '/market/direuser', '', 1, 'direuser', 'el-icon-s-order', 1, 1617080552, '2021-03-30 13:02:33', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (71, 56, 'student/:training_id/:period_id?', 'Student', '学员列表', '/training/student', '', 1, 'student', 'el-icon-user-solid', 1, 1617168337, '2021-03-31 16:39:16', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (72, 56, 'perioduser/:training_id', 'Perioduser', '预约管理', '/training/perioduser', '', 1, 'perioduser', 'el-icon-s-order', 1, 1617180319, '2021-04-11 17:27:54', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (73, 56, 'trainorder/:training_id', 'Trainorder', '报名订单', '/training/order', '', 1, 'trainorder', 'el-icon-s-order', 1, 1617265661, '2021-04-01 16:38:15', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (74, 56, 'white/:training_id/:period_id', 'White', '学员白名单', '/training/white', '', 1, 'white', 'el-icon-user', 1, 1617679650, '2021-04-06 11:27:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (75, 56, 'traincert/:training_id', 'Traincert', '证书配置', '/training/cert', '', 1, 'cert', 'el-icon-picture-outline', 1, 1617688226, '2021-04-06 14:08:10', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (76, 56, 'trainingregister/:training_id', 'Trainingregister', '培训报名', '/training/trainingregister', '', 1, 'trainingregister', 'el-icon-s-management', 1, 1617759522, '2021-04-07 09:38:42', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (77, 56, 'perioduserlist', 'Perioduserlist', '学员列表', '/training/perioduserlist', '', 0, 'perioduserlist', 'el-icon-s-order', 1, 1618133289, '2021-04-11 17:30:04', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (78, 56, 'trainorderlist', 'Trainorderlist', '培训订单', '/training/trainorderlist', '', 0, 'trainorderlist', 'el-icon-s-order', 1, 1618212210, '2021-04-12 15:23:30', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (79, 56, 'banlist', 'banlist', '培训班次', '/training/banlist', '', 0, 'banlist', 'el-icon-s-order', 1, 1618215450, '2021-04-12 16:18:20', '导出:Export', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (80, 56, 'whiteuserimport/:training_id/:period_id', 'Whiteuserimport', '白名单导入', '/training/import', '', 1, '白名单导入', 'el-icon-s-order', 1, 1618280907, '2021-04-13 11:04:41', '导入:Import;提交:Submit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (81, 9, 'image-watermark', 'imageWatermark', '照片水印配置', '/match/imagewatermark', '', 1, '水印管理', 'el-icon-picture-outline', 1, 1620640789, '2021-05-10 18:00:11', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (82, 0, '/service', 'service', '服务管理', '#', '/service/ServiceIndex', 0, '服务管理', 'el-icon-s-cooperation', 1, 1620975588, '2021-06-25 13:32:28', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (83, 82, 'ServiceIndex', 'serviceIndex', '服务列表', '/service/index', '/service/index', 0, '服务列表', 'el-icon-s-management', 2, 1620975784, '2021-05-14 16:47:16', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (84, 0, '/record', 'Record', '申请记录', '#', '/record/ticketsrecord', 1, '申请记录', 'el-icon-s-order', 1, 1620976305, '2021-05-24 10:06:53', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (85, 82, 'ticketsrecord', 'Ticketsrecord', '订票记录', '/qyh/record/tickets', '', 1, '订票记录', 'el-icon-s-ticket', 1, 1620976382, '2021-07-22 13:40:11', NULL, 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (86, 82, 'roomsrecord', 'Roomsrecord', '订房记录', '/qyh/record/rooms', '', 1, '订房记录', 'el-icon-office-building', 1, 1620976674, '2021-06-04 17:58:50', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (87, 82, 'carsrecords', 'Carsrecords', '订车记录', '/qyh/record/cars', '', 1, '订车记录', 'el-icon-truck', 1, 1620976709, '2021-06-04 17:59:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (88, 84, 'allcars', 'allcars', '运营车辆', '/record/allcars', '/record/allcars', 1, '运营车辆', 'el-icon-truck', 2, 1620976772, '2021-05-14 15:41:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (89, 84, 'allcarstable', 'allcarstable', '车辆统计', '/record/allcarstable', '/record/allcarstable', 1, '车辆统计', 'el-icon-truck', 2, 1620976811, '2021-05-14 15:41:26', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (90, 84, 'distributionrooms', 'distributionrooms', '分配房间', '/record/distributionrooms', '/record/distributionrooms', 1, '分配房间', 'el-icon-truck', 2, 1620976847, '2021-05-14 15:43:40', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (91, 0, '/qyhmember', 'Member', '成员管理', '#', '/qyhmember/qyhmember', 0, '成员管理', 'el-icon-user', 1, 1620976909, '2021-05-20 15:03:37', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (92, 91, 'qyhmember', 'member', '成员列表', '/qyh/qyhmember/index', '', 0, '成员列表', 'el-icon-user', 1, 1620976945, '2021-05-20 14:42:38', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (93, 0, '/setting', 'Setting', '配置管理', '#', '/setting/Settingrooms', 0, '配置管理', 'el-icon-setting', 1, 1620977032, '2021-05-20 15:03:50', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (94, 93, 'Settingrooms', 'settingrooms', '客房管理', '/qyh/setting/rooms', '', 0, '客房管理', 'el-icon-office-building', 1, 1620977079, '2021-05-20 14:43:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (95, 93, 'Settingcars', 'settingcars', '车辆管理', '/qyh/setting/cars', '', 0, '车辆管理', 'el-icon-truck', 1, 1620977117, '2021-05-20 14:43:07', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (96, 0, '/apply', 'Apply', '我要申报', '#', '/apply/ticketsList', 1, '我要申报', 'el-icon-setting', 2, 1620977173, '2021-05-14 17:36:35', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (97, 82, 'ticketsList', 'TicketsList', '订票申报', '/service/ticketsList', '', 1, '订票申报', 'el-icon-office-building', 2, 1620977215, '2021-05-17 09:41:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (98, 82, 'carsList', 'CarsList', '订车申报', '/service/carsList', '', 1, '订车申报', 'el-icon-truck', 2, 1620977257, '2021-05-17 09:41:06', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (99, 82, 'roomsList', 'RoomsList', '订房申报', '/service/roomsList', '', 1, '订房申报', 'el-icon-truck', 2, 1620977289, '2021-05-17 09:41:02', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (100, 0, '/', 'index', 'shouye', '*', '', 0, 'shouye', 'dashboard', 2, 1620980395, '2021-05-14 16:34:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (101, 100, 'dashboard', 'Dashboard', 'dashboard', '/dashboard/index', '', 0, 'home', 'dashboard', 2, 1620980472, '2021-05-14 16:34:02', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (102, 0, '/', 'index', '首页', '#', '', 0, 'home', 'dashboard', 2, 1620980566, '2021-05-14 16:34:04', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (103, 102, 'dashboard', 'Dashboard', 'dashboard', '/dashboard/index', '', 0, 'home', 'dashboard', 2, 1620980665, '2021-05-14 16:34:06', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (104, 82, 'ServiceIndex', 'serviceIndex', '服务列表', '/service/index', '', 0, '服务列表', 'el-icon-s-management', 2, 1620982093, '2021-05-14 17:23:36', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (105, 82, 'Allcars', 'allcars', '运营车辆', '/qyh/record/allcars', '', 1, '运营车辆', 'el-icon-truck', 1, 1620982493, '2021-06-04 18:03:32', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (106, 82, 'Allcarstable', 'allcarstable', '车辆统计', '/qyh/record/allcarstable', '', 1, '车辆统计', 'el-icon-truck', 1, 1620982535, '2021-06-04 18:03:37', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (107, 82, 'Distributionrooms', 'distributionrooms', '分配房间', '/qyh/record/distributionrooms', '', 1, '分配房间', 'el-icon-truck', 1, 1620982573, '2021-06-04 18:03:41', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (108, 115, 'Applyservice', 'applyservice', '服务申报', '/qyh/service/applyser', '', 0, '服务申报', 'el-icon-s-claim', 2, 1620984201, '2021-06-21 10:34:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (109, 82, 'ServiceIndex', 'serviceIndex', '服务列表', '/qyh/service/index', '', 0, '服务列表', 'el-icon-s-management', 1, 1620984258, '2021-05-20 14:43:44', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (110, 115, 'ticketsList', 'TicketsList', '订票申报', '/qyh/service/ticketsList', '', 1, '订票申报', 'el-icon-s-management', 2, 1621215751, '2021-06-21 10:37:20', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (111, 115, 'carsList', 'CarsList', '订车申报', '/qyh/service/carsList', '', 1, '订车申报', 'el-icon-s-management', 2, 1621215809, '2021-06-21 11:21:49', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (112, 115, 'roomsList', 'RoomsList', '订房申报', '/qyh/service/roomsList', '', 1, '订房申报', 'el-icon-s-management', 2, 1621215862, '2021-06-21 11:22:53', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (113, 82, 'importfile', 'Importfile', '上传明细', '/qyh/service/importFile', '', 1, '上传明细', 'el-icon-s-management', 1, 1621233354, '2021-05-20 14:44:09', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (114, 82, 'AllroomTable', 'allroomtable', '住房汇总', '/qyh/record/allroomtable', '', 1, '住房汇总', 'el-icon-truck', 1, 1623377125, '2021-06-11 10:05:25', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (115, 0, '/service2', 'service2', '申报管理', '#', '/service2/Applyservice', 0, '申报管理', 'el-icon-s-cooperation', 1, 1624241490, '2021-06-25 13:30:18', NULL, 2, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (116, 115, 'Applyservice', 'applyservice', '服务申报', '/qyh/service/applyser', '', 0, '服务申报', 'el-icon-s-claim', 1, 1624242888, '2021-06-21 10:34:48', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (117, 115, 'ticketsList', 'TicketsList', '订票申报', '/qyh/service/ticketsList', '', 1, '订票申报', 'el-icon-s-management', 1, 1624243080, '2021-06-21 10:38:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (118, 115, 'carsList', 'CarsList', '订车申报', '/qyh/service/carsList', '', 1, '订车申报', 'el-icon-s-management', 1, 1624245751, '2021-06-21 11:22:31', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (119, 115, 'roomsList', 'RoomsList', '订房申报', '/qyh/service/roomsList', '', 1, '订房申报', 'el-icon-s-management', 1, 1624245809, '2021-06-21 11:23:29', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (120, 115, 'importfile2', 'Importfile2', '上传', '/qyh/service/importFile', '', 1, '上传', 'el-icon-s-management', 1, 1624246218, '2021-06-21 11:58:23', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (121, 119, 'carsList', 'CarsList', '订车申报', '/qyh/service/carsList', '', 1, '订车申报', 'el-icon-s-management', 1, 1624250731, '2021-07-16 13:33:41', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (122, 119, 'roomsList', 'RoomsList', '订房申报', '/qyh/service/roomsList', '', 1, '订房申报', 'el-icon-s-management', 1, 1624250772, '2021-07-16 13:33:44', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (123, 119, 'importfile2', 'Importfile2', '上传', '/qyh/service/importFile', '', 1, '上传', 'el-icon-s-management', 1, 1624250813, '2021-07-16 13:33:46', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (124, 119, 'Applyservice', 'applyservice', '服务申报', '/qyh/service/applyser', '', 0, '服务申报', 'el-icon-s-claim', 1, 1624250895, '2021-07-16 13:33:47', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (212, 0, '/venue', 'Venue', '场馆管理', '#', '/venue/list', 0, '场馆管理', 'el-icon-s-order', 1, 1623398654, '2021-06-29 14:38:26', NULL, 90, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (213, 212, 'venuelist', 'venuelist', '场馆列表', '/venue/list', '', 0, '场馆列表', 'el-icon-truck', 1, 1623398727, '2021-07-16 13:41:56', '新建场馆:addVenue;修改场馆:editVenue;删除场馆:delVenue', 100, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (214, 212, 'venueedit', 'Venueedit', '场馆编辑', '/venue/edit', '', 1, '场馆编辑', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:41:58', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (215, 212, 'venuenotice', 'venuenotice', '场馆通知', '/venue/notice/index', '', 0, '场馆通知', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:00', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (216, 212, 'venuenoticedetail', 'venuenoticedetail', '场馆通知详情', '/venue/notice/detail', '', 1, '场馆通知详情', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:02', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (217, 212, 'venueorder', 'Venueorder', '散客预约列表', '/venue/order/index', '', 0, '散客预约列表', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:04', '查看:venueorderview;修改状态:venuechangestate;取消预订:venueCancelOrder;创建:venueordercreate', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (218, 212, 'venuedrew', 'Venuedrew', '兑换管理', '/venue/drew/index', '', 0, '兑换管理', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:06', ';新建:venuedrewadd', 90, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (219, 212, 'venuedrewuser/:acid?', 'Venuedrewuser', '兑换记录', '/venue/drew/user', '', 1, '兑换记录', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:07', '新建:venuedrewadd', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (220, 212, '/venue/order/import', 'VenueOrderImport', '创建预订订单', '/venue/order/import', '', 1, '创建预订订单', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:09', '导入:reserveImport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (221, 212, '/venue/order/group', 'VenueOrderGroup', '团队预约记录', '/venue/order/group', '', 0, '团队预约记录', 'el-icon-truck', 1, 1623398787, '2021-08-06 16:23:10', '新建:reserveGroupAdd;取消:venueCancelOrder;', 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (222, 212, '/venue/order/groupinfo', 'VenueOrderGroupInfo', '团队详情', '/venue/order/groupinfo', '', 1, '团队详情', 'el-icon-truck', 1, 1623398787, '2021-07-16 13:42:13', '编辑:reserveGroupInfoEdit', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (232, 0, '/statistics', 'Statistics', '数据统计', '#', '/statistics/reserve', 0, '数据统计', 'el-icon-s-order', 1, 1623398654, '2021-07-09 14:46:51', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (233, 232, 'reserve', 'Statisticsreserve', '预约人数统计', '/statistics/reserve', '', 0, '预约人数统计', 'el-icon-truck', 1, 1623398787, '2021-07-16 14:35:32', '导出:reserveExport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (234, 232, 'exchange', 'Statisticsexchange', '兑换统计', '/statistics/exchange', '', 0, '兑换统计', 'el-icon-truck', 1, 1623398787, '2021-07-16 14:35:33', '导出:exchangeExport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (235, 232, 'inout', 'Statisticsinout', '出入统计', '/statistics/inout', '', 0, '出入统计', 'el-icon-truck', 1, 1623398787, '2021-07-16 14:48:45', '导出:inoutExport', 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (237, 9, 'addservice/:matchid', 'Addservice', '增值服务', '/match/service', '', 1, 'addser', 'el-icon-s-order', 1, 1626683560, '2021-07-19 16:32:40', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (238, 40, 'newsaudit', 'Newsaudit', '新闻审核', '/news/audit', '', 0, 'newsaudit', 'el-icon-s-release', 1, 1626833726, '2021-07-21 10:15:26', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (239, 40, 'newsreview', 'Newsreview', '审核新闻', '/news/review', '', 0, 'nreview', 'el-icon-s-release', 1, 1626833865, '2021-07-21 10:17:45', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (240, 40, 'renewsdetail/:id?', 'ReNewsdetail', '新闻详情', '/news/redetail', '', 1, 'newsdetail', 'el-icon-s-order', 1, 1626933357, '2021-07-22 13:55:57', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (241, 212, '/venue/order/close', 'VenueClose', '封馆记录', '/venue/close', '', 0, '封馆记录', 'el-icon-truck', 1, 1623398787, '2021-07-27 14:04:59', NULL, 0, '');
//INSERT INTO `swim_central_platform`.`swim_auth_item` (`id`, `pid`, `path`, `name`, `label`, `component`, `redirect`, `hide`, `meta_title`, `meta_icon`, `status`, `create_time`, `update_time`, `actions`, `weight`, `jump_url`) VALUES (242, 82, 'ticketcount', 'ticketCount', '订票汇总', '/qyh/record/ticketcount', '', 1, '订票汇总', 'el-icon-document-copy', 1, 1631242603, '2021-09-10 10:56:43', NULL, 0, NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (1, 2, 1, 2, 1607420751, '2020-12-10 13:35:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (2, 2, 2, 1, 1607578556, '2020-12-10 13:35:56', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (3, 2, 3, 1, 1607652060, '2020-12-11 10:01:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (4, 2, 4, 1, 1607652060, '2020-12-11 10:01:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (5, 2, 5, 2, 1607652060, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (6, 2, 6, 2, 1607652060, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (7, 5, 2, 2, 1607653964, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (8, 5, 3, 2, 1607653964, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (9, 5, 4, 2, 1607653964, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (10, 5, 5, 2, 1607653965, '2021-02-26 18:12:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (11, 5, 6, 2, 1607653965, '2021-02-26 18:12:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (12, 4, 5, 2, 1607653971, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (13, 4, 6, 2, 1607653971, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (14, 2, 8, 1, 1607654318, '2020-12-11 10:38:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (15, 2, 9, 2, 1607680965, '2021-02-26 10:55:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (16, 2, 10, 2, 1607680966, '2021-04-08 19:44:52', '[\"macthadd\",\"macthedit\",\"matchdel\",\"matchshow\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (17, 5, 8, 2, 1607682564, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (18, 4, 9, 2, 1607682631, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (19, 4, 10, 1, 1607682631, '2021-03-04 10:55:48', '[\"macthadd\",\"macthedit\",\"matchdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (20, 2, 11, 1, 1608022974, '2021-02-25 20:09:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (21, 2, 12, 1, 1608022974, '2020-12-15 17:02:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (22, 2, 13, 2, 1608022974, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (23, 2, 14, 2, 1608120030, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (24, 2, 15, 1, 1608120030, '2020-12-16 20:00:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (25, 2, 16, 1, 1608120030, '2020-12-16 20:00:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (26, 4, 11, 1, 1608205573, '2020-12-17 19:46:13', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (27, 4, 12, 1, 1608205573, '2020-12-17 19:46:13', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (28, 4, 13, 2, 1608205573, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (29, 4, 14, 2, 1608205573, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (30, 4, 15, 2, 1608205573, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (31, 4, 16, 2, 1608205574, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (32, 2, 17, 1, 1608287154, '2020-12-18 18:25:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (33, 2, 18, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (34, 2, 19, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (35, 2, 20, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (36, 2, 21, 2, 1608287154, '2020-12-21 13:47:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (37, 2, 18, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (38, 2, 19, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (39, 2, 20, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (40, 2, 21, 1, 1608529644, '2020-12-21 13:47:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (41, 6, 5, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (42, 6, 6, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (43, 6, 9, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (44, 6, 10, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (45, 6, 11, 2, 1608540988, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (46, 6, 12, 2, 1608540989, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (47, 6, 13, 2, 1608540989, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (48, 6, 14, 2, 1608540989, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (49, 2, 22, 2, 1608602823, '2021-02-25 18:42:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (50, 6, 16, 2, 1608618997, '2020-12-22 14:45:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (51, 6, 22, 2, 1608620600, '2020-12-22 15:03:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (52, 4, 17, 2, 1608807199, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (53, 4, 18, 2, 1608807199, '2020-12-28 19:35:04', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (54, 4, 19, 1, 1608807199, '2020-12-24 18:53:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (55, 4, 20, 2, 1608807199, '2020-12-28 19:35:04', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (56, 4, 21, 1, 1608807199, '2020-12-24 18:53:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (57, 4, 24, 2, 1609155304, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (58, 4, 18, 1, 1609155316, '2020-12-28 19:35:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (59, 4, 20, 1, 1609155316, '2020-12-28 19:35:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (60, 7, 11, 2, 1609232167, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (61, 7, 12, 2, 1609232167, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (62, 7, 13, 2, 1609232167, '2021-02-26 10:52:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (63, 7, 14, 2, 1609232167, '2021-02-26 10:52:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (64, 8, 12, 2, 1611117191, '2021-02-26 17:57:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (65, 2, 29, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (66, 2, 25, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (67, 2, 24, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (68, 2, 32, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (69, 2, 33, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (70, 2, 34, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (71, 2, 35, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (72, 2, 31, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (73, 2, 40, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (74, 2, 41, 1, 1614249758, '2021-02-25 18:42:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (75, 7, 25, 2, 1614307937, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (76, 7, 42, 2, 1614307937, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (77, 7, 43, 2, 1614307937, '2021-03-23 17:14:33', '[\"auditadd\",\"auditedit\",\"auditdel\",\"auditdetail\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (78, 7, 15, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (79, 7, 16, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (80, 7, 17, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (81, 7, 24, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (82, 7, 32, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (83, 7, 33, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (84, 7, 34, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (85, 7, 35, 2, 1614307937, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (86, 7, 18, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (87, 7, 19, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (88, 7, 20, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (89, 7, 21, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (90, 7, 31, 2, 1614307938, '2021-02-26 14:52:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (91, 7, 40, 2, 1614307938, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (92, 7, 41, 2, 1614307938, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (93, 2, 42, 1, 1614308106, '2021-07-19 16:18:28', '[\"mdodt\",\"mdodb\",\"mdph\",\"mdoth\",\"masi\",\"mdcode\",\"addservice\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (94, 2, 9, 2, 1614308461, '2021-02-26 11:06:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (95, 2, 43, 2, 1614308461, '2021-02-26 11:06:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (96, 2, 9, 2, 1614309981, '2021-02-26 11:34:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (97, 2, 43, 2, 1614309981, '2021-02-26 11:34:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (98, 2, 9, 2, 1614314751, '2021-02-26 13:34:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (99, 2, 43, 2, 1614314751, '2021-02-26 13:34:52', '[\"auditadd\",\"auditedit\",\"auditdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (100, 2, 9, 2, 1614318498, '2021-02-26 14:32:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (101, 2, 43, 2, 1614318498, '2021-02-26 14:32:29', '[\"auditadd\",\"auditedit\",\"auditdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (102, 2, 9, 2, 1614323466, '2021-02-26 16:51:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (103, 2, 43, 2, 1614323466, '2021-02-26 16:51:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (104, 2, 44, 2, 1614325263, '2021-02-26 16:51:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (105, 7, 44, 2, 1614329497, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (106, 8, 9, 2, 1614333440, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (107, 8, 10, 2, 1614333440, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (108, 8, 25, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (109, 8, 42, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (110, 8, 43, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (111, 8, 44, 2, 1614333441, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (112, 8, 40, 1, 1614333441, '2021-02-26 17:57:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (113, 8, 41, 1, 1614333441, '2021-02-26 17:57:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (114, 5, 29, 2, 1614334350, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (115, 8, 2, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (116, 8, 3, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (117, 8, 4, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (118, 8, 8, 1, 1614334600, '2021-02-26 18:16:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (119, 8, 29, 1, 1614334601, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (120, 8, 11, 1, 1614334601, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (121, 8, 12, 1, 1614334601, '2021-02-26 18:16:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (122, 5, 9, 2, 1614334622, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (123, 5, 10, 2, 1614334622, '2021-03-23 13:29:52', '[\"macthadd\",\"macthedit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (124, 5, 25, 1, 1614334622, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (125, 5, 42, 1, 1614334622, '2021-03-05 10:40:55', '[\"mdodt\",\"mdodb\",\"mdph\",\"mdoth\",\"masi\",\"mdcode\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (126, 5, 43, 2, 1614334622, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (127, 5, 44, 2, 1614334622, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (128, 5, 40, 1, 1614334622, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (129, 5, 41, 1, 1614334622, '2021-02-26 18:17:02', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (130, 2, 43, 2, 1614338498, '2021-02-26 19:23:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (131, 2, 45, 1, 1614581421, '2021-03-01 14:50:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (132, 2, 46, 2, 1614760460, '2021-03-05 10:46:29', '[\"disadd\",\"disedit\",\"disdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (133, 5, 46, 2, 1614760481, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (134, 5, 45, 1, 1614760481, '2021-03-03 16:34:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (135, 5, 8, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (136, 5, 29, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (137, 5, 11, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (138, 5, 12, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (139, 5, 15, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (140, 5, 16, 1, 1614826510, '2021-03-04 10:55:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (141, 5, 17, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (142, 5, 24, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (143, 5, 32, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (144, 5, 33, 1, 1614826510, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (145, 5, 34, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (146, 5, 35, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (147, 5, 18, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (148, 5, 19, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (149, 5, 20, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (150, 5, 21, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (151, 5, 31, 1, 1614826511, '2021-03-04 10:55:11', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (152, 4, 8, 1, 1614826535, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (153, 4, 29, 1, 1614826535, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (154, 4, 25, 1, 1614826535, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (155, 4, 42, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (156, 4, 43, 2, 1614826536, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (157, 4, 44, 2, 1614826536, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (158, 4, 46, 2, 1614826536, '2021-03-04 10:56:00', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (159, 4, 32, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (160, 4, 33, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (161, 4, 34, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (162, 4, 35, 2, 1614826536, '2021-03-04 10:56:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (163, 4, 31, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (164, 4, 40, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (165, 4, 41, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (166, 4, 45, 1, 1614826536, '2021-03-04 10:55:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (167, 2, 47, 1, 1615200796, '2021-03-08 18:53:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (168, 2, 48, 2, 1615202716, '2021-03-16 17:29:25', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (169, 2, 49, 1, 1615456236, '2021-03-11 17:50:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (170, 2, 50, 1, 1615540012, '2021-03-12 17:06:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (171, 2, 51, 1, 1615886965, '2021-03-16 17:29:25', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (172, 2, 52, 2, 1616047490, '2021-04-08 19:45:09', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (173, 2, 55, 1, 1616119948, '2021-03-19 10:12:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (174, 5, 3, 1, 1616477369, '2021-03-23 13:29:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (175, 5, 47, 1, 1616477391, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (176, 5, 49, 1, 1616477391, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (177, 5, 50, 1, 1616477391, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (178, 5, 51, 1, 1616477392, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (179, 5, 52, 1, 1616477392, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (180, 5, 55, 1, 1616477392, '2021-03-23 13:29:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (181, 2, 56, 1, 1616486131, '2021-03-23 15:55:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (182, 2, 57, 1, 1616486131, '2021-04-08 10:50:26', '[\"TrainingCreate\",\"TrainingDel\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (183, 2, 58, 1, 1616486131, '2021-03-23 15:55:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (184, 9, 29, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (185, 9, 16, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (186, 9, 24, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (187, 9, 32, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (188, 9, 34, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (189, 9, 35, 1, 1616488286, '2021-03-23 16:31:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (190, 9, 18, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (191, 9, 19, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (192, 9, 20, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (193, 9, 21, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (194, 9, 31, 2, 1616488286, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (195, 9, 9, 1, 1616488286, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (196, 9, 25, 1, 1616488286, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (197, 9, 42, 1, 1616488286, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (198, 9, 47, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (199, 9, 49, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (200, 9, 50, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (201, 9, 51, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (202, 9, 52, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (203, 9, 55, 1, 1616488287, '2021-03-23 16:31:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (204, 9, 40, 2, 1616488287, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (205, 9, 41, 2, 1616488287, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (206, 9, 45, 2, 1616488287, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (207, 9, 10, 1, 1616490862, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (208, 9, 43, 1, 1616490862, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (209, 9, 44, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (210, 9, 46, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (211, 9, 11, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (212, 9, 12, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (213, 9, 15, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (214, 9, 17, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (215, 9, 33, 1, 1616490863, '2021-03-23 17:14:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (216, 7, 56, 1, 1616490873, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (217, 7, 57, 1, 1616490873, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (218, 7, 58, 1, 1616490873, '2021-03-23 17:14:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (219, 7, 40, 1, 1616490881, '2021-03-23 17:14:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (220, 7, 41, 1, 1616490881, '2021-03-23 17:14:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (221, 7, 45, 1, 1616490881, '2021-03-23 17:14:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (222, 2, 59, 1, 1616492266, '2021-04-07 17:25:52', '[\"TrainingYue\",\"TrainingOrder\",\"TrainingOpen\",\"TrainingCert\",\"TrainingUser\",\"TrainingRegister\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (223, 2, 60, 1, 1616648475, '2021-03-25 13:01:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (224, 2, 61, 1, 1616983953, '2021-03-29 10:12:34', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (225, 2, 62, 1, 1616984384, '2021-03-29 10:19:45', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (226, 2, 63, 1, 1616994271, '2021-04-29 20:29:36', '[\"acmdodt\",\"acmdodb\",\"acmdph\",\"acmdoth\",\"acmasi\",\"acmdcode\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (227, 2, 64, 1, 1616994271, '2021-03-29 13:04:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (228, 2, 65, 1, 1617000947, '2021-03-29 14:55:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (229, 2, 66, 1, 1617000947, '2021-03-29 14:55:47', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (230, 2, 67, 1, 1617010896, '2021-03-29 17:41:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (231, 2, 68, 1, 1617012147, '2021-03-29 18:02:27', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (232, 2, 70, 1, 1617081156, '2021-03-30 13:12:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (233, 2, 69, 1, 1617081156, '2021-03-30 13:12:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (234, 2, 71, 1, 1617168390, '2021-03-31 13:26:30', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (235, 2, 72, 1, 1617180346, '2021-03-31 16:45:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (236, 2, 73, 1, 1617265704, '2021-04-01 16:28:24', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (237, 2, 74, 1, 1617679663, '2021-04-06 11:27:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (238, 2, 75, 1, 1617688231, '2021-04-06 13:50:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (239, 2, 76, 1, 1617759641, '2021-04-07 09:40:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (240, 2, 46, 1, 1617776217, '2021-04-07 14:17:17', '[\"disadd\",\"disedit\",\"disdel\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (241, 2, 10, 1, 1617882308, '2021-08-19 14:43:13', '[\"macthedit\",\"matchdel\",\"matchshow\",\"macthadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (242, 2, 77, 1, 1618133324, '2021-04-11 17:28:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (243, 2, 78, 1, 1618212278, '2021-04-12 15:24:38', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (244, 2, 79, 1, 1618215474, '2021-04-12 16:17:54', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (245, 2, 80, 1, 1618280915, '2021-04-13 10:28:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (246, 11, 56, 1, 1618454022, '2021-04-15 10:33:42', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (247, 11, 57, 1, 1618454023, '2021-04-15 10:33:59', '[\"TrainingCreate\",\"TrainingDel\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (248, 11, 58, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (249, 11, 59, 1, 1618454023, '2021-04-15 10:33:59', '[\"TrainingYue\",\"TrainingOpen\",\"TrainingCert\",\"TrainingOrder\",\"TrainingUser\",\"TrainingRegister\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (250, 11, 69, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (251, 11, 71, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (252, 11, 72, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (253, 11, 73, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (254, 11, 74, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (255, 11, 75, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (256, 11, 76, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (257, 11, 77, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (258, 11, 78, 1, 1618454023, '2021-04-15 10:33:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (259, 11, 79, 1, 1618454023, '2021-04-15 10:33:59', '[\"Export\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (260, 11, 80, 1, 1618454023, '2021-04-15 10:33:59', '[\"Import\",\"Submit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (261, 10, 56, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (262, 10, 57, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (263, 10, 58, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (264, 10, 59, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (265, 10, 69, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (266, 10, 71, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (267, 10, 72, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (268, 10, 73, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (269, 10, 74, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (270, 10, 75, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (271, 10, 76, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (272, 10, 77, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (273, 10, 78, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (274, 10, 79, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (275, 10, 80, 1, 1618457629, '2021-04-15 11:33:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (276, 2, 9, 1, 1619699349, '2021-04-29 20:29:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (277, 2, 43, 1, 1619699349, '2021-04-29 20:29:36', '[\"auditadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (278, 2, 44, 1, 1619699349, '2021-04-29 20:29:10', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (279, 2, 52, 1, 1619699349, '2021-09-07 15:41:43', '[\"mat_add\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (280, 2, 81, 1, 1620640840, '2021-05-10 18:00:40', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (281, 13, 2, 1, 1620975988, '2021-05-14 15:06:28', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (282, 13, 3, 1, 1620975988, '2021-05-14 15:06:28', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (283, 13, 4, 1, 1620975988, '2021-05-14 15:06:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (284, 13, 8, 1, 1620975988, '2021-05-14 15:06:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (285, 13, 29, 1, 1620975988, '2021-05-14 15:06:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (286, 13, 82, 1, 1620975999, '2021-05-14 15:06:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (287, 13, 83, 1, 1620975999, '2021-05-14 15:06:39', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (288, 12, 2, 2, 1620976008, '2021-05-14 15:31:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (289, 12, 3, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (290, 12, 4, 2, 1620976008, '2021-05-14 15:31:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (291, 12, 8, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (292, 12, 29, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (293, 12, 82, 1, 1620976008, '2021-05-14 15:06:48', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (294, 12, 83, 2, 1620976008, '2021-05-14 16:48:28', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (295, 12, 84, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (296, 12, 85, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (297, 12, 86, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (298, 12, 87, 2, 1620977511, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (299, 12, 88, 2, 1620977512, '2021-05-14 16:26:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (300, 12, 89, 2, 1620977512, '2021-05-14 16:26:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (301, 12, 90, 2, 1620977512, '2021-05-14 16:26:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (302, 12, 91, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (303, 12, 92, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (304, 12, 93, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (305, 12, 94, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (306, 12, 95, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (307, 12, 96, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (308, 12, 97, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (309, 12, 98, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (310, 12, 99, 2, 1620977512, '2021-05-14 16:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (311, 14, 8, 2, 1620977585, '2021-06-15 16:53:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (312, 14, 29, 2, 1620977585, '2021-06-15 16:53:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (313, 14, 82, 2, 1620977585, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (314, 14, 83, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (315, 14, 84, 2, 1620977585, '2021-06-04 18:04:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (316, 14, 85, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (317, 14, 86, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (318, 14, 87, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (319, 14, 88, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (320, 14, 89, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (321, 14, 90, 2, 1620977585, '2021-05-14 17:25:36', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (322, 14, 91, 2, 1620977585, '2021-05-31 09:44:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (323, 14, 92, 2, 1620977585, '2021-05-31 09:44:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (324, 14, 93, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (325, 14, 94, 1, 1620977585, '2021-05-14 15:33:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (326, 14, 95, 1, 1620977585, '2021-05-14 15:33:06', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (327, 14, 96, 2, 1620977585, '2021-05-14 17:37:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (328, 14, 97, 2, 1620977586, '2021-05-17 09:44:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (329, 14, 98, 2, 1620977586, '2021-05-17 09:44:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (330, 14, 99, 2, 1620977586, '2021-05-17 09:44:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (331, 15, 82, 2, 1620977612, '2021-05-14 17:28:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (332, 15, 83, 2, 1620977612, '2021-05-14 17:25:56', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (333, 15, 91, 1, 1620977612, '2021-05-14 15:33:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (334, 15, 92, 1, 1620977612, '2021-05-14 15:33:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (335, 15, 96, 2, 1620977612, '2021-05-14 17:37:59', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (336, 15, 97, 2, 1620977612, '2021-05-17 09:44:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (337, 15, 98, 2, 1620977612, '2021-05-17 09:44:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (338, 15, 99, 2, 1620977612, '2021-05-17 09:44:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (339, 12, 100, 2, 1620980761, '2021-05-14 16:41:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (340, 12, 101, 2, 1620980761, '2021-05-14 16:41:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (341, 12, 104, 2, 1620982108, '2021-05-14 17:24:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (342, 12, 84, 2, 1620982586, '2021-06-04 18:03:49', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (343, 12, 85, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (344, 12, 86, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (345, 12, 87, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (346, 12, 105, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (347, 12, 106, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (348, 12, 107, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (349, 12, 91, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (350, 12, 92, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (351, 12, 93, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (352, 12, 94, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (353, 12, 95, 1, 1620982586, '2021-05-14 16:56:26', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (354, 12, 96, 2, 1620982586, '2021-05-14 17:37:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (355, 12, 97, 2, 1620982586, '2021-05-17 09:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (356, 12, 98, 2, 1620982586, '2021-05-17 09:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (357, 12, 99, 2, 1620982586, '2021-05-17 09:44:32', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (358, 12, 108, 2, 1620984271, '2021-06-21 10:35:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (359, 12, 109, 1, 1620984271, '2021-05-14 17:24:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (360, 14, 108, 2, 1620984335, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (361, 14, 109, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (362, 14, 105, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (363, 14, 106, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (364, 14, 107, 1, 1620984335, '2021-05-14 17:25:35', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (365, 15, 108, 2, 1620984356, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (366, 15, 109, 2, 1620984356, '2021-05-14 17:28:01', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (367, 12, 110, 2, 1621215872, '2021-06-21 10:39:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (368, 12, 111, 2, 1621215872, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (369, 12, 112, 2, 1621215872, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (370, 14, 110, 2, 1621215886, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (371, 14, 111, 2, 1621215886, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (372, 14, 112, 2, 1621215886, '2021-05-31 09:45:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (373, 12, 2, 1, 1621228135, '2021-05-17 13:08:55', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (374, 12, 4, 1, 1621228135, '2021-05-17 13:08:55', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (375, 12, 113, 1, 1621233368, '2021-05-17 14:36:09', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (376, 14, 113, 1, 1621233375, '2021-05-17 14:36:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (377, 15, 113, 2, 1621233381, '2021-06-21 11:59:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (378, 15, 110, 2, 1622425734, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (379, 15, 111, 2, 1622425734, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (380, 15, 112, 2, 1622425734, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (381, 14, 82, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (382, 14, 108, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (383, 14, 110, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (384, 14, 111, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (385, 14, 112, 2, 1623310708, '2021-06-16 14:06:18', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (386, 12, 114, 1, 1623377264, '2021-06-11 10:07:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (387, 14, 114, 1, 1623747194, '2021-06-15 16:53:14', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (388, 15, 115, 1, 1624241833, '2021-06-21 10:17:13', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (389, 12, 115, 1, 1624242021, '2021-06-21 10:20:21', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (390, 12, 116, 1, 1624242905, '2021-06-21 10:35:05', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (391, 12, 117, 1, 1624243169, '2021-06-21 10:39:29', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (392, 12, 118, 1, 1624245837, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (393, 12, 119, 1, 1624245837, '2021-06-21 11:23:57', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (394, 15, 116, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (395, 15, 117, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (396, 15, 118, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (397, 15, 119, 1, 1624245847, '2021-06-21 11:24:07', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (398, 15, 120, 1, 1624247973, '2021-06-21 11:59:33', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (399, 12, 120, 1, 1624415031, '2021-06-23 10:23:51', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (400, 14, 29, 1, 1624425042, '2021-06-23 13:10:42', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (401, 14, 82, 1, 1624425042, '2021-06-23 13:10:42', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (402, 15, 29, 1, 1624425050, '2021-06-23 13:10:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (403, 15, 82, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (404, 15, 85, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (405, 15, 86, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (406, 15, 87, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (407, 15, 105, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (408, 15, 106, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (409, 15, 107, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (410, 15, 109, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (411, 15, 113, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (412, 15, 114, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (413, 15, 84, 2, 1624599390, '2021-06-25 13:38:31', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (414, 15, 93, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (415, 15, 94, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (416, 15, 95, 2, 1624599390, '2021-06-29 13:35:37', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (417, 2, 131, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (418, 2, 136, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (419, 2, 132, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (420, 2, 133, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (421, 2, 134, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (422, 2, 135, 2, 1626413485, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (423, 2, 140, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (424, 2, 141, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (425, 2, 142, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (426, 2, 143, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (427, 2, 144, 2, 1626413580, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (428, 2, 125, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (429, 2, 126, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (430, 2, 127, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (431, 2, 137, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (432, 2, 138, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (433, 2, 139, 2, 1626413803, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (434, 2, 212, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (435, 2, 213, 1, 1626414106, '2021-07-22 14:35:38', '[\"editVenue\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (436, 2, 214, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (437, 2, 215, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (438, 2, 216, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (439, 2, 217, 1, 1626414106, '2021-08-06 16:16:38', '[\"venueordercreate\",\"venueCancelOrder\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (440, 2, 218, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (441, 2, 219, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (442, 2, 220, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (443, 2, 221, 1, 1626414106, '2021-07-19 18:09:01', '[\"reserveGroupAdd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (444, 2, 222, 1, 1626414106, '2021-07-16 13:41:46', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (445, 16, 11, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (446, 16, 12, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (447, 16, 16, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (448, 16, 24, 1, 1626415819, '2021-07-16 14:10:19', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (449, 16, 212, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (450, 16, 213, 1, 1626415820, '2021-07-19 14:38:26', '[\"addVenue\",\"editVenue\",\"delVenue\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (451, 16, 214, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (452, 16, 215, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (453, 16, 216, 1, 1626415820, '2021-07-16 14:10:20', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (454, 16, 217, 1, 1626415820, '2021-07-19 12:53:58', '[\"venueorderview\",\"venuechangestate\",\"venueCancelOrder\",\"venueordercreate\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (455, 16, 218, 1, 1626415820, '2021-07-19 14:38:26', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (456, 16, 219, 1, 1626415820, '2021-07-19 14:38:26', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (457, 16, 220, 1, 1626415820, '2021-07-19 14:38:26', '[\"reserveImport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (458, 16, 221, 1, 1626415820, '2021-07-19 12:53:58', '[\"reserveGroupAdd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (459, 16, 222, 1, 1626415820, '2021-07-19 14:38:27', '[\"reserveGroupInfoEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (460, 2, 232, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (461, 2, 233, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (462, 2, 234, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (463, 2, 235, 1, 1626416141, '2021-07-16 14:15:41', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (464, 2, 237, 1, 1626683571, '2021-07-19 16:32:51', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (465, 2, 238, 1, 1626833992, '2021-07-21 10:19:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (466, 2, 239, 1, 1626833992, '2021-07-21 10:19:52', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (467, 2, 240, 1, 1626933623, '2021-07-22 14:00:23', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (468, 2, 241, 1, 1627365908, '2021-07-27 14:05:08', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (469, 17, 9, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (470, 17, 10, 1, 1630996455, '2021-09-07 15:39:06', '[\"macthadd\",\"macthedit\",\"matchdel\",\"matchshow\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (471, 17, 25, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (472, 17, 42, 1, 1630996455, '2021-09-07 15:39:06', '[\"mdodt\",\"mdodb\",\"mdph\",\"mdoth\",\"masi\",\"mdcode\",\"addservice\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (473, 17, 43, 1, 1630996455, '2021-09-07 15:44:05', '[\"auditadd\",\"auditedit\",\"auditdetail\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (474, 17, 44, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (475, 17, 46, 1, 1630996455, '2021-09-07 15:44:05', '[\"disedit\",\"disadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (476, 17, 47, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (477, 17, 49, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (478, 17, 50, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (479, 17, 51, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (480, 17, 52, 1, 1630996455, '2021-09-07 15:41:49', '[\"mat_add\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (481, 17, 55, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (482, 17, 81, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (483, 17, 237, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (484, 17, 11, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (485, 17, 12, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (486, 17, 15, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (487, 17, 16, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (488, 17, 17, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (489, 17, 24, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (490, 17, 32, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (491, 17, 33, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (492, 17, 34, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (493, 17, 35, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (494, 17, 60, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (495, 17, 67, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (496, 17, 68, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (497, 17, 70, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (498, 17, 18, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (499, 17, 19, 1, 1630996455, '2021-09-07 14:34:15', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (500, 17, 20, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (501, 17, 21, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (502, 17, 31, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (503, 17, 40, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (504, 17, 41, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (505, 17, 45, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (506, 17, 238, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (507, 17, 239, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (508, 17, 240, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (509, 17, 56, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (510, 17, 57, 1, 1630996456, '2021-09-07 15:44:05', '[\"TrainingCreate\",\"TrainingEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (511, 17, 58, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (512, 17, 59, 1, 1630996456, '2021-09-07 15:44:06', '[\"TrainingRegister\",\"TrainingYue\",\"TrainingEdit\",\"TrainingOpen\",\"TrainingCert\",\"TrainingOrder\",\"TrainingUser\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (513, 17, 69, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (514, 17, 71, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (515, 17, 72, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (516, 17, 73, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (517, 17, 74, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (518, 17, 75, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (519, 17, 76, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (520, 17, 77, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (521, 17, 78, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (522, 17, 79, 1, 1630996456, '2021-09-07 15:44:06', '[\"Export\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (523, 17, 80, 1, 1630996456, '2021-09-07 15:44:06', '[\"Submit\",\"Import\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (524, 17, 61, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (525, 17, 62, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (526, 17, 63, 1, 1630996456, '2021-09-07 15:44:06', '[\"acmdodt\",\"acmdodb\",\"acmdph\",\"acmdoth\",\"acmasi\",\"acmdcode\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (527, 17, 64, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (528, 17, 65, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (529, 17, 66, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (530, 17, 212, 1, 1630996456, '2021-09-07 14:34:16', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (531, 17, 213, 1, 1630996456, '2021-09-07 15:44:06', '[\"editVenue\",\"addVenue\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (532, 17, 214, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (533, 17, 215, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (534, 17, 216, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (535, 17, 217, 1, 1630996457, '2021-09-07 15:44:06', '[\"venueorderview\",\"venuechangestate\",\"venueordercreate\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (536, 17, 218, 1, 1630996457, '2021-09-07 15:44:06', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (537, 17, 219, 1, 1630996457, '2021-09-07 15:44:06', '[\"venuedrewadd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (538, 17, 220, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveImport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (539, 17, 221, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveGroupAdd\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (540, 17, 222, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveGroupInfoEdit\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (541, 17, 241, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (542, 17, 232, 1, 1630996457, '2021-09-07 14:34:17', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (543, 17, 233, 1, 1630996457, '2021-09-07 15:44:06', '[\"reserveExport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (544, 17, 234, 1, 1630996457, '2021-09-07 15:44:06', '[\"exchangeExport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (545, 17, 235, 1, 1630996457, '2021-09-07 15:44:06', '[\"inoutExport\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (546, 17, 29, 1, 1631000324, '2021-09-07 15:38:44', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (547, 12, 242, 1, 1631242790, '2021-09-10 10:59:50', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (548, 14, 242, 1, 1631242799, '2021-09-10 10:59:59', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (549, 18, 9, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (550, 18, 10, 1, 1631599063, '2021-09-14 13:58:31', '[\"macthedit\",\"macthadd\",\"matchdel\",\"matchshow\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (551, 18, 25, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (552, 18, 42, 1, 1631599063, '2021-09-14 13:58:31', '[\"mdodt\"]');
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (553, 18, 43, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (554, 18, 44, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (555, 18, 46, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (556, 18, 47, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (557, 18, 49, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (558, 18, 50, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (559, 18, 51, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (560, 18, 52, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (561, 18, 55, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (562, 18, 81, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_auth_role_item` (`id`, `role_id`, `auth_item_id`, `status`, `create_time`, `update_time`, `actions`) VALUES (563, 18, 237, 1, 1631599063, '2021-09-14 13:57:43', NULL);
//INSERT INTO `swim_central_platform`.`swim_bk_user` (`id`, `gid`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `nickname`, `area_code`, `channel_id`, `pid`, `status`, `create_time`, `update_time`, `allowance`, `allowance_updated_at`, `avatar`, `role`, `phone`, `email`, `unionid`, `mpinfo`, `wsaf_urid`, `hp_urid`, `asid`, `password_lock_time`, `password_lock_inventory`, `last_login_time`, `realname`, `created_at`, `updated_at`) VALUES (1, 3, 'admin', 'GefarLc4lO4ZxUScmHNBqV5QNHU6_DFB', '$2y$13$4gyC0mcjQmby7MPJQa7pduon4uEsKJrAOqMCy9HB1RCb9Yiq8Xt4O', 'YC9elHz59eA1T6K8LJV1WzgvBvvtlshE_1634724494', '每步管理员', 1, 1, NULL, 1, 1505378472, '2021-10-20 18:08:14', 0, 0, '2222', '[1,2]', '', NULL, NULL, NULL, '0', 0, 5, 0, 5, NULL, NULL, NULL, NULL);
//EOF
        $sql = <<<EOF
ALTER TABLE `swim_central_platform`.`swim_address_check` 
ADD COLUMN `check_num` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '检查编号' AFTER `type`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE;
ALTER TABLE `swim_central_platform`.`swim_work_order_index` 
ADD COLUMN `work_order_num` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '工单编号' AFTER `principal_channel_id`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE;
EOF;
        $this->execute($sql);
    }
//delete from swim_address;

//ALTER TABLE `swim_central_platform`.`swim_address`
//MODIFY COLUMN `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '游泳馆类型 Code' AFTER `address_id`,
//MODIFY COLUMN `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '场馆名称' AFTER `type`,
//MODIFY COLUMN `avatar` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '游泳馆头像照片' AFTER `name`,
//MODIFY COLUMN `license_url` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '许可证照片' AFTER `avatar`,
//MODIFY COLUMN `imgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '场馆图片' AFTER `license_url`,
//MODIFY COLUMN `province` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `imgurl`,
//MODIFY COLUMN `city` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `province`,
//MODIFY COLUMN `district` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `city`,
//MODIFY COLUMN `neighborhood_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '街道名称' AFTER `district`,
//MODIFY COLUMN `neighborhood_id` int(11) NOT NULL DEFAULT 0 COMMENT '街道id' AFTER `neighborhood_name`,
//MODIFY COLUMN `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '泳馆地址-详细详址' AFTER `neighborhood_id`,
//MODIFY COLUMN `travel_information` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '交通信息' AFTER `address`,
//MODIFY COLUMN `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '场所固定电话' AFTER `travel_information`,
//MODIFY COLUMN `trade_situation` tinyint(2) NOT NULL DEFAULT 0 COMMENT '营业情况（01-正常；02-休业；）' AFTER `phone`,
//MODIFY COLUMN `swim_service_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '提供服务信息' AFTER `trade_situation`,
//MODIFY COLUMN `longitude` float(10, 6) NOT NULL DEFAULT 0 COMMENT '赛事经度' AFTER `swim_service_type`,
//MODIFY COLUMN `latitude` float(10, 6) NOT NULL DEFAULT 0 COMMENT '赛事纬度' AFTER `longitude`,
//MODIFY COLUMN `lane` tinyint(2) NOT NULL AFTER `latitude`,
//MODIFY COLUMN `comment_num` int(11) NOT NULL DEFAULT 0 AFTER `lane`,
//MODIFY COLUMN `comment_sum_score` int(11) NOT NULL DEFAULT 0 AFTER `comment_num`,
//MODIFY COLUMN `publish` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否发布' AFTER `comment_sum_score`,
//MODIFY COLUMN `account` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '负责人账号' AFTER `publish`,
//MODIFY COLUMN `account_id` int(11) NOT NULL DEFAULT 0 COMMENT '负责人账号id' AFTER `account`,
//MODIFY COLUMN `water_acreage` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '池水面积（㎡）' AFTER `account_id`,
//MODIFY COLUMN `remark` varchar(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '场所开放时间：全年开放；夏季开放' AFTER `water_acreage`,
//MODIFY COLUMN `open_license` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '开放许可证编号' AFTER `remark`,
//MODIFY COLUMN `principal` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '负责人姓名' AFTER `open_license`,
//MODIFY COLUMN `open_object` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '场所开放性质：对内开放；对外开放；' AFTER `principal`,
//MODIFY COLUMN `last_access` int(20) NOT NULL DEFAULT 0 COMMENT '最后更新时间' AFTER `open_object`,
//MODIFY COLUMN `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1-有效；2-无效' AFTER `last_access`,
//MODIFY COLUMN `create_time` int(11) NOT NULL DEFAULT 0 AFTER `status`;
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210910_104222_modify_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210910_104222_modify_address cannot be reverted.\n";

        return false;
    }
    */
}
