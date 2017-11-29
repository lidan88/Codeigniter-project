# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.9)
# Database: continuitypro_17
# Generation Time: 2014-08-13 13:49:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table bia
# ------------------------------------------------------------

CREATE TABLE `bia` (
  `bia_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `library_value_id` int(11) DEFAULT '0',
  `status` enum('In Progress','Pending Approval','Approved','Rejected') DEFAULT 'In Progress',
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  PRIMARY KEY (`bia_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table call_chain
# ------------------------------------------------------------

CREATE TABLE `call_chain` (
  `call_chain_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `description` text,
  `call_chain` text,
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  PRIMARY KEY (`call_chain_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table company
# ------------------------------------------------------------

CREATE TABLE `company` (
  `company_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `notes` text,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `web` varchar(250) DEFAULT NULL,
  `main_contact` varchar(250) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT '1',
  `added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table dropdown
# ------------------------------------------------------------

CREATE TABLE `dropdown` (
  `dropdown_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `is_active` enum('Yes','No') DEFAULT 'Yes',
  PRIMARY KEY (`dropdown_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table dropdown_item
# ------------------------------------------------------------

CREATE TABLE `dropdown_item` (
  `dropdown_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dropdown_id` int(11) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `is_active` enum('Yes','No') DEFAULT 'Yes',
  PRIMARY KEY (`dropdown_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table library
# ------------------------------------------------------------

CREATE TABLE `library` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `parent_id` int(11) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `is_visible` int(11) DEFAULT '1',
  `is_system` int(11) DEFAULT '0',
  `added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table library_items
# ------------------------------------------------------------

CREATE TABLE `library_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `library_id` int(11) DEFAULT NULL,
  `var_name` varchar(250) DEFAULT NULL,
  `var_type` varchar(250) DEFAULT NULL,
  `var_value` longtext,
  `var_value_type` enum('T','P','Q','L') DEFAULT NULL,
  `is_required` enum('Yes','No') DEFAULT 'No',
  `show_by_default` enum('Yes','No') DEFAULT 'Yes',
  `help` text,
  `item_order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `library_id` (`library_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table library_template
# ------------------------------------------------------------

CREATE TABLE `library_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `library_id` int(11) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `template` text,
  `sort_by` varchar(100) DEFAULT NULL,
  `order_by` varchar(100) DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table library_values
# ------------------------------------------------------------

CREATE TABLE `library_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `library_id` int(11) DEFAULT NULL,
  `unique_id` varchar(250) NOT NULL DEFAULT '',
  `value` text,
  `added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by_uid` int(11) DEFAULT '0',
  `modified_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_field` (`company_id`,`library_id`,`unique_id`),
  KEY `company_id` (`company_id`),
  KEY `library_id` (`library_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table modules
# ------------------------------------------------------------

CREATE TABLE `modules` (
  `module_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `group` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `uni-name` (`name`),
  KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table plan_items
# ------------------------------------------------------------

CREATE TABLE `plan_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) DEFAULT NULL,
  `plan_type` varchar(10) DEFAULT 'lib',
  `library_id` int(11) DEFAULT '0',
  `template_id` int(11) DEFAULT '0',
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `footer` text,
  `library_items` text,
  `order_by` int(11) DEFAULT '0',
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table plans
# ------------------------------------------------------------

CREATE TABLE `plans` (
  `plan_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `library_value_id` int(11) DEFAULT NULL,
  `status` enum('In Progress','Pending Approval','Approved','Rejected') DEFAULT 'In Progress',
  `notes` text,
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  PRIMARY KEY (`plan_id`),
  KEY `library_value_id` (`library_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table reports
# ------------------------------------------------------------

CREATE TABLE `reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `library_id` int(11) DEFAULT NULL,
  `module_id` varchar(20) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `header` varchar(250) DEFAULT NULL,
  `footer` varchar(250) DEFAULT NULL,
  `template` text,
  `sort_by` varchar(100) DEFAULT NULL,
  `order_by` varchar(100) DEFAULT NULL,
  `filter_by` text,
  `selected_records` text,
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table risk_assessment
# ------------------------------------------------------------

CREATE TABLE `risk_assessment` (
  `risk_assessment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `description` text,
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  `library_value_id` int(11) DEFAULT NULL,
  `status` enum('In Progress','Pending Approval','Approved') DEFAULT 'In Progress',
  PRIMARY KEY (`risk_assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table role
# ------------------------------------------------------------

CREATE TABLE `role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  KEY `company_id` (`company_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table role_permission
# ------------------------------------------------------------

CREATE TABLE `role_permission` (
  `role_id` int(11) unsigned NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `permissions` int(11) DEFAULT '0',
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tasks
# ------------------------------------------------------------

CREATE TABLE `tasks` (
  `task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `lv_id` int(11) DEFAULT '0',
  `threat_id` int(11) DEFAULT NULL,
  `risk_assessment_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT '0',
  `assigned_to` int(11) DEFAULT '0',
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `priority` int(11) DEFAULT '0',
  `completed` int(11) DEFAULT '0',
  `due_by` date DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table teams
# ------------------------------------------------------------

CREATE TABLE `teams` (
  `team_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `lv_id` int(11) DEFAULT '0',
  `member` varchar(250) DEFAULT NULL,
  `role` varchar(250) DEFAULT '',
  `responsibility` varchar(250) DEFAULT NULL,
  `priority` int(11) DEFAULT '0',
  `modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT '0',
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table threat_analysis
# ------------------------------------------------------------

CREATE TABLE `threat_analysis` (
  `threat_analysis_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `risk_assessment_id` int(11) DEFAULT '0',
  `threat_id` int(11) DEFAULT NULL,
  `likelihood` enum('LOW','MEDIUM','HIGH') DEFAULT 'LOW',
  `impact` enum('LOW','MEDIUM','HIGH') DEFAULT 'LOW',
  `weight` enum('LOW','MEDIUM','HIGH','CRITICAL') DEFAULT 'LOW',
  PRIMARY KEY (`threat_analysis_id`),
  KEY `risk_assessment_id` (`risk_assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table threats
# ------------------------------------------------------------

CREATE TABLE `threats` (
  `threat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `group` varchar(100) DEFAULT NULL,
  `added_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` int(11) DEFAULT '0',
  PRIMARY KEY (`threat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table timezones
# ------------------------------------------------------------

CREATE TABLE `timezones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `hour` double DEFAULT NULL,
  `timezone` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `timezones` WRITE;
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;

INSERT INTO `timezones` (`id`, `name`, `hour`, `timezone`)
VALUES
	(1,'Hawaii',-10,'-10:00'),
	(2,'Alaska',-9,'-09:00'),
	(3,'Pacific Time (US &amp; Canada)',-8,'-08:00'),
	(4,'Arizona',-7,'-07:00'),
	(5,'Mountain Time (US &amp; Canada)',-7,'-07:00'),
	(6,'Central Time (US &amp; Canada)',-6,'-06:00'),
	(7,'Eastern Time (US &amp; Canada)',-5,'-05:00'),
	(8,'Indiana (East)',-5,'-05:00'),
	(9,'International Date Line West',-11,'-11:00'),
	(10,'Midway Island',-11,'-11:00'),
	(11,'Samoa',-11,'-11:00'),
	(12,'Tijuana',-8,'-08:00'),
	(13,'Chihuahua',-7,'-07:00'),
	(14,'Mazatlan',-7,'-07:00'),
	(15,'Central America',-6,'-06:00'),
	(16,'Guadalajara',-6,'-06:00'),
	(17,'Mexico City',-6,'-06:00'),
	(18,'Monterrey',-6,'-06:00'),
	(19,'Saskatchewan',-6,'-06:00'),
	(20,'Bogota',-5,'-05:00'),
	(21,'Lima',-5,'-05:00'),
	(22,'Quito',-5,'-05:00'),
	(23,'Caracas',-4,'-04:30'),
	(24,'Atlantic Time (Canada)',-4,'-04:00'),
	(25,'La Paz',-4,'-04:00'),
	(26,'Santiago',-4,'-04:00'),
	(27,'Newfoundland',-3,'-03:30'),
	(28,'Brasilia',-3,'-03:00'),
	(29,'Buenos Aires',-3,'-03:00'),
	(30,'Georgetown',-3,'-03:00'),
	(31,'Greenland',-3,'-03:00'),
	(32,'Mid-Atlantic',-2,'-02:00'),
	(33,'Azores',-1,'-01:00'),
	(34,'Cape Verde Is.',-1,'-01:00'),
	(35,'Casablanca',0,'+00:00'),
	(36,'Dublin',0,'+00:00'),
	(37,'Edinburgh',0,'+00:00'),
	(38,'Lisbon',0,'+00:00'),
	(39,'London',0,'+00:00'),
	(40,'Monrovia',0,'+00:00'),
	(41,'UTC',0,'+00:00'),
	(42,'Amsterdam',1,'+01:00'),
	(43,'Belgrade',1,'+01:00'),
	(44,'Berlin',1,'+01:00'),
	(45,'Bern',1,'+01:00'),
	(46,'Bratislava',1,'+01:00'),
	(47,'Brussels',1,'+01:00'),
	(48,'Budapest',1,'+01:00'),
	(49,'Copenhagen',1,'+01:00'),
	(50,'Ljubljana',1,'+01:00'),
	(51,'Madrid',1,'+01:00'),
	(52,'Paris',1,'+01:00'),
	(53,'Prague',1,'+01:00'),
	(54,'Rome',1,'+01:00'),
	(55,'Sarajevo',1,'+01:00'),
	(56,'Skopje',1,'+01:00'),
	(57,'Stockholm',1,'+01:00'),
	(58,'Vienna',1,'+01:00'),
	(59,'Warsaw',1,'+01:00'),
	(60,'West Central Africa',1,'+01:00'),
	(61,'Zagreb',1,'+01:00'),
	(62,'Athens',2,'+02:00'),
	(63,'Bucharest',2,'+02:00'),
	(64,'Cairo',2,'+02:00'),
	(65,'Harare',2,'+02:00'),
	(66,'Helsinki',2,'+02:00'),
	(67,'Istanbul',2,'+02:00'),
	(68,'Jerusalem',2,'+02:00'),
	(69,'Kyev',2,'+02:00'),
	(70,'Minsk',2,'+02:00'),
	(71,'Pretoria',2,'+02:00'),
	(72,'Riga',2,'+02:00'),
	(73,'Sofia',2,'+02:00'),
	(74,'Tallinn',2,'+02:00'),
	(75,'Vilnius',2,'+02:00'),
	(76,'Baghdad',3,'+03:00'),
	(77,'Kuwait',3,'+03:00'),
	(78,'Moscow',3,'+03:00'),
	(79,'Nairobi',3,'+03:00'),
	(80,'Riyadh',3,'+03:00'),
	(81,'St. Petersburg',3,'+03:00'),
	(82,'Volgograd',3,'+03:00'),
	(83,'Tehran',3,'+03:30'),
	(84,'Abu Dhabi',4,'+04:00'),
	(85,'Baku',4,'+04:00'),
	(86,'Muscat',4,'+04:00'),
	(87,'Tbilisi',4,'+04:00'),
	(88,'Yerevan',4,'+04:00'),
	(89,'Kabul',4,'+04:30'),
	(90,'Ekaterinburg',5,'+05:00'),
	(91,'Islamabad',5,'+05:00'),
	(92,'Karachi',5,'+05:00'),
	(93,'Tashkent',5,'+05:00'),
	(94,'Chennai',5,'+05:30'),
	(95,'Kolkata',5,'+05:30'),
	(96,'Mumbai',5,'+05:30'),
	(97,'New Delhi',5,'+05:30'),
	(98,'Sri Jayawardenepura',5,'+05:30'),
	(99,'Kathmandu',5,'+05:45'),
	(100,'Almaty',6,'+06:00'),
	(101,'Astana',6,'+06:00'),
	(102,'Dhaka',6,'+06:00'),
	(103,'Novosibirsk',6,'+06:00'),
	(104,'Rangoon',6,'+06:30'),
	(105,'Bangkok',7,'+07:00'),
	(106,'Hanoi',7,'+07:00'),
	(107,'Jakarta',7,'+07:00'),
	(108,'Krasnoyarsk',7,'+07:00'),
	(109,'Beijing',8,'+08:00'),
	(110,'Chongqing',8,'+08:00'),
	(111,'Hong Kong',8,'+08:00'),
	(112,'Irkutsk',8,'+08:00'),
	(113,'Kuala Lumpur',8,'+08:00'),
	(114,'Perth',8,'+08:00'),
	(115,'Singapore',8,'+08:00'),
	(116,'Taipei',8,'+08:00'),
	(117,'Ulaan Bataar',8,'+08:00'),
	(118,'Urumqi',8,'+08:00'),
	(119,'Osaka',9,'+09:00'),
	(120,'Sapporo',9,'+09:00'),
	(121,'Seoul',9,'+09:00'),
	(122,'Tokyo',9,'+09:00'),
	(123,'Yakutsk',9,'+09:00'),
	(124,'Adelaide',9,'+09:30'),
	(125,'Darwin',9,'+09:30'),
	(126,'Brisbane',10,'+10:00'),
	(127,'Canberra',10,'+10:00'),
	(128,'Guam',10,'+10:00'),
	(129,'Hobart',10,'+10:00'),
	(130,'Melbourne',10,'+10:00'),
	(131,'Port Moresby',10,'+10:00'),
	(132,'Sydney',10,'+10:00'),
	(133,'Vladivostok',10,'+10:00'),
	(134,'Magadan',11,'+11:00'),
	(135,'New Caledonia',11,'+11:00'),
	(136,'Solomon Is.',11,'+11:00'),
	(137,'Auckland',12,'+12:00'),
	(138,'Fiji',12,'+12:00'),
	(139,'Kamchatka',12,'+12:00'),
	(140,'Marshall Is.',12,'+12:00'),
	(141,'Wellington',12,'+12:00'),
	(142,'Nukualofa',13,'+13:00');

/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT '0',
  `role_id` int(11) DEFAULT '0',
  `first_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `aemail` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `timezone` char(6) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
  `default_view` int(11) DEFAULT '0',
  `added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
