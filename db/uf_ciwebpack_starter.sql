/*
 Navicat Premium Data Transfer

 Source Server         : [local] MySQL
 Source Server Type    : MySQL
 Source Server Version : 50628
 Source Host           : 127.0.0.1:3306
 Source Schema         : project_ciwebpack_starter

 Target Server Type    : MySQL
 Target Server Version : 50628
 File Encoding         : 65001

 Date: 08/03/2019 14:45:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for uf_files
-- ----------------------------
DROP TABLE IF EXISTS `uf_files`;
CREATE TABLE `uf_files`  (
  `id` char(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `folder_id` int(11) NULL DEFAULT 1,
  `user_id` int(11) NOT NULL DEFAULT 1,
  `type` enum('a','v','d','i','o') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mimetype` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `keywords` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `width` int(5) NULL DEFAULT NULL,
  `height` int(5) NULL DEFAULT NULL,
  `filesize` int(11) NOT NULL DEFAULT 0,
  `alt_attribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `folder_id`(`folder_id`) USING BTREE,
  CONSTRAINT `uf_files_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `uf_files_folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for uf_files_folders
-- ----------------------------
DROP TABLE IF EXISTS `uf_files_folders`;
CREATE TABLE `uf_files_folders`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NULL DEFAULT 0,
  `slug` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local',
  `remote_container` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_files_folders
-- ----------------------------
INSERT INTO `uf_files_folders` VALUES (1, 0, 'default', 'Default', 'local', '', '2018-01-30 20:11:55', '2019-01-15 09:53:05');

-- ----------------------------
-- Table structure for uf_modules
-- ----------------------------
DROP TABLE IF EXISTS `uf_modules`;
CREATE TABLE `uf_modules`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('A','D') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'A',
  `order` int(11) NOT NULL DEFAULT 1,
  `menu` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `menu_group` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 78 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_modules
-- ----------------------------
INSERT INTO `uf_modules` VALUES (64, 'dashboard', 'A', 1, 'dashboard', 'navigation');
INSERT INTO `uf_modules` VALUES (70, 'settings', 'A', 7, 'settings', 'navigation');
INSERT INTO `uf_modules` VALUES (71, 'users', 'A', 8, 'users', 'navigation');
INSERT INTO `uf_modules` VALUES (77, 'files', 'A', 1, 'files', 'navigation');

-- ----------------------------
-- Table structure for uf_settings
-- ----------------------------
DROP TABLE IF EXISTS `uf_settings`;
CREATE TABLE `uf_settings`  (
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `default` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `module` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` set('text','textarea','password','select','select-multiple','radio','checkbox') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `is_required` int(1) NOT NULL,
  `options` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `is_gui` int(1) NOT NULL,
  `order` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`slug`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_settings
-- ----------------------------
INSERT INTO `uf_settings` VALUES ('auth_email_activation', 'Email Activation', NULL, '1', NULL, 'auth', 'select', 0, '0=Disable|1=Enable', 1, NULL, '2018-09-22 11:04:50', '2018-09-22 11:04:53');
INSERT INTO `uf_settings` VALUES ('auth_extend_on_login', 'Extend on login', 'Extend the users cookies every time they auto-login', '0', NULL, 'auth', 'select', 1, '0=Disable|1=Enable', 1, NULL, '2018-09-24 17:17:46', '2018-09-24 17:17:50');
INSERT INTO `uf_settings` VALUES ('auth_identity_cookie', 'Cookie Identity', 'Cookie name of identity', 'identity_cookie', NULL, 'auth', 'text', 1, NULL, 1, NULL, '2018-09-23 17:00:29', '2018-09-23 17:00:29');
INSERT INTO `uf_settings` VALUES ('auth_login_identity', 'Login identity', NULL, 'username', NULL, 'auth', 'select', 0, 'username=username|email=email', 1, NULL, '2018-09-23 14:39:31', '2018-09-23 17:18:12');
INSERT INTO `uf_settings` VALUES ('auth_manual_activation', 'Manual Activation', NULL, '0', NULL, 'auth', 'select', 0, '0=Disable|1=Enable', 1, NULL, '2018-09-22 11:02:02', '2018-09-22 11:02:02');
INSERT INTO `uf_settings` VALUES ('auth_max_login_attempts', 'Maximal Login Attempts', NULL, '6', NULL, 'auth', 'text', 0, NULL, 1, NULL, '2018-09-23 14:50:02', '2018-09-23 14:50:02');
INSERT INTO `uf_settings` VALUES ('auth_max_password_length', 'Maximal length password', '0 is unlimited', '10', NULL, 'auth', 'text', 1, NULL, 1, NULL, '2018-10-17 19:46:45', '2018-10-17 19:50:40');
INSERT INTO `uf_settings` VALUES ('auth_min_password_length', 'Minimal length password', 'minimal 5', '5', NULL, 'auth', 'text', 1, NULL, 1, NULL, '2018-10-17 19:46:25', '2018-10-17 19:51:05');
INSERT INTO `uf_settings` VALUES ('auth_recheck_timer', 'Re check timer', NULL, '0', NULL, 'auth', 'text', 1, NULL, 1, NULL, '2018-09-24 16:45:22', '2018-09-24 16:46:00');
INSERT INTO `uf_settings` VALUES ('auth_remember_me_cookie', 'Cookie Remember me', 'Cookie name of remember me', 'remember_cookie', NULL, 'auth', 'text', 1, NULL, 1, NULL, '2018-09-23 16:58:12', '2018-09-23 16:58:12');
INSERT INTO `uf_settings` VALUES ('auth_remember_me_expire', 'Expire remember me', 'How long to remember the user (seconds). Set to zero for no expiration', '86500', NULL, 'auth', 'text', 1, NULL, 1, NULL, '2018-09-23 16:54:18', '2018-09-23 16:56:50');
INSERT INTO `uf_settings` VALUES ('auth_store_salt', 'Store Salt', NULL, '1', NULL, 'auth', 'select', 0, '0=Disable|1=Enable', 1, NULL, '2018-09-22 11:02:58', '2018-09-22 11:03:00');
INSERT INTO `uf_settings` VALUES ('auth_store_salt_length', 'Store Salt Length', NULL, '13', NULL, 'auth', 'text', 0, NULL, 1, NULL, '2018-09-22 11:03:55', '2018-09-23 20:57:48');
INSERT INTO `uf_settings` VALUES ('files_cache', 'Files Cache', 'When outputting an image via site.com/files what shall we set the cache expiration for?', '480', '1', 'files', 'select', 0, '0=no-cache|1=1-minute|60=1-hour|180=3-hour|480=8-hour|1440=1-day|43200=30-days', 1, NULL, '2018-10-30 13:59:37', '2018-10-30 13:59:37');
INSERT INTO `uf_settings` VALUES ('files_upload_limit', 'Filesize Limit', 'Maximum filesize to allow when uploading. Specify the size in MB. Example: 5', '5', NULL, 'files', 'text', 0, NULL, 1, NULL, '2018-10-28 21:47:41', '2018-10-28 21:47:41');
INSERT INTO `uf_settings` VALUES ('site_lang', 'Site Language', NULL, 'en', NULL, NULL, 'select', 1, 'en=English', 1, NULL, '2018-09-24 19:52:17', '2018-09-24 19:52:17');
INSERT INTO `uf_settings` VALUES ('site_name_abbr', 'Site Abbr Name', NULL, 'ciwebpack', 'uftify', NULL, 'text', 1, NULL, 1, NULL, '2018-09-24 00:31:37', '2019-02-23 11:47:30');
INSERT INTO `uf_settings` VALUES ('site_name_full', 'Site Full Name', NULL, 'Codeigniter webpack starter', NULL, NULL, 'text', 1, NULL, 1, NULL, '2018-09-23 23:55:14', '2019-01-15 09:52:21');

-- ----------------------------
-- Table structure for uf_user_groups
-- ----------------------------
DROP TABLE IF EXISTS `uf_user_groups`;
CREATE TABLE `uf_user_groups`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name_UNIQUE`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_user_groups
-- ----------------------------
INSERT INTO `uf_user_groups` VALUES (1, 'admin', 'Administrator', 0, 1, '2018-02-11 22:45:35', '2018-02-11 22:45:35', NULL);
INSERT INTO `uf_user_groups` VALUES (5, 'default', 'Default group', 1, 0, '2019-03-05 21:23:04', '2019-03-05 21:23:04', NULL);
INSERT INTO `uf_user_groups` VALUES (8, 'Group ke 2', 'Group ke 2', 0, 0, '2019-03-07 11:13:26', '2019-03-07 11:13:26', NULL);
INSERT INTO `uf_user_groups` VALUES (9, 'Group ke 3', 'Groupke 3', 0, 0, '2019-03-07 11:13:37', '2019-03-07 11:13:37', NULL);
INSERT INTO `uf_user_groups` VALUES (10, 'public user', 'pubcli user', 0, 0, '2019-03-07 11:13:56', '2019-03-07 11:13:56', NULL);

-- ----------------------------
-- Table structure for uf_user_groups_permissions
-- ----------------------------
DROP TABLE IF EXISTS `uf_user_groups_permissions`;
CREATE TABLE `uf_user_groups_permissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `roles` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  CONSTRAINT `uf_user_groups_permissions_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `uf_user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_user_groups_permissions
-- ----------------------------
INSERT INTO `uf_user_groups_permissions` VALUES (9, 5, 'settings', '[\"read\"]', '2019-03-06 20:36:22', 1, '2019-03-06 20:36:22', 1);
INSERT INTO `uf_user_groups_permissions` VALUES (10, 5, 'users', '{\"user\":[\"read\"],\"group\":[\"read\"]}', '2019-03-06 20:36:22', 1, '2019-03-06 20:36:22', 1);
INSERT INTO `uf_user_groups_permissions` VALUES (11, 5, 'dashboard', '[\"read\"]', '2019-03-06 20:36:22', 1, '2019-03-06 20:36:22', 1);

-- ----------------------------
-- Table structure for uf_user_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `uf_user_login_attempts`;
CREATE TABLE `uf_user_login_attempts`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `login` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for uf_user_profile
-- ----------------------------
DROP TABLE IF EXISTS `uf_user_profile`;
CREATE TABLE `uf_user_profile`  (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `photo_file` char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `updated_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`user_id`) USING BTREE,
  CONSTRAINT `uf_user_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `uf_user_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_user_profile
-- ----------------------------
INSERT INTO `uf_user_profile` VALUES (1, 'Administrator', '-', NULL, '2019-02-23 17:33:40');
INSERT INTO `uf_user_profile` VALUES (2, 'Suryadi ufhy', '08114602715', NULL, '2019-03-07 11:24:32');

-- ----------------------------
-- Table structure for uf_user_users
-- ----------------------------
DROP TABLE IF EXISTS `uf_user_users`;
CREATE TABLE `uf_user_users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `activation_code` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `forgotten_password_code` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `forgotten_password_time` int(11) NULL DEFAULT NULL,
  `remember_code` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `lang` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'en',
  `group_id` int(11) NOT NULL,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uf_user_users
-- ----------------------------
INSERT INTO `uf_user_users` VALUES (1, '1', 'administrator', '$2y$10$i5MLkTSL7x6rDeSVx4VGkuqpkr5aACNLcs.OSQRZhw.UDWvOdpsNa', '0HTL/tcYiJI11', 'cms_admin@localhost', '', NULL, NULL, 'K8Fp0gUI.705M', 1, 'en', 1, '2019-03-08 13:52:48', '2017-05-25 00:19:50', '2019-03-08 14:44:50', NULL);
INSERT INTO `uf_user_users` VALUES (2, '::1', 'suryadi', '$2y$10$eMlbyt.z0ChnNg2fC1qCmeTXVrv7MPe6IaVPxQQfbDNeSENhyLkeS', 'XhbIYMUtSl2l3', 'suryadi@localhost.test', NULL, NULL, NULL, NULL, 1, 'id', 5, NULL, '2019-03-07 11:24:32', '2019-03-07 11:24:32', NULL);

SET FOREIGN_KEY_CHECKS = 1;
