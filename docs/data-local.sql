/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - an_mastery
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`an_mastery` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `an_mastery`;

/*Table structure for table `bill_suppliers` */

DROP TABLE IF EXISTS `bill_suppliers`;

CREATE TABLE `bill_suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `price_supplier_id` bigint unsigned DEFAULT NULL,
  `sablon_id` bigint unsigned DEFAULT NULL,
  `total_fee` int DEFAULT NULL,
  `date_bill` date DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_suppliers_supplier_id_foreign` (`supplier_id`),
  KEY `bill_suppliers_price_supplier_id_foreign` (`price_supplier_id`),
  KEY `bill_suppliers_sablon_id_foreign` (`sablon_id`),
  KEY `bill_suppliers_batch_index` (`batch`),
  CONSTRAINT `bill_suppliers_price_supplier_id_foreign` FOREIGN KEY (`price_supplier_id`) REFERENCES `price_suppliers` (`id`),
  CONSTRAINT `bill_suppliers_sablon_id_foreign` FOREIGN KEY (`sablon_id`) REFERENCES `sablons` (`id`),
  CONSTRAINT `bill_suppliers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bill_suppliers` */

insert  into `bill_suppliers`(`id`,`batch`,`supplier_id`,`price_supplier_id`,`sablon_id`,`total_fee`,`date_bill`,`is_paid`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'20260726121818205302',1,2,1,573400,'2026-07-26',1,NULL,'2026-07-26 12:18:18','2026-07-26 12:18:30',51,51,NULL,NULL),
(2,'20260726121818205302',1,2,2,545950,'2026-07-26',1,NULL,'2026-07-26 12:18:18','2026-07-26 12:18:30',51,51,NULL,NULL),
(3,'20260726121818205302',1,1,3,534000,'2026-07-26',1,NULL,'2026-07-26 12:18:18','2026-07-26 12:18:30',51,51,NULL,NULL),
(4,'20260726122534402442',1,1,4,508500,'2026-07-26',0,NULL,'2026-07-26 12:25:34','2026-07-26 12:25:34',51,51,NULL,NULL);

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

insert  into `cache`(`key`,`value`,`expiration`) values 
('anmastery-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:8:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"d\";s:7:\"menu_id\";s:1:\"r\";s:5:\"roles\";s:1:\"l\";s:10:\"created_by\";s:1:\"m\";s:10:\"updated_by\";s:1:\"o\";s:10:\"deleted_by\";}s:11:\"permissions\";a:113:{i:0;a:5:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"dashboard.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:1;s:1:\"r\";a:1:{i:0;i:2;}}i:1;a:5:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"users.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:2;a:5:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"users.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:3;a:5:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"users.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:4;a:5:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"users.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:5;a:5:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"users.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:6;a:5:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"users.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:7;a:5:{s:1:\"a\";i:8;s:1:\"b\";s:13:\"users.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:8;a:5:{s:1:\"a\";i:9;s:1:\"b\";s:17:\"users.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"roles.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"roles.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"roles.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"roles.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"roles.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"roles.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:13:\"roles.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:17:\"roles.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"suppliers.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:16:\"suppliers.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"suppliers.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:14:\"suppliers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:16:\"suppliers.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:16:\"suppliers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:17:\"suppliers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:21:\"suppliers.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;}i:25;a:5:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"employees.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:3;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"employees.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:14:\"employees.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:14:\"employees.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:16:\"employees.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"employees.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"employees.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:21:\"employees.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;}i:33;a:5:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"image-fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:1:{i:0;i:3;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:20:\"image-fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"image-fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:18:\"image-fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:20:\"image-fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:20:\"image-fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:21:\"image-fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:25:\"image-fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;}i:41;a:5:{s:1:\"a\";i:42;s:1:\"b\";s:18:\"color-fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:1:{i:0;i:3;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:20:\"color-fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:18:\"color-fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:18:\"color-fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:20:\"color-fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:20:\"color-fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:21:\"color-fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:25:\"color-fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;}i:49;a:5:{s:1:\"a\";i:50;s:1:\"b\";s:17:\"type-fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:1:{i:0;i:3;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:19:\"type-fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:17:\"type-fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:17:\"type-fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:19:\"type-fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:19:\"type-fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:20:\"type-fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:24:\"type-fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;}i:57;a:5:{s:1:\"a\";i:58;s:1:\"b\";s:16:\"type-colors.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:1:{i:0;i:3;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:18:\"type-colors.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:16:\"type-colors.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:16:\"type-colors.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:18:\"type-colors.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:18:\"type-colors.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:19:\"type-colors.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:23:\"type-colors.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:20:\"price-suppliers.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:22:\"price-suppliers.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:20:\"price-suppliers.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:20:\"price-suppliers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:22:\"price-suppliers.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:22:\"price-suppliers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:23:\"price-suppliers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:27:\"price-suppliers.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:20:\"price-employees.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:22:\"price-employees.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:20:\"price-employees.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:20:\"price-employees.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:22:\"price-employees.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:22:\"price-employees.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:23:\"price-employees.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:27:\"price-employees.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:14:\"presences.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:16:\"presences.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:14:\"presences.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:14:\"presences.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:16:\"presences.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:16:\"presences.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:17:\"presences.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:21:\"presences.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:17;}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:12:\"fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:14:\"fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:12:\"fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:12:\"fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:14:\"fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:14:\"fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:15:\"fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:19:\"fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:12:\"sablons.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:14:\"sablons.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:12:\"sablons.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:12:\"sablons.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:14:\"sablons.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:14:\"sablons.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:15:\"sablons.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:19:\"sablons.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:19;}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:19:\"bill-suppliers.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:21:\"bill-suppliers.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:19:\"bill-suppliers.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:19:\"bill-suppliers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:21:\"bill-suppliers.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:21:\"bill-suppliers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:22:\"bill-suppliers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:26:\"bill-suppliers.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:20;}}s:5:\"roles\";a:2:{i:0;a:6:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";s:1:\"l\";N;s:1:\"m\";N;s:1:\"o\";N;}i:1;a:6:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";s:1:\"l\";N;s:1:\"m\";N;s:1:\"o\";N;}}}',1785134491);

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `color_fabrics` */

DROP TABLE IF EXISTS `color_fabrics`;

CREATE TABLE `color_fabrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `color_fabrics` */

insert  into `color_fabrics`(`id`,`name`,`code_color`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Jambon','#fbcbf5','tes','2026-04-26 13:25:26','2026-05-13 15:08:33',51,51,NULL,NULL),
(2,'Krem','#dbcfb8',NULL,'2026-06-28 18:13:46','2026-06-23 10:00:05',51,51,NULL,NULL),
(3,'Blewah','#d3b573',NULL,'2026-06-28 18:13:58','2026-06-23 09:59:55',51,51,NULL,NULL),
(4,'Biru','#92b9f7',NULL,'2026-06-28 18:14:12','2026-06-23 10:00:44',51,51,NULL,NULL),
(5,'Merah','#f45252',NULL,'2026-06-23 10:00:53','2026-06-23 10:00:53',51,51,NULL,NULL),
(6,'Kuning','#f1e909',NULL,'2026-06-23 10:01:04','2026-06-23 10:01:04',51,51,NULL,NULL),
(7,'Coklat','#a38552',NULL,'2026-06-23 10:01:19','2026-06-23 10:01:19',51,51,NULL,NULL);

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `employees` */

insert  into `employees`(`id`,`name`,`address`,`contact`,`is_active`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Agung','tulungagung','08123456780',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(2,'Mambar','tulungagung','08123456781',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(3,'Ipin','tulungagung','08123456782',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(4,'Empi','tulungagung','08123456783',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(5,'Rizal','tulungagung','08123456784',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(6,'Agus','tulungagung','08123456785',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(7,'Harno','tulungagung','08123456786',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(8,'RikoA','tulungagung','08123456787',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(9,'Mujib','tulungagung','08123456788',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(10,'Irwan','tulungagung','08123456789',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(11,'Yoga','tulungagung','081234567810',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(12,'Adi','tulungagung','081234567811',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(13,'Viki','tulungagung','081234567812',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(14,'Arip','tulungagung','081234567813',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(15,'Ayet','tulungagung','081234567814',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(16,'Hegler','tulungagung','081234567815',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(17,'Dadang','tulungagung','081234567816',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(18,'RikoB','tulungagung','081234567817',1,'tes','2026-04-26 13:02:00','2026-06-28 18:07:43',NULL,51,NULL,NULL);

/*Table structure for table `fabric_details` */

DROP TABLE IF EXISTS `fabric_details`;

CREATE TABLE `fabric_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fabric_id` bigint unsigned DEFAULT NULL,
  `color_fabric_id` bigint unsigned DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fabric_details_fabric_id_foreign` (`fabric_id`),
  KEY `fabric_details_color_fabric_id_foreign` (`color_fabric_id`),
  CONSTRAINT `fabric_details_color_fabric_id_foreign` FOREIGN KEY (`color_fabric_id`) REFERENCES `color_fabrics` (`id`),
  CONSTRAINT `fabric_details_fabric_id_foreign` FOREIGN KEY (`fabric_id`) REFERENCES `fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `fabric_details` */

insert  into `fabric_details`(`id`,`fabric_id`,`color_fabric_id`,`stock`,`notes`,`created_at`,`updated_at`) values 
(1,1,1,10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(2,1,2,10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(3,1,3,10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(4,1,4,10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(5,2,7,10,NULL,'2026-07-26 09:44:55','2026-07-26 09:44:55'),
(6,2,3,10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56'),
(7,2,4,10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56'),
(8,2,1,10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56');

/*Table structure for table `fabrics` */

DROP TABLE IF EXISTS `fabrics`;

CREATE TABLE `fabrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `type_fabric_id` bigint unsigned DEFAULT NULL,
  `date_coming` date DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'auto *example {name_supplier}{timestamp()} SUNAR17829012',
  `seri` int DEFAULT NULL,
  `stock_total` int DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fabrics_supplier_id_foreign` (`supplier_id`),
  KEY `fabrics_type_fabric_id_foreign` (`type_fabric_id`),
  CONSTRAINT `fabrics_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `fabrics_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `fabrics` */

insert  into `fabrics`(`id`,`supplier_id`,`type_fabric_id`,`date_coming`,`code`,`seri`,`stock_total`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,1,'2026-06-27','SUNAR-20260628181445',4,40,'aman\ntes\n\n12','2026-06-28 18:14:45','2026-06-28 19:28:38',51,51,NULL,NULL),
(2,1,2,'2026-07-26','SUNAR-20260726094455',4,40,NULL,'2026-07-26 09:44:55','2026-07-26 09:44:55',51,51,NULL,NULL);

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `history_stocks` */

DROP TABLE IF EXISTS `history_stocks`;

CREATE TABLE `history_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fabric_detail_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'enum StatusSablonEnum',
  `total` int DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `history_stocks_fabric_detail_id_foreign` (`fabric_detail_id`),
  CONSTRAINT `history_stocks_fabric_detail_id_foreign` FOREIGN KEY (`fabric_detail_id`) REFERENCES `fabric_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `history_stocks` */

insert  into `history_stocks`(`id`,`fabric_detail_id`,`status`,`total`,`notes`,`created_at`,`updated_at`) values 
(1,1,'IN',10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(2,2,'IN',10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(3,3,'IN',10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(4,4,'IN',10,NULL,'2026-06-28 18:14:45','2026-06-28 18:14:45'),
(5,5,'IN',10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56'),
(6,6,'IN',10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56'),
(7,7,'IN',10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56'),
(8,8,'IN',10,NULL,'2026-07-26 09:44:56','2026-07-26 09:44:56');

/*Table structure for table `image_fabrics` */

DROP TABLE IF EXISTS `image_fabrics`;

CREATE TABLE `image_fabrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `image_fabrics` */

insert  into `image_fabrics`(`id`,`name`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Robot',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(2,'Love',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(3,'Kipas',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(4,'Kelinci',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(5,'Beruang',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(6,'Angry Birds',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(7,'Bulan Bintang',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(8,'Flamboyan',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(9,'Kenangan',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(10,'Bawang',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(11,'Cempaka',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(12,'Raflesia',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(13,'Lotus',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(14,'Adenium',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(15,'Rosela',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(16,'Seruni',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(17,'Anggrek',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(18,'Semanggi',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(19,'Teratai',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(20,'Nusa Indah',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(21,'Matahari',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(22,'Bougenvil',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(23,'Aster',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(24,'Jasmin',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(25,'Dahlia',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(26,'Melati',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(27,'Mawar',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(28,'Sepatu',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(29,'Kuncup',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(30,'Sakura',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(31,'Mahkota',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(32,'Kamboja',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(33,'Lavender',NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(34,'Kaktus',NULL,'2026-06-28 18:52:20','2026-06-28 18:52:20',51,51,NULL,NULL);

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `media` */

/*Table structure for table `menu_permissions` */

DROP TABLE IF EXISTS `menu_permissions`;

CREATE TABLE `menu_permissions` (
  `menu_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  KEY `menu_permissions_menu_id_foreign` (`menu_id`),
  KEY `menu_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `menu_permissions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  CONSTRAINT `menu_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `menu_permissions` */

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `menus` */

insert  into `menus`(`id`,`parent_id`,`name`,`icon`,`url`,`sort_order`,`is_active`,`created_at`,`updated_at`) values 
(1,NULL,'Dashboard','home','/dashboard',1,1,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(2,NULL,'Access Management','shield','#access-management',2,1,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(3,2,'Users',NULL,'/users',1,1,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(4,2,'Roles',NULL,'/roles',2,1,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(6,14,'Suppliers',NULL,'/suppliers',1,1,'2026-04-26 13:01:48','2026-06-28 15:14:18'),
(7,14,'Employees',NULL,'/employees',2,1,'2026-04-26 13:01:48','2026-06-28 15:14:18'),
(8,15,'Image Fabrics',NULL,'/image-fabrics',1,1,'2026-04-26 13:01:48','2026-06-28 15:14:18'),
(9,15,'Color Fabrics',NULL,'/color-fabrics',2,1,'2026-04-26 13:01:48','2026-06-28 15:14:18'),
(10,15,'Type Fabrics',NULL,'/type-fabrics',3,1,'2026-04-26 13:01:48','2026-06-28 15:14:18'),
(11,15,'Type Colors',NULL,'/type-colors',4,1,'2026-05-24 11:08:47','2026-06-28 15:14:18'),
(12,16,'Price Supplier',NULL,'/price-suppliers',1,1,'2026-05-24 11:08:47','2026-06-28 15:14:18'),
(13,16,'Price Employee',NULL,'/price-employees',2,1,'2026-06-21 11:07:54','2026-06-28 15:14:18'),
(14,NULL,'People','users','#people',3,1,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(15,NULL,'Fabric Attribute','layers','#fabric-attribute',4,1,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(16,NULL,'Pricing','dollar-sign','#pricing',5,1,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(17,NULL,'Employee Presence','calendar-check-2','/presences',6,1,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(18,NULL,'Inventory Fabric','package','/fabrics',7,1,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(19,NULL,'Sablon','paintbrush','/sablons',8,1,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(20,NULL,'Bill Supplier','receipt-text','/bill-suppliers',9,1,'2026-07-26 09:39:54','2026-07-26 09:39:54');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_09_000000_add_two_factor_columns_to_users_table',1),
(5,'2026_01_09_020401_create_media_table',1),
(6,'2026_01_09_020402_create_suppliers_table',1),
(7,'2026_01_09_020850_create_employees_table',1),
(8,'2026_01_09_020933_create_image_fabrics_table',1),
(9,'2026_01_09_022539_create_color_fabrics_table',1),
(10,'2026_01_09_022642_create_type_fabrics_table',1),
(11,'2026_01_09_022707_create_type_colors_table',1),
(12,'2026_01_09_022812_create_price_suppliers_table',1),
(13,'2026_01_09_023019_create_price_employees_table',1),
(14,'2026_01_09_023438_create_presences_table',1),
(15,'2026_01_09_023803_create_fabrics_table',1),
(16,'2026_01_09_024300_create_fabric_details_table',1),
(17,'2026_01_09_024607_create_history_stocks_table',1),
(18,'2026_01_09_025447_create_sablons_table',1),
(19,'2026_01_09_025935_create_sablon_details_table',1),
(20,'2026_01_09_030050_create_sablon_employee_details_table',1),
(21,'2026_01_09_030249_create_bill_suppliers_table',1),
(22,'2026_01_09_030432_create_bill_supplier_details_table',1),
(23,'2026_01_10_123401_create_permission_tables',1),
(24,'2026_01_20_060718_create_menus_table',1),
(25,'2026_01_20_062042_create_menu_permissions_table',1),
(26,'2026_02_19_133515_add_menu_id_to_permissions_table',1),
(27,'2026_02_20_112934_add_soft_deletes_to_roles',1),
(28,'2026_05_14_150053_change_datetime_to_date_week_of_to_presences',2),
(29,'2026_06_23_081129_add_fabric_detail_id_to_sablons',3),
(30,'2026_06_23_090019_add_seri_to_fabrics',3),
(31,'2026_06_23_102020_add_timestamp_to_fabric_details',3),
(32,'2026_06_23_140140_add_some_column_to_sablon_employee_details',4),
(33,'2026_06_24_081413_create_salary_employees_table',4),
(34,'2026_06_24_082750_remove_fabric_detail_id_to_sablons',4),
(35,'2026_06_28_175622_add_is_active_to_employess_and_suppliers',5),
(37,'2026_06_28_190726_add_some_column_to_fabrics',6),
(38,'2026_07_01_081614_add_is_paid_to_bill_suppliers',7),
(39,'2026_07_26_120016_add_batch_to_bill_suppliers',8),
(40,'2026_07_26_131402_drop_table_to_bill_supplier_details',9),
(41,'2026_07_26_133559_create_supplier_cover_styles_table',10);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values 
(3,'App\\Models\\User',49),
(1,'App\\Models\\User',51),
(2,'App\\Models\\User',52);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`),
  KEY `permissions_menu_id_foreign` (`menu_id`),
  CONSTRAINT `permissions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`menu_id`,`created_at`,`updated_at`) values 
(1,'dashboard.view','web',1,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(2,'users.view','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(3,'users.create','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(4,'users.read','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(5,'users.edit','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(6,'users.update','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(7,'users.delete','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(8,'users.restore','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(9,'users.forceDelete','web',3,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(10,'roles.view','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(11,'roles.create','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(12,'roles.read','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(13,'roles.edit','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(14,'roles.update','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(15,'roles.delete','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(16,'roles.restore','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(17,'roles.forceDelete','web',4,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(18,'suppliers.view','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(19,'suppliers.create','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(20,'suppliers.read','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(21,'suppliers.edit','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(22,'suppliers.update','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(23,'suppliers.delete','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(24,'suppliers.restore','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(25,'suppliers.forceDelete','web',6,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(26,'employees.view','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(27,'employees.create','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(28,'employees.read','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(29,'employees.edit','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(30,'employees.update','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(31,'employees.delete','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(32,'employees.restore','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(33,'employees.forceDelete','web',7,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(34,'image-fabrics.view','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(35,'image-fabrics.create','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(36,'image-fabrics.read','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(37,'image-fabrics.edit','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(38,'image-fabrics.update','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(39,'image-fabrics.delete','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(40,'image-fabrics.restore','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(41,'image-fabrics.forceDelete','web',8,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(42,'color-fabrics.view','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(43,'color-fabrics.create','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(44,'color-fabrics.read','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(45,'color-fabrics.edit','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(46,'color-fabrics.update','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(47,'color-fabrics.delete','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(48,'color-fabrics.restore','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(49,'color-fabrics.forceDelete','web',9,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(50,'type-fabrics.view','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(51,'type-fabrics.create','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(52,'type-fabrics.read','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(53,'type-fabrics.edit','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(54,'type-fabrics.update','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(55,'type-fabrics.delete','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(56,'type-fabrics.restore','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(57,'type-fabrics.forceDelete','web',10,'2026-04-26 13:01:48','2026-04-26 13:01:48'),
(58,'type-colors.view','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(59,'type-colors.create','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(60,'type-colors.read','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(61,'type-colors.edit','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(62,'type-colors.update','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(63,'type-colors.delete','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(64,'type-colors.restore','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(65,'type-colors.forceDelete','web',11,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(66,'price-suppliers.view','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(67,'price-suppliers.create','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(68,'price-suppliers.read','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(69,'price-suppliers.edit','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(70,'price-suppliers.update','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(71,'price-suppliers.delete','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(72,'price-suppliers.restore','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(73,'price-suppliers.forceDelete','web',12,'2026-05-24 11:08:47','2026-05-24 11:08:47'),
(74,'price-employees.view','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(75,'price-employees.create','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(76,'price-employees.read','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(77,'price-employees.edit','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(78,'price-employees.update','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(79,'price-employees.delete','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(80,'price-employees.restore','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(81,'price-employees.forceDelete','web',13,'2026-06-21 11:07:54','2026-06-21 11:07:54'),
(82,'presences.view','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(83,'presences.create','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(84,'presences.read','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(85,'presences.edit','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(86,'presences.update','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(87,'presences.delete','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(88,'presences.restore','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(89,'presences.forceDelete','web',17,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(90,'fabrics.view','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(91,'fabrics.create','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(92,'fabrics.read','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(93,'fabrics.edit','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(94,'fabrics.update','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(95,'fabrics.delete','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(96,'fabrics.restore','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(97,'fabrics.forceDelete','web',18,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(98,'sablons.view','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(99,'sablons.create','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(100,'sablons.read','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(101,'sablons.edit','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(102,'sablons.update','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(103,'sablons.delete','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(104,'sablons.restore','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(105,'sablons.forceDelete','web',19,'2026-06-28 15:14:18','2026-06-28 15:14:18'),
(106,'bill-suppliers.view','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(107,'bill-suppliers.create','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(108,'bill-suppliers.read','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(109,'bill-suppliers.edit','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(110,'bill-suppliers.update','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(111,'bill-suppliers.delete','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(112,'bill-suppliers.restore','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54'),
(113,'bill-suppliers.forceDelete','web',20,'2026-07-26 09:39:54','2026-07-26 09:39:54');

/*Table structure for table `presences` */

DROP TABLE IF EXISTS `presences`;

CREATE TABLE `presences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned DEFAULT NULL,
  `week_of` date DEFAULT NULL,
  `monday` int DEFAULT NULL,
  `tuesday` int DEFAULT NULL,
  `wednesday` int DEFAULT NULL,
  `thursday` int DEFAULT NULL,
  `friday` int DEFAULT NULL,
  `saturday` int DEFAULT NULL,
  `sunday` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presences_employee_id_foreign` (`employee_id`),
  CONSTRAINT `presences_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `presences` */

insert  into `presences`(`id`,`employee_id`,`week_of`,`monday`,`tuesday`,`wednesday`,`thursday`,`friday`,`saturday`,`sunday`,`total`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,12,'2026-06-22',10000,10000,10000,10000,10000,10000,0,60000,NULL,'2026-06-28 15:24:07','2026-06-28 15:24:07',51,51,NULL,NULL);

/*Table structure for table `price_employees` */

DROP TABLE IF EXISTS `price_employees`;

CREATE TABLE `price_employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_fabric_id` bigint unsigned DEFAULT NULL,
  `type_color_id` bigint unsigned DEFAULT NULL,
  `price` int DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_employees_type_fabric_id_foreign` (`type_fabric_id`),
  KEY `price_employees_type_color_id_foreign` (`type_color_id`),
  CONSTRAINT `price_employees_type_color_id_foreign` FOREIGN KEY (`type_color_id`) REFERENCES `type_colors` (`id`),
  CONSTRAINT `price_employees_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `price_employees` */

insert  into `price_employees`(`id`,`type_fabric_id`,`type_color_id`,`price`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,2,1,450,NULL,'2026-06-21 11:11:59','2026-06-28 17:53:13',51,51,NULL,NULL),
(2,1,2,500,NULL,'2026-06-21 11:12:15','2026-06-28 17:53:06',51,51,NULL,NULL),
(3,1,3,525,NULL,'2026-06-21 11:12:32','2026-06-28 17:52:58',51,51,NULL,NULL);

/*Table structure for table `price_suppliers` */

DROP TABLE IF EXISTS `price_suppliers`;

CREATE TABLE `price_suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `type_fabric_id` bigint unsigned DEFAULT NULL,
  `type_color_id` bigint unsigned DEFAULT NULL,
  `price` int DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_suppliers_supplier_id_foreign` (`supplier_id`),
  KEY `price_suppliers_type_fabric_id_foreign` (`type_fabric_id`),
  KEY `price_suppliers_type_color_id_foreign` (`type_color_id`),
  CONSTRAINT `price_suppliers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `price_suppliers_type_color_id_foreign` FOREIGN KEY (`type_color_id`) REFERENCES `type_colors` (`id`),
  CONSTRAINT `price_suppliers_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `price_suppliers` */

insert  into `price_suppliers`(`id`,`supplier_id`,`type_fabric_id`,`type_color_id`,`price`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,2,3,1500,NULL,'2026-05-24 11:12:04','2026-07-26 09:47:41',51,51,NULL,NULL),
(2,1,1,3,1525,NULL,'2026-07-26 09:38:03','2026-07-26 09:41:55',51,51,NULL,NULL),
(3,1,2,2,1400,NULL,'2026-07-26 09:47:24','2026-07-26 09:47:24',51,51,NULL,NULL);

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

insert  into `role_has_permissions`(`permission_id`,`role_id`) values 
(1,2),
(2,2),
(3,2),
(4,2),
(5,2),
(6,2),
(7,2),
(8,2),
(9,2),
(26,3),
(34,3),
(42,3),
(50,3),
(58,3);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Super Admin','web','2026-04-26 13:01:59','2026-04-26 13:01:59',NULL,NULL,NULL,NULL),
(2,'Admin','web','2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(3,'Employee','web','2026-05-27 12:21:49','2026-05-27 12:21:49',NULL,NULL,NULL,NULL);

/*Table structure for table `sablon_details` */

DROP TABLE IF EXISTS `sablon_details`;

CREATE TABLE `sablon_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sablon_id` bigint unsigned DEFAULT NULL,
  `fabric_detail_id` bigint unsigned DEFAULT NULL,
  `color_fabric_id` bigint unsigned DEFAULT NULL,
  `long_fabric` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sablon_details_sablon_id_foreign` (`sablon_id`),
  KEY `sablon_details_color_fabric_id_foreign` (`color_fabric_id`),
  KEY `sablon_details_fabric_detail_id_foreign` (`fabric_detail_id`),
  CONSTRAINT `sablon_details_color_fabric_id_foreign` FOREIGN KEY (`color_fabric_id`) REFERENCES `color_fabrics` (`id`),
  CONSTRAINT `sablon_details_fabric_detail_id_foreign` FOREIGN KEY (`fabric_detail_id`) REFERENCES `fabric_details` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sablon_details_sablon_id_foreign` FOREIGN KEY (`sablon_id`) REFERENCES `sablons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sablon_details` */

insert  into `sablon_details`(`id`,`sablon_id`,`fabric_detail_id`,`color_fabric_id`,`long_fabric`) values 
(41,1,1,1,93),
(42,1,2,2,93),
(43,1,3,3,94),
(44,1,4,4,96),
(49,3,5,7,90),
(50,3,6,3,92),
(51,3,7,4,87),
(52,3,8,1,87),
(53,2,1,1,85),
(54,2,2,2,92),
(55,2,3,3,91),
(56,2,4,4,90),
(57,4,5,7,90),
(58,4,6,3,80),
(59,4,7,4,82),
(60,4,8,1,87),
(61,5,1,1,91),
(62,5,2,2,87),
(63,5,3,3,86),
(64,5,4,4,85);

/*Table structure for table `sablon_employee_details` */

DROP TABLE IF EXISTS `sablon_employee_details`;

CREATE TABLE `sablon_employee_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sablon_id` bigint unsigned DEFAULT NULL,
  `fabric_detail_id` bigint unsigned DEFAULT NULL,
  `employee_id` bigint unsigned DEFAULT NULL,
  `layers` int DEFAULT NULL,
  `fee` int DEFAULT NULL,
  `additional_fee` json DEFAULT NULL,
  `total` int DEFAULT NULL,
  `is_change` tinyint(1) DEFAULT NULL,
  `employee_change_id` bigint unsigned DEFAULT NULL,
  `is_payed` tinyint(1) DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sablon_employee_details_sablon_id_foreign` (`sablon_id`),
  KEY `sablon_employee_details_fabric_detail_id_foreign` (`fabric_detail_id`),
  KEY `sablon_employee_details_employee_id_foreign` (`employee_id`),
  KEY `sablon_employee_details_employee_change_id_foreign` (`employee_change_id`),
  CONSTRAINT `sablon_employee_details_employee_change_id_foreign` FOREIGN KEY (`employee_change_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sablon_employee_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  CONSTRAINT `sablon_employee_details_fabric_detail_id_foreign` FOREIGN KEY (`fabric_detail_id`) REFERENCES `fabric_details` (`id`),
  CONSTRAINT `sablon_employee_details_sablon_id_foreign` FOREIGN KEY (`sablon_id`) REFERENCES `sablons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sablon_employee_details` */

insert  into `sablon_employee_details`(`id`,`sablon_id`,`fabric_detail_id`,`employee_id`,`layers`,`fee`,`additional_fee`,`total`,`is_change`,`employee_change_id`,`is_payed`,`notes`,`created_at`,`updated_at`) values 
(25,1,NULL,14,1,65800,'[{\"notes\": \"\", \"nominal\": -60000}]',5800,0,NULL,0,NULL,'2026-06-28 19:40:51','2026-06-28 19:40:51'),
(26,1,NULL,15,1,65800,'[{\"notes\": \"\", \"nominal\": -30000}]',35800,0,NULL,0,NULL,'2026-06-28 19:40:51','2026-06-28 19:40:51'),
(27,1,NULL,17,1,65800,'[{\"notes\": \"\", \"nominal\": -45000}]',20800,0,NULL,0,NULL,'2026-06-28 19:40:51','2026-06-28 19:40:51'),
(30,3,NULL,5,1,62300,'[]',62300,0,NULL,0,NULL,'2026-07-26 09:46:59','2026-07-26 09:46:59'),
(31,3,NULL,3,1,62300,'[]',62300,0,NULL,0,NULL,'2026-07-26 09:46:59','2026-07-26 09:46:59'),
(32,3,NULL,9,1,62300,'[]',62300,0,NULL,0,NULL,'2026-07-26 09:46:59','2026-07-26 09:46:59'),
(33,2,NULL,4,2,125300,'[{\"notes\": \"\", \"nominal\": -60000}]',65300,0,NULL,0,NULL,'2026-07-26 11:40:51','2026-07-26 11:40:51'),
(34,2,NULL,6,1,62700,'[{\"notes\": \"\", \"nominal\": -30000}]',32700,0,NULL,0,NULL,'2026-07-26 11:40:51','2026-07-26 11:40:51'),
(35,4,NULL,1,1,56500,'[]',56500,0,NULL,0,NULL,'2026-07-26 12:21:17','2026-07-26 12:21:17'),
(36,4,NULL,2,1,56500,'[]',56500,0,NULL,0,NULL,'2026-07-26 12:21:17','2026-07-26 12:21:17'),
(37,4,NULL,5,1,56500,'[]',56500,0,NULL,0,NULL,'2026-07-26 12:21:17','2026-07-26 12:21:17'),
(38,5,NULL,1,1,61100,'[]',61100,0,NULL,0,NULL,'2026-07-26 13:20:10','2026-07-26 13:20:10'),
(39,5,NULL,2,1,61100,'[]',61100,0,NULL,0,NULL,'2026-07-26 13:20:10','2026-07-26 13:20:10'),
(40,5,NULL,3,1,61100,'[]',61100,0,NULL,0,NULL,'2026-07-26 13:20:10','2026-07-26 13:20:10');

/*Table structure for table `sablons` */

DROP TABLE IF EXISTS `sablons`;

CREATE TABLE `sablons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `fabric_id` bigint unsigned DEFAULT NULL,
  `image_fabric_id` bigint unsigned DEFAULT NULL,
  `type_color_id` bigint unsigned DEFAULT NULL,
  `type_fabric_id` bigint unsigned DEFAULT NULL,
  `price_employee_id` bigint unsigned DEFAULT NULL,
  `total_long_fabric` int DEFAULT NULL,
  `total_sablon` int DEFAULT NULL COMMENT 'total long fabric * price employee',
  `date_sablon` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'enum StatusSablonEnum',
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sablons_supplier_id_foreign` (`supplier_id`),
  KEY `sablons_fabric_id_foreign` (`fabric_id`),
  KEY `sablons_image_fabric_id_foreign` (`image_fabric_id`),
  KEY `sablons_type_color_id_foreign` (`type_color_id`),
  KEY `sablons_type_fabric_id_foreign` (`type_fabric_id`),
  KEY `sablons_price_employee_id_foreign` (`price_employee_id`),
  CONSTRAINT `sablons_fabric_id_foreign` FOREIGN KEY (`fabric_id`) REFERENCES `fabrics` (`id`),
  CONSTRAINT `sablons_image_fabric_id_foreign` FOREIGN KEY (`image_fabric_id`) REFERENCES `image_fabrics` (`id`),
  CONSTRAINT `sablons_price_employee_id_foreign` FOREIGN KEY (`price_employee_id`) REFERENCES `price_employees` (`id`),
  CONSTRAINT `sablons_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `sablons_type_color_id_foreign` FOREIGN KEY (`type_color_id`) REFERENCES `type_colors` (`id`),
  CONSTRAINT `sablons_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sablons` */

insert  into `sablons`(`id`,`supplier_id`,`fabric_id`,`image_fabric_id`,`type_color_id`,`type_fabric_id`,`price_employee_id`,`total_long_fabric`,`total_sablon`,`date_sablon`,`status`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,1,34,3,1,3,376,197400,'2026-07-26','DONE',NULL,'2026-07-26 09:43:19','2026-07-26 11:39:24',51,51,NULL,NULL),
(2,1,1,25,3,1,3,358,187950,'2026-07-26','DONE',NULL,'2026-07-26 09:43:19','2026-07-26 11:40:51',51,51,NULL,NULL),
(3,1,2,12,3,2,3,356,186900,'2026-07-26','DONE',NULL,'2026-07-26 09:46:59','2026-07-26 11:40:57',51,51,NULL,NULL),
(4,1,2,14,3,2,2,339,169500,'2026-07-26','DONE',NULL,'2026-07-26 12:21:17','2026-07-26 12:21:17',51,51,NULL,NULL),
(5,1,1,17,3,1,3,349,183225,'2026-07-26','DONE',NULL,'2026-07-26 13:20:10','2026-07-26 13:20:42',51,51,NULL,NULL);

/*Table structure for table `salary_employees` */

DROP TABLE IF EXISTS `salary_employees`;

CREATE TABLE `salary_employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned DEFAULT NULL,
  `fee` int DEFAULT NULL,
  `additional_fee` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salary_employees_employee_id_foreign` (`employee_id`),
  CONSTRAINT `salary_employees_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `salary_employees` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('x8WbXYzzneZqkpsjWOjmXzO1dW4K6dMbO9E28ogV',51,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoia2pPaHQ5TVNWNXBqR2QyRWFUTnNzWERWQ09CVTQxeUQ1SGxBWUxybSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjc5OiJodHRwczovL2FuLW1hc3RlcnktdjMudGVzdC9iaWxsLXN1cHBsaWVycz93ZWVrX2VuZD0yMDI2LVczMCZ3ZWVrX3N0YXJ0PTIwMjYtVzMwIjtzOjU6InJvdXRlIjtzOjIwOiJiaWxsX3N1cHBsaWVycy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjUxO3M6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fX0=',1785050547);

/*Table structure for table `supplier_cover_styles` */

DROP TABLE IF EXISTS `supplier_cover_styles`;

CREATE TABLE `supplier_cover_styles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned NOT NULL,
  `color_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'book-marked',
  `pattern` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stripes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supplier_cover_styles_supplier_id_unique` (`supplier_id`),
  CONSTRAINT `supplier_cover_styles_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `supplier_cover_styles` */

insert  into `supplier_cover_styles`(`id`,`supplier_id`,`color_from`,`color_to`,`icon`,`pattern`,`created_at`,`updated_at`) values 
(1,1,'#cee70d','#03a03f','book-marked','stripes','2026-07-26 14:15:38','2026-07-26 14:15:38');

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `suppliers` */

insert  into `suppliers`(`id`,`name`,`address`,`contact`,`is_active`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Sunar','tulungagung','08123456780',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(2,'Yadi','tulungagung','08123456781',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(3,'Bibit','tulungagung','08123456782',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(4,'Santoso','tulungagung','08123456783',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(5,'Mail','tulungagung','08123456784',0,NULL,'2026-04-26 13:02:00','2026-06-28 18:11:12',NULL,51,NULL,NULL),
(6,'Yanti','tulungagung','08123456785',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(7,'Rusda','tulungagung','08123456786',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(8,'Ahmad','tulungagung','08123456787',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(9,'Sirajudin','tulungagung','08123456788',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(10,'Udin','tulungagung','08123456789',1,NULL,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL);

/*Table structure for table `type_colors` */

DROP TABLE IF EXISTS `type_colors`;

CREATE TABLE `type_colors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` int NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `type_colors` */

insert  into `type_colors`(`id`,`name`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,'Tes notes 3','2026-05-24 11:09:41','2026-06-21 10:17:26',51,51,NULL,NULL),
(2,2,'Tes notes 2','2026-05-24 11:09:51','2026-06-21 10:17:20',51,51,NULL,NULL),
(3,3,'Tes notes 1','2026-05-24 11:09:58','2026-06-21 10:17:14',51,51,NULL,NULL);

/*Table structure for table `type_fabrics` */

DROP TABLE IF EXISTS `type_fabrics`;

CREATE TABLE `type_fabrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `type_fabrics` */

insert  into `type_fabrics`(`id`,`name`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'INT',NULL,'2026-05-24 11:09:18','2026-05-24 11:09:18',51,51,NULL,NULL),
(2,'HGT',NULL,'2026-05-24 11:09:27','2026-05-24 11:09:27',51,51,NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`two_factor_secret`,`two_factor_recovery_codes`,`two_factor_confirmed_at`,`remember_token`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'User 1','user1@gmail.com','2026-04-26 13:01:48','$2y$12$oC1E9wpsusR2D3Lkh1Lj9.CsoikbkFLCrdaA7Jw83vyHn7iPSvq7C',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:48','2026-04-26 13:01:48',NULL,NULL,NULL,NULL),
(2,'User 2','user2@gmail.com','2026-04-26 13:01:48','$2y$12$mZJqWZumQn2rqBUQzsRMJuI5E5WU1MMCCiyUnFy4tF7b3EBc7H.3W',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:48','2026-04-26 13:01:48',NULL,NULL,NULL,NULL),
(3,'User 3','user3@gmail.com','2026-04-26 13:01:49','$2y$12$14XpBcqwq8IYrSbe/MztyOFWOmszVC8zBy2bpMzK2Jt429T.cmSmm',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:49','2026-04-26 13:01:49',NULL,NULL,NULL,NULL),
(4,'User 4','user4@gmail.com','2026-04-26 13:01:49','$2y$12$1d3YvYjPfZb4MKvmd/pUYeCRO3arJP.hZBj0GDVvvjMFMDf4mpEfi',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:49','2026-04-26 13:01:49',NULL,NULL,NULL,NULL),
(5,'User 5','user5@gmail.com','2026-04-26 13:01:49','$2y$12$.gl3aVE5jkcs9IWUMctINeskzMpatwZYOtriG4c1/yyR25YJyO.B6',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:49','2026-04-26 13:01:49',NULL,NULL,NULL,NULL),
(6,'User 6','user6@gmail.com','2026-04-26 13:01:49','$2y$12$NDJ0h9Hk6g4Xj0VNEQ9ChOyAZfyARSWP.GUUbMc7s6dZXdh1VM7u.',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:49','2026-04-26 13:01:49',NULL,NULL,NULL,NULL),
(7,'User 7','user7@gmail.com','2026-04-26 13:01:49','$2y$12$pszmUkKfbQak2EakBrW2veKSbl/xd0QBqKK9Dvw/H5DlpmjFeljpu',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:49','2026-04-26 13:01:49',NULL,NULL,NULL,NULL),
(8,'User 8','user8@gmail.com','2026-04-26 13:01:50','$2y$12$Y3Jotsnfq/CZIcYoBkK6Y.HuaJIz951akZ/ZZCexpUtu28FzQ./VS',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:50','2026-04-26 13:01:50',NULL,NULL,NULL,NULL),
(9,'User 9','user9@gmail.com','2026-04-26 13:01:50','$2y$12$WnVvjXTtTgAiSEeRmnHebeBjzvR1sn3i1Tc07MFUp.V8tM5UJpQ1O',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:50','2026-04-26 13:01:50',NULL,NULL,NULL,NULL),
(10,'User 10','user10@gmail.com','2026-04-26 13:01:50','$2y$12$Pd9oArJb/rZmYNjL7oPwN.EQ59jv.Q.eVsCCzUrHBkis9zvuUucVK',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:50','2026-04-26 13:01:50',NULL,NULL,NULL,NULL),
(11,'User 11','user11@gmail.com','2026-04-26 13:01:50','$2y$12$u0qSZ9Y0acymxELZbIVgtu7KM44CDtsmXTqLjlyMDRlgTn4/WsOAa',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:50','2026-04-26 13:01:50',NULL,NULL,NULL,NULL),
(12,'User 12','user12@gmail.com','2026-04-26 13:01:51','$2y$12$qTpn5kME2mMFY4iihab0Lu1sSRUH6syzehBo0kIyFUVZp/3V5s64a',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:51','2026-04-26 13:01:51',NULL,NULL,NULL,NULL),
(13,'User 13','user13@gmail.com','2026-04-26 13:01:51','$2y$12$PwMaYR4FletQSqdpAuIXhOiK8BVzfFcal5PzS4wSDvwxCf6nak7nO',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:51','2026-04-26 13:01:51',NULL,NULL,NULL,NULL),
(14,'User 14','user14@gmail.com','2026-04-26 13:01:51','$2y$12$Ojk1TwMqkiuaR9Apevs6SuydMgUDzUwVcdP86iWqcK/74NBQMHurS',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:51','2026-04-26 13:01:51',NULL,NULL,NULL,NULL),
(15,'User 15','user15@gmail.com','2026-04-26 13:01:51','$2y$12$e/Zf0Ptek8ZTZuWdwD7Ix.2RG4JL5W1QeWCyI0bFRdotesMme6Uzm',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:51','2026-04-26 13:01:51',NULL,NULL,NULL,NULL),
(16,'User 16','user16@gmail.com','2026-04-26 13:01:52','$2y$12$mV5eyWJiBxcWJgAD2zsYq.a4eDhhTDUQMOayBFdUTKVOV5kVF44YC',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:52','2026-04-26 13:01:52',NULL,NULL,NULL,NULL),
(17,'User 17','user17@gmail.com','2026-04-26 13:01:52','$2y$12$B0LxC2KCGIsy4KLJPFDeMeZSo1Rfor.4sRM5G8aZ0xI.QbZ2R1GWC',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:52','2026-04-26 13:01:52',NULL,NULL,NULL,NULL),
(18,'User 18','user18@gmail.com','2026-04-26 13:01:52','$2y$12$VO32vtekA6zoX5.NrNxl2ubYnrDImJzCjWi4j1UVSxUh4n9IqF2M2',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:52','2026-04-26 13:01:52',NULL,NULL,NULL,NULL),
(19,'User 19','user19@gmail.com','2026-04-26 13:01:52','$2y$12$O7m9I68r.njGrq.4mJXKJenX/m5fyspVvQ/nCIUjpg4Z5cJmcT/YC',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:52','2026-04-26 13:01:52',NULL,NULL,NULL,NULL),
(20,'User 20','user20@gmail.com','2026-04-26 13:01:53','$2y$12$J8vGZe9dbrj/iGJtVxgB/ueYyXD9sqRewE3fD6aYuV8JnltHp9SkG',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:53','2026-04-26 13:01:53',NULL,NULL,NULL,NULL),
(21,'User 21','user21@gmail.com','2026-04-26 13:01:53','$2y$12$LGnyQ0nYvk5T2FoFy.p3zuTVLgruYYtV5k5tM1/uAOkrTQTQA0tN6',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:53','2026-04-26 13:01:53',NULL,NULL,NULL,NULL),
(22,'User 22','user22@gmail.com','2026-04-26 13:01:53','$2y$12$k1F2o8knyq6.jF2UNeFteeE6pfuXJAeXZ9GrLi3ocrZmmIjESUJHC',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:53','2026-04-26 13:01:53',NULL,NULL,NULL,NULL),
(23,'User 23','user23@gmail.com','2026-04-26 13:01:53','$2y$12$EHoM.VCuXdMygRh4DOHCO.7RGagacSZRTKNVuVvHeme5ER1I0HCfC',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:53','2026-04-26 13:01:53',NULL,NULL,NULL,NULL),
(24,'User 24','user24@gmail.com','2026-04-26 13:01:53','$2y$12$19BT9hKonWf3jXnozrZzZebecvHWi.4dZNFgrF7lMdiHK9du5bzDS',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:53','2026-04-26 13:01:53',NULL,NULL,NULL,NULL),
(25,'User 25','user25@gmail.com','2026-04-26 13:01:54','$2y$12$SNz9d8.QQm2vGIIFPvjBpu7UFv1vwRcA2EkcaqkYIQ/.jtttdEan2',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:54','2026-04-26 13:01:54',NULL,NULL,NULL,NULL),
(26,'User 26','user26@gmail.com','2026-04-26 13:01:54','$2y$12$LKRhy5MoQ6wNgOa0Ln9RWOoxYxgaXV5KL6h9QgjF98sBkPFYNW2vu',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:54','2026-04-26 13:01:54',NULL,NULL,NULL,NULL),
(27,'User 27','user27@gmail.com','2026-04-26 13:01:54','$2y$12$v.7aZKudd5i.VYOwjDryEelGTw4WCBG5nc7Xe7GuhEPAnWp2MUGee',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:54','2026-04-26 13:01:54',NULL,NULL,NULL,NULL),
(28,'User 28','user28@gmail.com','2026-04-26 13:01:54','$2y$12$rMAnj3dqjM.FgFA2PiVVwu6PlBWTjKhikgIKsPAGpitmZlySYPCbW',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:54','2026-04-26 13:01:54',NULL,NULL,NULL,NULL),
(29,'User 29','user29@gmail.com','2026-04-26 13:01:55','$2y$12$Afn5yBus2u9QCaRJFU8Si.df.jKhLEfcerkG2zl71F/YcDCW3suqe',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:55','2026-04-26 13:01:55',NULL,NULL,NULL,NULL),
(30,'User 30','user30@gmail.com','2026-04-26 13:01:55','$2y$12$tDkRKyrdIeOthXcCqhoQz.Skag0.liVkmLVQakohRAdn/VfylJAAq',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:55','2026-04-26 13:01:55',NULL,NULL,NULL,NULL),
(31,'User 31','user31@gmail.com','2026-04-26 13:01:55','$2y$12$tPFuB3oV7PHG.KqmcQiGp.noYO2PdTxPNMs1YuTZKQeIgIo2XIF2u',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:55','2026-04-26 13:01:55',NULL,NULL,NULL,NULL),
(32,'User 32','user32@gmail.com','2026-04-26 13:01:55','$2y$12$weNBFFKHNwpvtCFstLnhv.m4OygctEnqiFVVtXZThq35Jcx9avoq6',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:55','2026-04-26 13:01:55',NULL,NULL,NULL,NULL),
(33,'User 33','user33@gmail.com','2026-04-26 13:01:56','$2y$12$9ztTh/x2rqi6UQgJJhBuO.Ide2mZ06TkgmSm3x3PJG56euy.NSGXC',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:56','2026-04-26 13:01:56',NULL,NULL,NULL,NULL),
(34,'User 34','user34@gmail.com','2026-04-26 13:01:56','$2y$12$G8iUjZdM6kW8uy15Xij9qu8GC1Cl9O4PFtKGZobGKWHfgA9NKkyKe',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:56','2026-04-26 13:01:56',NULL,NULL,NULL,NULL),
(35,'User 35','user35@gmail.com','2026-04-26 13:01:56','$2y$12$3BnNrQip6bg1uB.0IJ8gBu9wVbZbP/PH1tmrjN.WaEvVDyhoDKZzW',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:56','2026-04-26 13:01:56',NULL,NULL,NULL,NULL),
(36,'User 36','user36@gmail.com','2026-04-26 13:01:56','$2y$12$W1J1ga7S8ORv7.NynUnL8.ljuoFlR8edP.J8I.BYgb.BI0Q6hILuq',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:56','2026-04-26 13:01:56',NULL,NULL,NULL,NULL),
(37,'User 37','user37@gmail.com','2026-04-26 13:01:56','$2y$12$2yhung.GqXnX.KaZxLnpSuABBDd/n25yRlaoLpoIToVMmWS1bIwzS',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:56','2026-04-26 13:01:56',NULL,NULL,NULL,NULL),
(38,'User 38','user38@gmail.com','2026-04-26 13:01:57','$2y$12$aqrMfMJPBFZT2d/u5J1KiOZM0G2krthCPfOI0MRI6jee7Yy7D2KFa',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:57','2026-04-26 13:01:57',NULL,NULL,NULL,NULL),
(39,'User 39','user39@gmail.com','2026-04-26 13:01:57','$2y$12$0OSm3tFp/sQLGX01E/V5Oe4MM.7zSPiUAwPycQLKQ4slyPg9nxcBW',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:57','2026-04-26 13:01:57',NULL,NULL,NULL,NULL),
(40,'User 40','user40@gmail.com','2026-04-26 13:01:57','$2y$12$qoSSu9LfSX.StITmC3QbCuW7R33OvOmsS4CYQ8Zb/LpJMCENQHFqq',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:57','2026-04-26 13:01:57',NULL,NULL,NULL,NULL),
(41,'User 41','user41@gmail.com','2026-04-26 13:01:57','$2y$12$iVB5TUeN6o04uAHwwe1g0.z79U8yAAMboRIQf4I3efzq08kAFSAxa',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:57','2026-04-26 13:01:57',NULL,NULL,NULL,NULL),
(42,'User 42','user42@gmail.com','2026-04-26 13:01:58','$2y$12$Op/L7KTf5zz1P4qMzjQ8beMuvteAlg5CPgDXu5AUcWLsLBRmwqoka',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:58','2026-04-26 13:01:58',NULL,NULL,NULL,NULL),
(43,'User 43','user43@gmail.com','2026-04-26 13:01:58','$2y$12$Xk0HFoXdYesgrJoG/2s1Be89xdNm7ImYwYnLjjszIzPeGuDOcWYCm',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:58','2026-04-26 13:01:58',NULL,NULL,NULL,NULL),
(44,'User 44','user44@gmail.com','2026-04-26 13:01:58','$2y$12$3XuE4xDEqNoGH2RQVQo1p.3sHPMDJVpUVbXg//3gvJ3VefWNaeH3a',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:58','2026-04-26 13:01:58',NULL,NULL,NULL,NULL),
(45,'User 45','user45@gmail.com','2026-04-26 13:01:58','$2y$12$4bLiiRbOVwPpqExjEgvZ3eTO8r3OskhcmjLa8mMyuhbKFHWbQT13a',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:58','2026-04-26 13:01:58',NULL,NULL,NULL,NULL),
(46,'User 46','user46@gmail.com','2026-04-26 13:01:59','$2y$12$Ygur4MVexNtm1UmsbZvRTuet0bJK4h2.o1Gcw1yBYC.rKBUkxaHo2',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:59','2026-04-26 13:01:59',NULL,NULL,NULL,NULL),
(47,'User 47','user47@gmail.com','2026-04-26 13:01:59','$2y$12$lXwQpJtKSaewMQZow3erv.wi6zGCJQn3rlI0tQWgsVdoMu1z8HBJ.',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:59','2026-04-26 13:01:59',NULL,NULL,NULL,NULL),
(48,'User 48','user48@gmail.com','2026-04-26 13:01:59','$2y$12$gVlsCy2fuEfOj28qRK5PHu0oK23V5EDCvdIusYmAsvqJqlAT4OL0m',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:59','2026-04-26 13:01:59',NULL,NULL,NULL,NULL),
(49,'User 49','user49@gmail.com','2026-04-26 13:01:59','$2y$12$cLnwEkX0argN6hSQq3gER.c9O5myV/4M5zMe5lA9CIjpMsMju69PO',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:59','2026-04-26 13:01:59',NULL,NULL,NULL,NULL),
(50,'User 50','user50@gmail.com','2026-04-26 13:01:59','$2y$12$NOpdaHE558havSYznHMKAuD0FNj6d0X/EDwDJRK/D79xReFWk5b1G',NULL,NULL,NULL,NULL,0,'2026-04-26 13:01:59','2026-04-26 13:01:59',NULL,NULL,NULL,NULL),
(51,'Rendy','rendy@gmail.com','2026-04-26 13:02:00','$2y$12$tCXfkUt1YDDyBdV43oUON.9lSPE8wBIZe8TXE/BiAXNKiACDJVS0u',NULL,NULL,NULL,NULL,1,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL),
(52,'Edo','edo@gmail.com','2026-04-26 13:02:00','$2y$12$J4D8D.xQfDPm82ngfXoyzO7T5x7U/4HQ4QBEkbM0kpsaiUgKXf8Z2',NULL,NULL,NULL,NULL,1,'2026-04-26 13:02:00','2026-04-26 13:02:00',NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
