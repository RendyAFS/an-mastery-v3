/*
SQLyog Ultimate v13.1.1 (32 bit)
MySQL - 10.11.16-MariaDB-log : Database - an_mastery
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`an_mastery` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;

USE `an_mastery`;

/*Table structure for table `bill_supplier_details` */

DROP TABLE IF EXISTS `bill_supplier_details`;

CREATE TABLE `bill_supplier_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bill_supplier_id` bigint(20) unsigned DEFAULT NULL,
  `sablon_detail_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_supplier_details_bill_supplier_id_foreign` (`bill_supplier_id`),
  KEY `bill_supplier_details_sablon_detail_id_foreign` (`sablon_detail_id`),
  CONSTRAINT `bill_supplier_details_bill_supplier_id_foreign` FOREIGN KEY (`bill_supplier_id`) REFERENCES `bill_suppliers` (`id`),
  CONSTRAINT `bill_supplier_details_sablon_detail_id_foreign` FOREIGN KEY (`sablon_detail_id`) REFERENCES `sablon_details` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bill_supplier_details` */

/*Table structure for table `bill_suppliers` */

DROP TABLE IF EXISTS `bill_suppliers`;

CREATE TABLE `bill_suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `price_supplier_id` bigint(20) unsigned DEFAULT NULL,
  `sablon_id` bigint(20) unsigned DEFAULT NULL,
  `total_fee` int(11) DEFAULT NULL,
  `date_bill` date DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_suppliers_supplier_id_foreign` (`supplier_id`),
  KEY `bill_suppliers_price_supplier_id_foreign` (`price_supplier_id`),
  KEY `bill_suppliers_sablon_id_foreign` (`sablon_id`),
  CONSTRAINT `bill_suppliers_price_supplier_id_foreign` FOREIGN KEY (`price_supplier_id`) REFERENCES `price_suppliers` (`id`),
  CONSTRAINT `bill_suppliers_sablon_id_foreign` FOREIGN KEY (`sablon_id`) REFERENCES `sablons` (`id`),
  CONSTRAINT `bill_suppliers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bill_suppliers` */

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

insert  into `cache`(`key`,`value`,`expiration`) values 
('laravel-cache-435347697ac176274c06c637e5d6b4d3','i:1;',1782177321),
('laravel-cache-435347697ac176274c06c637e5d6b4d3:timer','i:1782177321;',1782177321),
('laravel-cache-lv:v3.24.0:file:0c401bb7-laravel-2026-06-23.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782800207),
('laravel-cache-lv:v3.24.0:file:1199d6c9-laravel-2026-05-16.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782783615),
('laravel-cache-lv:v3.24.0:file:246f5e45-laravel.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782783615),
('laravel-cache-lv:v3.24.0:file:486268b3-laravel-2026-06-22.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782783615),
('laravel-cache-lv:v3.24.0:file:55d1c80d-laravel-2026-05-19.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782783615),
('laravel-cache-lv:v3.24.0:file:59d98ac4-laravel-2026-05-15.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782783615),
('laravel-cache-lv:v3.24.0:file:7639766d-laravel-2026-06-09.log:metadata','a:1:{s:4:\"type\";s:7:\"laravel\";}',1782783615),
('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:8:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"d\";s:7:\"menu_id\";s:1:\"r\";s:5:\"roles\";s:1:\"l\";s:10:\"created_by\";s:1:\"m\";s:10:\"updated_by\";s:1:\"o\";s:10:\"deleted_by\";}s:11:\"permissions\";a:97:{i:0;a:5:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"dashboard.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:1;s:1:\"r\";a:1:{i:0;i:2;}}i:1;a:5:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"users.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:2;a:5:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"users.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:3;a:5:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"users.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:4;a:5:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"users.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:5;a:5:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"users.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:6;a:5:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"users.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:7;a:5:{s:1:\"a\";i:8;s:1:\"b\";s:13:\"users.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:8;a:5:{s:1:\"a\";i:9;s:1:\"b\";s:17:\"users.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:3;s:1:\"r\";a:1:{i:0;i:2;}}i:9;a:5:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"roles.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:10;a:5:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"roles.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:11;a:5:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"roles.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:12;a:5:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"roles.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:13;a:5:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"roles.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:14;a:5:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"roles.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:15;a:5:{s:1:\"a\";i:16;s:1:\"b\";s:13:\"roles.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:16;a:5:{s:1:\"a\";i:17;s:1:\"b\";s:17:\"roles.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:4;s:1:\"r\";a:1:{i:0;i:2;}}i:17;a:5:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"suppliers.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:18;a:5:{s:1:\"a\";i:19;s:1:\"b\";s:16:\"suppliers.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:19;a:5:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"suppliers.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:20;a:5:{s:1:\"a\";i:21;s:1:\"b\";s:14:\"suppliers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:21;a:5:{s:1:\"a\";i:22;s:1:\"b\";s:16:\"suppliers.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:22;a:5:{s:1:\"a\";i:23;s:1:\"b\";s:16:\"suppliers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:23;a:5:{s:1:\"a\";i:24;s:1:\"b\";s:17:\"suppliers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:24;a:5:{s:1:\"a\";i:25;s:1:\"b\";s:21:\"suppliers.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:6;s:1:\"r\";a:1:{i:0;i:2;}}i:25;a:5:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"employees.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:26;a:5:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"employees.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:27;a:5:{s:1:\"a\";i:28;s:1:\"b\";s:14:\"employees.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:28;a:5:{s:1:\"a\";i:29;s:1:\"b\";s:14:\"employees.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:29;a:5:{s:1:\"a\";i:30;s:1:\"b\";s:16:\"employees.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:30;a:5:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"employees.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:31;a:5:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"employees.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:32;a:5:{s:1:\"a\";i:33;s:1:\"b\";s:21:\"employees.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:7;s:1:\"r\";a:1:{i:0;i:2;}}i:33;a:5:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"image-fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:34;a:5:{s:1:\"a\";i:35;s:1:\"b\";s:20:\"image-fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:35;a:5:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"image-fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:36;a:5:{s:1:\"a\";i:37;s:1:\"b\";s:18:\"image-fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:37;a:5:{s:1:\"a\";i:38;s:1:\"b\";s:20:\"image-fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:38;a:5:{s:1:\"a\";i:39;s:1:\"b\";s:20:\"image-fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:39;a:5:{s:1:\"a\";i:40;s:1:\"b\";s:21:\"image-fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:40;a:5:{s:1:\"a\";i:41;s:1:\"b\";s:25:\"image-fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:8;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:41;a:5:{s:1:\"a\";i:42;s:1:\"b\";s:18:\"color-fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:42;a:5:{s:1:\"a\";i:43;s:1:\"b\";s:20:\"color-fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:43;a:5:{s:1:\"a\";i:44;s:1:\"b\";s:18:\"color-fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:44;a:5:{s:1:\"a\";i:45;s:1:\"b\";s:18:\"color-fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:45;a:5:{s:1:\"a\";i:46;s:1:\"b\";s:20:\"color-fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:46;a:5:{s:1:\"a\";i:47;s:1:\"b\";s:20:\"color-fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:47;a:5:{s:1:\"a\";i:48;s:1:\"b\";s:21:\"color-fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:48;a:5:{s:1:\"a\";i:49;s:1:\"b\";s:25:\"color-fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:9;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:49;a:5:{s:1:\"a\";i:50;s:1:\"b\";s:17:\"type-fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:50;a:5:{s:1:\"a\";i:51;s:1:\"b\";s:19:\"type-fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:51;a:5:{s:1:\"a\";i:52;s:1:\"b\";s:17:\"type-fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:52;a:5:{s:1:\"a\";i:53;s:1:\"b\";s:17:\"type-fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:53;a:5:{s:1:\"a\";i:54;s:1:\"b\";s:19:\"type-fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:54;a:5:{s:1:\"a\";i:55;s:1:\"b\";s:19:\"type-fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:55;a:5:{s:1:\"a\";i:56;s:1:\"b\";s:20:\"type-fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:56;a:5:{s:1:\"a\";i:57;s:1:\"b\";s:24:\"type-fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:10;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:57;a:5:{s:1:\"a\";i:58;s:1:\"b\";s:16:\"type-colors.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:58;a:5:{s:1:\"a\";i:59;s:1:\"b\";s:18:\"type-colors.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:59;a:5:{s:1:\"a\";i:60;s:1:\"b\";s:16:\"type-colors.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:60;a:5:{s:1:\"a\";i:61;s:1:\"b\";s:16:\"type-colors.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:61;a:5:{s:1:\"a\";i:62;s:1:\"b\";s:18:\"type-colors.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:62;a:5:{s:1:\"a\";i:63;s:1:\"b\";s:18:\"type-colors.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:63;a:5:{s:1:\"a\";i:64;s:1:\"b\";s:19:\"type-colors.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:64;a:5:{s:1:\"a\";i:65;s:1:\"b\";s:23:\"type-colors.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:11;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:65;a:5:{s:1:\"a\";i:66;s:1:\"b\";s:20:\"price-suppliers.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:66;a:5:{s:1:\"a\";i:67;s:1:\"b\";s:22:\"price-suppliers.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:67;a:5:{s:1:\"a\";i:68;s:1:\"b\";s:20:\"price-suppliers.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:68;a:5:{s:1:\"a\";i:69;s:1:\"b\";s:20:\"price-suppliers.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:69;a:5:{s:1:\"a\";i:70;s:1:\"b\";s:22:\"price-suppliers.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:70;a:5:{s:1:\"a\";i:71;s:1:\"b\";s:22:\"price-suppliers.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:71;a:5:{s:1:\"a\";i:72;s:1:\"b\";s:23:\"price-suppliers.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:72;a:5:{s:1:\"a\";i:73;s:1:\"b\";s:27:\"price-suppliers.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:12;s:1:\"r\";a:1:{i:0;i:2;}}i:73;a:5:{s:1:\"a\";i:74;s:1:\"b\";s:20:\"price-employees.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:74;a:5:{s:1:\"a\";i:75;s:1:\"b\";s:22:\"price-employees.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:75;a:5:{s:1:\"a\";i:76;s:1:\"b\";s:20:\"price-employees.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:76;a:5:{s:1:\"a\";i:77;s:1:\"b\";s:20:\"price-employees.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:77;a:5:{s:1:\"a\";i:78;s:1:\"b\";s:22:\"price-employees.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:78;a:5:{s:1:\"a\";i:79;s:1:\"b\";s:22:\"price-employees.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:79;a:5:{s:1:\"a\";i:80;s:1:\"b\";s:23:\"price-employees.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:80;a:5:{s:1:\"a\";i:81;s:1:\"b\";s:27:\"price-employees.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:13;s:1:\"r\";a:1:{i:0;i:2;}}i:81;a:5:{s:1:\"a\";i:82;s:1:\"b\";s:14:\"presences.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:82;a:5:{s:1:\"a\";i:83;s:1:\"b\";s:16:\"presences.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:83;a:5:{s:1:\"a\";i:84;s:1:\"b\";s:14:\"presences.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:84;a:5:{s:1:\"a\";i:85;s:1:\"b\";s:14:\"presences.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:85;a:5:{s:1:\"a\";i:86;s:1:\"b\";s:16:\"presences.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:86;a:5:{s:1:\"a\";i:87;s:1:\"b\";s:16:\"presences.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:87;a:5:{s:1:\"a\";i:88;s:1:\"b\";s:17:\"presences.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:88;a:5:{s:1:\"a\";i:89;s:1:\"b\";s:21:\"presences.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:14;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:89;a:5:{s:1:\"a\";i:90;s:1:\"b\";s:12:\"fabrics.view\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:90;a:5:{s:1:\"a\";i:91;s:1:\"b\";s:14:\"fabrics.create\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:91;a:5:{s:1:\"a\";i:92;s:1:\"b\";s:12:\"fabrics.read\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:92;a:5:{s:1:\"a\";i:93;s:1:\"b\";s:12:\"fabrics.edit\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:93;a:5:{s:1:\"a\";i:94;s:1:\"b\";s:14:\"fabrics.update\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:94;a:5:{s:1:\"a\";i:95;s:1:\"b\";s:14:\"fabrics.delete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:95;a:5:{s:1:\"a\";i:96;s:1:\"b\";s:15:\"fabrics.restore\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:96;a:5:{s:1:\"a\";i:97;s:1:\"b\";s:19:\"fabrics.forceDelete\";s:1:\"c\";s:3:\"web\";s:1:\"d\";i:18;s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}}s:5:\"roles\";a:2:{i:0;a:6:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";s:1:\"l\";N;s:1:\"m\";N;s:1:\"o\";N;}i:1;a:6:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";s:1:\"l\";N;s:1:\"m\";N;s:1:\"o\";N;}}}',1782274200);

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `color_fabrics` */

DROP TABLE IF EXISTS `color_fabrics`;

CREATE TABLE `color_fabrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code_color` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `color_fabrics` */

insert  into `color_fabrics`(`id`,`name`,`code_color`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Jambon','#fbcbf5','tes','2026-04-25 09:16:51','2026-05-13 15:08:33',51,51,NULL,NULL),
(2,'Krem','#dbcfb8',NULL,'2026-06-23 09:59:33','2026-06-23 10:00:05',51,51,NULL,NULL),
(3,'Blewah','#d3b573',NULL,'2026-06-23 09:59:55','2026-06-23 09:59:55',51,51,NULL,NULL),
(4,'Biru','#92b9f7',NULL,'2026-06-23 10:00:44','2026-06-23 10:00:44',51,51,NULL,NULL),
(5,'Merah','#f45252',NULL,'2026-06-23 10:00:53','2026-06-23 10:00:53',51,51,NULL,NULL),
(6,'Kuning','#f1e909',NULL,'2026-06-23 10:01:04','2026-06-23 10:01:04',51,51,NULL,NULL),
(7,'Coklat','#a38552',NULL,'2026-06-23 10:01:19','2026-06-23 10:01:19',51,51,NULL,NULL);

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `employees` */

insert  into `employees`(`id`,`name`,`address`,`contact`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Agung','tulungagung','08123456780',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(2,'Mambar','tulungagung','08123456781',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(3,'Ipin','tulungagung','08123456782',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(4,'Empi','tulungagung','08123456783',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(5,'Rizal','tulungagung','08123456784',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(6,'Agus','tulungagung','08123456785',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(7,'Harno','tulungagung','08123456786',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(8,'RikoA','tulungagung','08123456787',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(9,'Mujib','tulungagung','08123456788',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(10,'Irwan','tulungagung','08123456789',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(11,'Yoga','tulungagung','081234567810',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(12,'Adi','tulungagung','081234567811',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(13,'Viki','tulungagung','081234567812',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(14,'Arip','tulungagung','081234567813',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(15,'Ayet','tulungagung','081234567814',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(16,'Hegler','tulungagung','081234567815',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(17,'Dadang','tulungagung','081234567816',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(18,'RikoB','tulungagung','081234567817',NULL,'2026-04-03 08:32:33','2026-04-10 10:54:37',NULL,51,NULL,NULL);

/*Table structure for table `fabric_details` */

DROP TABLE IF EXISTS `fabric_details`;

CREATE TABLE `fabric_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fabric_id` bigint(20) unsigned DEFAULT NULL,
  `color_fabric_id` bigint(20) unsigned DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fabric_details_fabric_id_foreign` (`fabric_id`),
  KEY `fabric_details_color_fabric_id_foreign` (`color_fabric_id`),
  CONSTRAINT `fabric_details_color_fabric_id_foreign` FOREIGN KEY (`color_fabric_id`) REFERENCES `color_fabrics` (`id`),
  CONSTRAINT `fabric_details_fabric_id_foreign` FOREIGN KEY (`fabric_id`) REFERENCES `fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `fabric_details` */

insert  into `fabric_details`(`id`,`fabric_id`,`color_fabric_id`,`stock`,`notes`,`created_at`,`updated_at`) values 
(1,2,1,11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:49'),
(2,2,2,11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:49'),
(3,2,3,11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:49'),
(4,2,4,11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:49'),
(5,3,1,12,NULL,'2026-06-23 10:35:34','2026-06-23 10:50:54'),
(6,3,2,12,NULL,'2026-06-23 10:35:34','2026-06-23 10:50:54'),
(7,3,3,10,'kurangan blewah besok','2026-06-23 10:35:34','2026-06-23 10:50:54'),
(8,3,4,12,'kurangan biru besok','2026-06-23 10:35:34','2026-06-23 10:56:17'),
(9,4,5,4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(10,4,4,4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(11,4,6,4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(12,4,7,4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50');

/*Table structure for table `fabrics` */

DROP TABLE IF EXISTS `fabrics`;

CREATE TABLE `fabrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL COMMENT 'auto *example {name_supplier}{timestamp()} SUNAR17829012',
  `seri` int(11) DEFAULT NULL,
  `stock_total` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fabrics_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `fabrics_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `fabrics` */

insert  into `fabrics`(`id`,`supplier_id`,`code`,`seri`,`stock_total`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(2,1,'SUNAR-20260623102103',4,44,'tes','2026-06-23 10:21:03','2026-06-23 10:21:49',51,51,NULL,NULL),
(3,2,'YADI-20260623103534',4,46,'ada kurangan','2026-06-23 10:35:34','2026-06-23 10:56:17',51,51,NULL,NULL),
(4,4,'SANTOSO-20260623105150',4,16,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50',51,51,NULL,NULL);

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `history_stocks` */

DROP TABLE IF EXISTS `history_stocks`;

CREATE TABLE `history_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fabric_detail_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'enum StatusSablonEnum',
  `total` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `history_stocks_fabric_detail_id_foreign` (`fabric_detail_id`),
  CONSTRAINT `history_stocks_fabric_detail_id_foreign` FOREIGN KEY (`fabric_detail_id`) REFERENCES `fabric_details` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `history_stocks` */

insert  into `history_stocks`(`id`,`fabric_detail_id`,`status`,`total`,`notes`,`created_at`,`updated_at`) values 
(1,1,'IN',11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:03'),
(2,2,'IN',11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:03'),
(3,3,'IN',11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:03'),
(4,4,'IN',11,NULL,'2026-06-23 10:21:03','2026-06-23 10:21:03'),
(5,5,'IN',12,NULL,'2026-06-23 10:35:34','2026-06-23 10:35:34'),
(6,6,'IN',12,NULL,'2026-06-23 10:35:34','2026-06-23 10:35:34'),
(7,7,'IN',10,NULL,'2026-06-23 10:35:34','2026-06-23 10:35:34'),
(8,8,'IN',9,NULL,'2026-06-23 10:35:34','2026-06-23 10:35:34'),
(9,9,'IN',4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(10,10,'IN',4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(11,11,'IN',4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(12,12,'IN',4,NULL,'2026-06-23 10:51:50','2026-06-23 10:51:50'),
(13,8,'ADJUSTMENT',3,'kurangan biru besok','2026-06-23 10:56:17','2026-06-23 10:56:17');

/*Table structure for table `image_fabrics` */

DROP TABLE IF EXISTS `image_fabrics`;

CREATE TABLE `image_fabrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `image_fabrics` */

insert  into `image_fabrics`(`id`,`name`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Robot',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(2,'Love',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(3,'Kipas',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(4,'Kelinci',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(5,'Beruang',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(6,'Angry Birds',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(7,'Bulan Bintang',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(8,'Flamboyan',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(9,'Kenangan',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(10,'Bawang',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(11,'Cempaka',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(12,'Raflesia',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(13,'Lotus',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(14,'Adenium',NULL,'2026-04-03 08:32:33','2026-04-10 10:54:01',NULL,51,NULL,NULL),
(15,'Rosela',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(16,'Seruni',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(17,'Anggrek',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(18,'Semanggi',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(19,'Teratai',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(20,'Nusa Indah',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(21,'Matahari',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(22,'Bougenvil',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(23,'Aster',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(24,'Jasmin',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(25,'Dahlia',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(26,'Melati',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(27,'Mawar',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(28,'Sepatu',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(29,'Kuncup',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(30,'Sakura',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(31,'Mahkota',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(32,'Kamboja',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(33,'Lavender',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL);

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `media` */

insert  into `media`(`id`,`model_type`,`model_id`,`uuid`,`collection_name`,`name`,`file_name`,`mime_type`,`disk`,`conversions_disk`,`size`,`manipulations`,`custom_properties`,`generated_conversions`,`responsive_images`,`order_column`,`created_at`,`updated_at`) values 
(3,'App\\Models\\User',51,'ee3b5367-11f8-4487-b3aa-121f13933b43','user-profile','7CatsT9YM9e2wPJsCj71iu3breN3etcR5egPB9xV','45233532-28d8-4969-bc01-19b37d9066a4.png','image/jpeg','public','public',554410,'[]','[]','[]','[]',1,'2026-06-09 08:27:14','2026-06-09 08:27:14');

/*Table structure for table `menu_permissions` */

DROP TABLE IF EXISTS `menu_permissions`;

CREATE TABLE `menu_permissions` (
  `menu_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  KEY `menu_permissions_menu_id_foreign` (`menu_id`),
  KEY `menu_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `menu_permissions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  CONSTRAINT `menu_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `menu_permissions` */

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `menus` */

insert  into `menus`(`id`,`parent_id`,`name`,`icon`,`url`,`sort_order`,`is_active`,`created_at`,`updated_at`) values 
(1,NULL,'Dashboard','home','/dashboard',1,1,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(2,NULL,'Access Management','shield','#access-management',2,1,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(3,2,'Users',NULL,'/users',1,1,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(4,2,'Roles',NULL,'/roles',2,1,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(6,15,'Suppliers',NULL,'/suppliers',1,1,'2026-04-03 08:32:23','2026-06-22 13:20:07'),
(7,15,'Employees',NULL,'/employees',2,1,'2026-04-03 08:32:23','2026-06-22 13:20:07'),
(8,16,'Image Fabrics',NULL,'/image-fabrics',1,1,'2026-04-03 08:32:23','2026-06-22 13:20:07'),
(9,16,'Color Fabrics',NULL,'/color-fabrics',2,1,'2026-04-25 08:41:24','2026-06-22 13:20:07'),
(10,16,'Type Fabrics',NULL,'/type-fabrics',3,1,'2026-04-25 08:41:24','2026-06-22 13:20:07'),
(11,16,'Type Colors',NULL,'/type-colors',4,1,'2026-05-12 13:29:08','2026-06-22 13:20:07'),
(12,17,'Price Supplier',NULL,'/price-suppliers',1,1,'2026-05-13 08:33:16','2026-06-22 13:20:07'),
(13,17,'Price Employee',NULL,'/price-employees',2,1,'2026-06-22 08:16:34','2026-06-22 13:20:07'),
(14,NULL,'Employee Presence','calendar-check-2','/presences',6,1,'2026-06-22 09:02:03','2026-06-22 13:20:07'),
(15,NULL,'People','users','#people',3,1,'2026-06-22 13:20:07','2026-06-22 13:20:07'),
(16,NULL,'Fabric Attribute','layers','#fabric-attribute',4,1,'2026-06-22 13:20:07','2026-06-22 13:20:07'),
(17,NULL,'Pricing','dollar-sign','#pricing',5,1,'2026-06-22 13:20:07','2026-06-22 13:20:07'),
(18,NULL,'Inventory Fabric','package','/fabrics',7,1,'2026-06-23 08:43:33','2026-06-23 08:43:33');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(30,'2026_06_23_081129_add_fabric_detail_id_to_sablons',3),
(31,'2026_06_23_090019_add_seri_to_fabrics',4),
(32,'2026_06_23_102020_add_timestamp_to_fabric_details',5),
(33,'2026_06_23_140140_add_some_column_to_sablon_employee_details',6);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values 
(1,'App\\Models\\User',51),
(2,'App\\Models\\User',52),
(2,'App\\Models\\User',53),
(3,'App\\Models\\User',54),
(3,'App\\Models\\User',55);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `menu_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`),
  KEY `permissions_menu_id_foreign` (`menu_id`),
  CONSTRAINT `permissions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`menu_id`,`created_at`,`updated_at`) values 
(1,'dashboard.view','web',1,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(2,'users.view','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(3,'users.create','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(4,'users.read','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(5,'users.edit','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(6,'users.update','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(7,'users.delete','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(8,'users.restore','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(9,'users.forceDelete','web',3,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(10,'roles.view','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(11,'roles.create','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(12,'roles.read','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(13,'roles.edit','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(14,'roles.update','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(15,'roles.delete','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(16,'roles.restore','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(17,'roles.forceDelete','web',4,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(18,'suppliers.view','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(19,'suppliers.create','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(20,'suppliers.read','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(21,'suppliers.edit','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(22,'suppliers.update','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(23,'suppliers.delete','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(24,'suppliers.restore','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(25,'suppliers.forceDelete','web',6,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(26,'employees.view','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(27,'employees.create','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(28,'employees.read','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(29,'employees.edit','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(30,'employees.update','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(31,'employees.delete','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(32,'employees.restore','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(33,'employees.forceDelete','web',7,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(34,'image-fabrics.view','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(35,'image-fabrics.create','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(36,'image-fabrics.read','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(37,'image-fabrics.edit','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(38,'image-fabrics.update','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(39,'image-fabrics.delete','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(40,'image-fabrics.restore','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(41,'image-fabrics.forceDelete','web',8,'2026-04-03 08:32:23','2026-04-03 08:32:23'),
(42,'color-fabrics.view','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(43,'color-fabrics.create','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(44,'color-fabrics.read','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(45,'color-fabrics.edit','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(46,'color-fabrics.update','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(47,'color-fabrics.delete','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(48,'color-fabrics.restore','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(49,'color-fabrics.forceDelete','web',9,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(50,'type-fabrics.view','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(51,'type-fabrics.create','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(52,'type-fabrics.read','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(53,'type-fabrics.edit','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(54,'type-fabrics.update','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(55,'type-fabrics.delete','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(56,'type-fabrics.restore','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(57,'type-fabrics.forceDelete','web',10,'2026-04-25 08:41:24','2026-04-25 08:41:24'),
(58,'type-colors.view','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(59,'type-colors.create','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(60,'type-colors.read','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(61,'type-colors.edit','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(62,'type-colors.update','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(63,'type-colors.delete','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(64,'type-colors.restore','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(65,'type-colors.forceDelete','web',11,'2026-05-12 13:29:08','2026-05-12 13:29:08'),
(66,'price-suppliers.view','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(67,'price-suppliers.create','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(68,'price-suppliers.read','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(69,'price-suppliers.edit','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(70,'price-suppliers.update','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(71,'price-suppliers.delete','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(72,'price-suppliers.restore','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(73,'price-suppliers.forceDelete','web',12,'2026-05-13 08:33:16','2026-05-13 08:33:16'),
(74,'price-employees.view','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(75,'price-employees.create','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(76,'price-employees.read','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(77,'price-employees.edit','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(78,'price-employees.update','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(79,'price-employees.delete','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(80,'price-employees.restore','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(81,'price-employees.forceDelete','web',13,'2026-06-22 08:16:34','2026-06-22 08:16:34'),
(82,'presences.view','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(83,'presences.create','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(84,'presences.read','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(85,'presences.edit','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(86,'presences.update','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(87,'presences.delete','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(88,'presences.restore','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(89,'presences.forceDelete','web',14,'2026-06-22 09:02:03','2026-06-22 09:02:03'),
(90,'fabrics.view','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(91,'fabrics.create','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(92,'fabrics.read','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(93,'fabrics.edit','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(94,'fabrics.update','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(95,'fabrics.delete','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(96,'fabrics.restore','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33'),
(97,'fabrics.forceDelete','web',18,'2026-06-23 08:43:33','2026-06-23 08:43:33');

/*Table structure for table `presences` */

DROP TABLE IF EXISTS `presences`;

CREATE TABLE `presences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `week_of` date DEFAULT NULL,
  `monday` int(11) DEFAULT NULL,
  `tuesday` int(11) DEFAULT NULL,
  `wednesday` int(11) DEFAULT NULL,
  `thursday` int(11) DEFAULT NULL,
  `friday` int(11) DEFAULT NULL,
  `saturday` int(11) DEFAULT NULL,
  `sunday` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presences_employee_id_foreign` (`employee_id`),
  CONSTRAINT `presences_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `presences` */

insert  into `presences`(`id`,`employee_id`,`week_of`,`monday`,`tuesday`,`wednesday`,`thursday`,`friday`,`saturday`,`sunday`,`total`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(2,12,'2026-06-22',10000,10000,10000,10000,10000,10000,10000,70000,'tes','2026-06-22 10:19:59','2026-06-22 10:20:36',51,51,NULL,NULL);

/*Table structure for table `price_employees` */

DROP TABLE IF EXISTS `price_employees`;

CREATE TABLE `price_employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_fabric_id` bigint(20) unsigned DEFAULT NULL,
  `type_color_id` bigint(20) unsigned DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_employees_type_fabric_id_foreign` (`type_fabric_id`),
  KEY `price_employees_type_color_id_foreign` (`type_color_id`),
  CONSTRAINT `price_employees_type_color_id_foreign` FOREIGN KEY (`type_color_id`) REFERENCES `type_colors` (`id`),
  CONSTRAINT `price_employees_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `price_employees` */

insert  into `price_employees`(`id`,`type_fabric_id`,`type_color_id`,`price`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,1,123111,NULL,'2026-06-22 08:17:09','2026-06-22 10:38:58',51,51,NULL,NULL),
(2,1,2,345,NULL,'2026-06-22 08:17:17','2026-06-22 08:17:17',51,51,NULL,NULL),
(3,2,3,222,NULL,'2026-06-22 08:17:26','2026-06-22 08:17:26',51,51,NULL,NULL);

/*Table structure for table `price_suppliers` */

DROP TABLE IF EXISTS `price_suppliers`;

CREATE TABLE `price_suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `type_fabric_id` bigint(20) unsigned DEFAULT NULL,
  `type_color_id` bigint(20) unsigned DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_suppliers_supplier_id_foreign` (`supplier_id`),
  KEY `price_suppliers_type_fabric_id_foreign` (`type_fabric_id`),
  KEY `price_suppliers_type_color_id_foreign` (`type_color_id`),
  CONSTRAINT `price_suppliers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `price_suppliers_type_color_id_foreign` FOREIGN KEY (`type_color_id`) REFERENCES `type_colors` (`id`),
  CONSTRAINT `price_suppliers_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `price_suppliers` */

insert  into `price_suppliers`(`id`,`supplier_id`,`type_fabric_id`,`type_color_id`,`price`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,8,2,1,10000,'tes 123','2026-05-13 14:14:48','2026-06-22 10:34:02',51,51,NULL,NULL),
(2,1,1,2,250,'tes','2026-05-13 14:45:38','2026-06-09 09:37:28',51,51,NULL,NULL);

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
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
(10,2),
(11,2),
(12,2),
(13,2),
(14,2),
(15,2),
(16,2),
(17,2),
(18,2),
(19,2),
(20,2),
(21,2),
(22,2),
(23,2),
(24,2),
(25,2),
(26,2),
(27,2),
(28,2),
(29,2),
(30,2),
(31,2),
(32,2),
(33,2),
(34,2),
(34,3),
(35,2),
(35,3),
(36,2),
(36,3),
(37,2),
(37,3),
(38,2),
(38,3),
(39,2),
(39,3),
(40,2),
(40,3),
(41,2),
(41,3),
(42,2),
(42,3),
(43,2),
(43,3),
(44,2),
(44,3),
(45,2),
(45,3),
(46,2),
(46,3),
(47,2),
(47,3),
(48,2),
(48,3),
(49,2),
(49,3),
(50,2),
(50,3),
(51,2),
(51,3),
(52,2),
(52,3),
(53,2),
(53,3),
(54,2),
(54,3),
(55,2),
(55,3),
(56,2),
(56,3),
(57,2),
(57,3),
(58,2),
(58,3),
(59,2),
(59,3),
(60,2),
(60,3),
(61,2),
(61,3),
(62,2),
(62,3),
(63,2),
(63,3),
(64,2),
(64,3),
(65,2),
(65,3),
(66,2),
(67,2),
(68,2),
(69,2),
(70,2),
(71,2),
(72,2),
(73,2),
(74,2),
(75,2),
(76,2),
(77,2),
(78,2),
(79,2),
(80,2),
(81,2),
(82,2),
(82,3),
(83,2),
(83,3),
(84,2),
(84,3),
(85,2),
(85,3),
(86,2),
(86,3),
(87,2),
(87,3),
(88,2),
(88,3),
(89,2),
(89,3),
(90,2),
(90,3),
(91,2),
(91,3),
(92,2),
(92,3),
(93,2),
(93,3),
(94,2),
(94,3),
(95,2),
(95,3),
(96,2),
(96,3),
(97,2),
(97,3);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Super Admin','web','2026-04-03 08:32:32','2026-04-03 08:32:32',NULL,NULL,NULL,NULL),
(2,'Admin','web','2026-04-03 08:32:32','2026-04-03 08:32:32',NULL,NULL,NULL,NULL),
(3,'Employee','web','2026-05-13 15:04:59','2026-05-13 15:04:59',NULL,NULL,NULL,NULL);

/*Table structure for table `sablon_details` */

DROP TABLE IF EXISTS `sablon_details`;

CREATE TABLE `sablon_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sablon_id` bigint(20) unsigned DEFAULT NULL,
  `color_fabric_id` bigint(20) unsigned DEFAULT NULL,
  `long_fabric` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sablon_details_sablon_id_foreign` (`sablon_id`),
  KEY `sablon_details_color_fabric_id_foreign` (`color_fabric_id`),
  CONSTRAINT `sablon_details_color_fabric_id_foreign` FOREIGN KEY (`color_fabric_id`) REFERENCES `color_fabrics` (`id`),
  CONSTRAINT `sablon_details_sablon_id_foreign` FOREIGN KEY (`sablon_id`) REFERENCES `sablons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sablon_details` */

/*Table structure for table `sablon_employee_details` */

DROP TABLE IF EXISTS `sablon_employee_details`;

CREATE TABLE `sablon_employee_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sablon_id` bigint(20) unsigned DEFAULT NULL,
  `fabric_detail_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `layers` int(11) DEFAULT NULL,
  `fee` int(11) DEFAULT NULL,
  `additional_fee` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_fee`)),
  `total` int(11) DEFAULT NULL,
  `is_change` tinyint(1) DEFAULT NULL,
  `employee_change_id` bigint(20) unsigned DEFAULT NULL,
  `is_payed` tinyint(1) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sablon_employee_details` */

/*Table structure for table `sablons` */

DROP TABLE IF EXISTS `sablons`;

CREATE TABLE `sablons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `fabric_id` bigint(20) unsigned DEFAULT NULL,
  `fabric_detail_id` bigint(20) unsigned DEFAULT NULL,
  `image_fabric_id` bigint(20) unsigned DEFAULT NULL,
  `type_color_id` bigint(20) unsigned DEFAULT NULL,
  `type_fabric_id` bigint(20) unsigned DEFAULT NULL,
  `price_employee_id` bigint(20) unsigned DEFAULT NULL,
  `total_long_fabric` int(11) DEFAULT NULL,
  `total_sablon` int(11) DEFAULT NULL COMMENT 'total long fabric * price employee',
  `date_sablon` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'enum StatusSablonEnum',
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sablons_supplier_id_foreign` (`supplier_id`),
  KEY `sablons_fabric_id_foreign` (`fabric_id`),
  KEY `sablons_image_fabric_id_foreign` (`image_fabric_id`),
  KEY `sablons_type_color_id_foreign` (`type_color_id`),
  KEY `sablons_type_fabric_id_foreign` (`type_fabric_id`),
  KEY `sablons_price_employee_id_foreign` (`price_employee_id`),
  KEY `sablons_fabric_detail_id_foreign` (`fabric_detail_id`),
  CONSTRAINT `sablons_fabric_detail_id_foreign` FOREIGN KEY (`fabric_detail_id`) REFERENCES `fabric_details` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sablons_fabric_id_foreign` FOREIGN KEY (`fabric_id`) REFERENCES `fabrics` (`id`),
  CONSTRAINT `sablons_image_fabric_id_foreign` FOREIGN KEY (`image_fabric_id`) REFERENCES `image_fabrics` (`id`),
  CONSTRAINT `sablons_price_employee_id_foreign` FOREIGN KEY (`price_employee_id`) REFERENCES `price_employees` (`id`),
  CONSTRAINT `sablons_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `sablons_type_color_id_foreign` FOREIGN KEY (`type_color_id`) REFERENCES `type_colors` (`id`),
  CONSTRAINT `sablons_type_fabric_id_foreign` FOREIGN KEY (`type_fabric_id`) REFERENCES `type_fabrics` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sablons` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

/*Table structure for table `suppliers` */

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `suppliers` */

insert  into `suppliers`(`id`,`name`,`address`,`contact`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Sunar','tulungagung','08123456780',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(2,'Yadi','tulungagung','08123456781',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(3,'Bibit','tulungagung','08123456782',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(4,'Santoso','tulungagung','08123456783',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(5,'Mail','tulungagung','08123456784',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(6,'Yanti','tulungagung','08123456785',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(7,'Rusda','tulungagung','08123456786',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(8,'Ahmad','tulungagung','08123456787',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(9,'Sirajudin','tulungagung','08123456788','tes','2026-04-03 08:32:33','2026-04-27 11:13:02',NULL,51,NULL,NULL),
(10,'Udin','tulungagung','08123456789',NULL,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL);

/*Table structure for table `type_colors` */

DROP TABLE IF EXISTS `type_colors`;

CREATE TABLE `type_colors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `type_colors` */

insert  into `type_colors`(`id`,`name`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,NULL,'2026-05-12 13:29:56','2026-05-12 13:39:42',51,51,NULL,NULL),
(2,2,NULL,'2026-05-12 13:30:05','2026-05-12 13:30:05',51,51,NULL,NULL),
(3,3,'tes','2026-05-12 13:30:12','2026-05-13 08:34:57',51,51,NULL,NULL);

/*Table structure for table `type_fabrics` */

DROP TABLE IF EXISTS `type_fabrics`;

CREATE TABLE `type_fabrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `type_fabrics` */

insert  into `type_fabrics`(`id`,`name`,`notes`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'INT','tes','2026-04-27 08:38:51','2026-04-27 08:38:51',51,51,NULL,NULL),
(2,'HGT',NULL,'2026-04-27 08:39:01','2026-04-27 08:39:01',51,51,NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`two_factor_secret`,`two_factor_recovery_codes`,`two_factor_confirmed_at`,`remember_token`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'User 1','user1@gmail.com','2026-04-03 08:32:23','$2y$12$t6TN04CY.cLXyE/BpiobdOE56dbjl1he8YqDqG/Cmr3hiq/5.lSeu',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:23','2026-04-03 08:32:23',NULL,NULL,NULL,NULL),
(2,'User 2','user2@gmail.com','2026-04-03 08:32:23','$2y$12$KvRSzP.1GMYoGDZpeyMsJuiX7uQ0ipmqQ5oNl9Hwc1Ublzm9OuNL6',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:23','2026-04-03 08:32:23',NULL,NULL,NULL,NULL),
(3,'User 3','user3@gmail.com','2026-04-03 08:32:23','$2y$12$.CB/OTAebyBTZ4lpE3YZhOUBPJONSCwDtVWH77j/H.D3VpK/E3yym',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:23','2026-04-03 08:32:23',NULL,NULL,NULL,NULL),
(4,'User 4','user4@gmail.com','2026-04-03 08:32:24','$2y$12$mBbLHxRS4kXv4r5MBsfS0uYoelT6JCHXXGJwO8weaXTXspYu4xZlG',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:24','2026-04-03 08:32:24',NULL,NULL,NULL,NULL),
(5,'User 5','user5@gmail.com','2026-04-03 08:32:24','$2y$12$N7hT4cGAT6GGB3mRcHbG7eY43qR.D7QOBVzNYMitP27PKkNy5ozIW',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:24','2026-04-03 08:32:24',NULL,NULL,NULL,NULL),
(6,'User 6','user6@gmail.com','2026-04-03 08:32:24','$2y$12$uY5pQmD83k46ukgF42KXSeH012HjRkEGnKzFzsJMCA1lH4pw4KS/2',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:24','2026-04-03 08:32:24',NULL,NULL,NULL,NULL),
(7,'User 7','user7@gmail.com','2026-04-03 08:32:24','$2y$12$r8PafZ/0RtZ7fjHMSg1IcOg4uateXk50RN2BYSVOvQ2zpCgIe1/Pi',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:24','2026-04-03 08:32:24',NULL,NULL,NULL,NULL),
(8,'User 8','user8@gmail.com','2026-04-03 08:32:24','$2y$12$QtLwPMwQ05mz3w7jRuBhUebTMILaoA/PZv2jHnUS/bpr5dU1iYvIS',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:24','2026-04-03 08:32:24',NULL,NULL,NULL,NULL),
(9,'User 9','user9@gmail.com','2026-04-03 08:32:24','$2y$12$QytBsKdcKZpJoZ6aUk.4WO1zfkDu2ZCZ6qu0gS3wJ4ImjdRrOeI8G',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:24','2026-04-03 08:32:24',NULL,NULL,NULL,NULL),
(10,'User 10','user10@gmail.com','2026-04-03 08:32:25','$2y$12$vvfpKJXs5NqK20x3dhDZ7.PJgfgYzWfiH9uP1temICQJa.OB.Dk9W',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:25','2026-04-03 08:32:25',NULL,NULL,NULL,NULL),
(11,'User 11','user11@gmail.com','2026-04-03 08:32:25','$2y$12$I1wFFltkmv/elAO98XFHuuQeQfxTN4nGzgRe9xamCWmpaJOPpXgIi',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:25','2026-04-03 08:32:25',NULL,NULL,NULL,NULL),
(12,'User 12','user12@gmail.com','2026-04-03 08:32:25','$2y$12$BTwHOwU7bUXFwLuc/vvxMOcD.wHqduBioCNcm1btgZTdhr3VMwtc6',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:25','2026-04-03 08:32:25',NULL,NULL,NULL,NULL),
(13,'User 13','user13@gmail.com','2026-04-03 08:32:25','$2y$12$E8d7psm3PrrBcV8FziAJlesbWFl/vyZxe/wPsdNqKcIBV5qvKF/my',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:25','2026-04-03 08:32:25',NULL,NULL,NULL,NULL),
(14,'User 14','user14@gmail.com','2026-04-03 08:32:25','$2y$12$/9b58t1ayq0aZqb1qGMpPe4Mxr/J512ktesIjcI2/n9FbzylV/C3O',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:25','2026-04-03 08:32:25',NULL,NULL,NULL,NULL),
(15,'User 15','user15@gmail.com','2026-04-03 08:32:26','$2y$12$RdS9ccbHQTuUt5ScWiAqwOcQtG/iHtCZRhibtF1WYiFgu1D2Svlg6',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:26','2026-04-03 08:32:26',NULL,NULL,NULL,NULL),
(16,'User 16','user16@gmail.com','2026-04-03 08:32:26','$2y$12$UCGcuL.Y8B0svpkrLItqGuMhwY6gR.wpTKBlEFcy8033DfZot2Wha',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:26','2026-04-03 08:32:26',NULL,NULL,NULL,NULL),
(17,'User 17','user17@gmail.com','2026-04-03 08:32:26','$2y$12$no5JGgJai1Vf3a8FduG/9uNlCZ/kjMxG.GMLEiXnbWMeOF8azPqwm',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:26','2026-04-03 08:32:26',NULL,NULL,NULL,NULL),
(18,'User 18','user18@gmail.com','2026-04-03 08:32:26','$2y$12$7zvXuUwZKhjGaC4Rga0GmewVX8RqsHCrKb4ryEuTf0KembQneGJuq',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:26','2026-04-03 08:32:26',NULL,NULL,NULL,NULL),
(19,'User 19','user19@gmail.com','2026-04-03 08:32:26','$2y$12$eiMkL52ZZLU4MzVhGV4g1eCQG9ItE58TqNLSKIgTUnGkkom0uZNE.',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:26','2026-04-03 08:32:26',NULL,NULL,NULL,NULL),
(20,'User 20','user20@gmail.com','2026-04-03 08:32:27','$2y$12$7fLqYvOjdmiOeDa/5igJWuINsUA/Lq03iAx.RsAvnHkbv0hIzZSHi',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:27','2026-04-03 08:32:27',NULL,NULL,NULL,NULL),
(21,'User 21','user21@gmail.com','2026-04-03 08:32:27','$2y$12$2XQmF9VKtDx9zX2IKtivmOG35zcWXlECoAvSyT2WZd7m5Kh6B5IJy',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:27','2026-04-03 08:32:27',NULL,NULL,NULL,NULL),
(22,'User 22','user22@gmail.com','2026-04-03 08:32:27','$2y$12$VaZzeP/qFL7Z8gzVi.ztSOHux39pSrI5STQB4dDfJ/D3ck1IE0SJW',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:27','2026-04-03 08:32:27',NULL,NULL,NULL,NULL),
(23,'User 23','user23@gmail.com','2026-04-03 08:32:27','$2y$12$Z1UDZcBIZitTKQI3iGGvp.oqCxiCTwB9XSaDdts2bf9dmPHmgHGXS',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:27','2026-04-03 08:32:27',NULL,NULL,NULL,NULL),
(24,'User 24','user24@gmail.com','2026-04-03 08:32:27','$2y$12$h1m4vMkZQn8dEku03qCXWegA6hs7hceXsNXwWt5aiLeegUmA5LdNS',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:27','2026-04-03 08:32:27',NULL,NULL,NULL,NULL),
(25,'User 25','user25@gmail.com','2026-04-03 08:32:27','$2y$12$KWsG5QY0VoTujIx2iA7reeBNS8CL2AwwK6XCR7JTUxTnQg/uADnvK',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:27','2026-04-03 08:32:27',NULL,NULL,NULL,NULL),
(26,'User 26','user26@gmail.com','2026-04-03 08:32:28','$2y$12$4y.hcijRmR85EGTjhkE1R.cLGYLsWqkfunJkrvEI7M8UuX9yV5T0K',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:28','2026-04-03 08:32:28',NULL,NULL,NULL,NULL),
(27,'User 27','user27@gmail.com','2026-04-03 08:32:28','$2y$12$VsRddhL/icSXDosE4bSA4ekF2/d2NyMmLg9hGz8d5ayasaiMdPwGW',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:28','2026-04-03 08:32:28',NULL,NULL,NULL,NULL),
(28,'User 28','user28@gmail.com','2026-04-03 08:32:28','$2y$12$E3eobMFvbtm51NmdO7rE2uTdXPdsnW4maNoG4CFUayaxr7sVTRZvm',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:28','2026-04-03 08:32:28',NULL,NULL,NULL,NULL),
(29,'User 29','user29@gmail.com','2026-04-03 08:32:28','$2y$12$WAHKil5/pooOmVQPuQJ4CeTwCA2YWMU7jxoO2wTaZ2jm3gtp6g8OW',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:28','2026-04-03 08:32:28',NULL,NULL,NULL,NULL),
(30,'User 30','user30@gmail.com','2026-04-03 08:32:28','$2y$12$5xSS7WmrdF1cSBHMVPi.uerm87tG1FAxmzqUoiVa1dJdcHIjFqYCq',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:28','2026-04-03 08:32:28',NULL,NULL,NULL,NULL),
(31,'User 31','user31@gmail.com','2026-04-03 08:32:29','$2y$12$wyAM/p9VvE9ufhK4rmV79uTbbVfWoXSuPN4JsmtLS13q2Tq6MApX6',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:29','2026-04-03 08:32:29',NULL,NULL,NULL,NULL),
(32,'User 32','user32@gmail.com','2026-04-03 08:32:29','$2y$12$KvmPz8CXPtO4TmlMvw3PGu3NOmLOFuMtc5p1BiZ9PbMaPPEyx8w0i',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:29','2026-04-03 08:32:29',NULL,NULL,NULL,NULL),
(33,'User 33','user33@gmail.com','2026-04-03 08:32:29','$2y$12$5Qpsqtg0NM1FlM0tsZXUqOQpaBXpTLB8PTGgLUmoxXraL2vojYpii',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:29','2026-04-03 08:32:29',NULL,NULL,NULL,NULL),
(34,'User 34','user34@gmail.com','2026-04-03 08:32:29','$2y$12$DNeb7n/WQ5tAxJ0BCIbRGe8633MCT1TaCv/mQALydhp3xGhiEMQhy',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:29','2026-04-03 08:32:29',NULL,NULL,NULL,NULL),
(35,'User 35','user35@gmail.com','2026-04-03 08:32:29','$2y$12$Lvf3UafreCBvub.78BEsy.jQlElUrPOlxoyTwRrifDnJzCZlqLFEK',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:29','2026-04-03 08:32:29',NULL,NULL,NULL,NULL),
(36,'User 36','user36@gmail.com','2026-04-03 08:32:30','$2y$12$RLyh4mMmiNa/cJlmFrp/nupcRYoRDaVSgYUUomaz2bl7A1IQJHWNa',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:30','2026-04-03 08:32:30',NULL,NULL,NULL,NULL),
(37,'User 37','user37@gmail.com','2026-04-03 08:32:30','$2y$12$rsu1t/Tk.b9e/Ge9fYKM5O6eItwma59yPjQLk/KtRuk6pH51dpPbS',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:30','2026-04-03 08:32:30',NULL,NULL,NULL,NULL),
(38,'User 38','user38@gmail.com','2026-04-03 08:32:30','$2y$12$.nClyXb3qZqWVGNvb/Hf6uOi.qNBzQOJQ/zSzzINK3LeIfYyHJLHq',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:30','2026-04-03 08:32:30',NULL,NULL,NULL,NULL),
(39,'User 39','user39@gmail.com','2026-04-03 08:32:30','$2y$12$RsHTvmVfvvVkD.WKG.0VGebi.eLlio/5W3V.ewkE34.aeyTC.TUuK',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:30','2026-04-03 08:32:30',NULL,NULL,NULL,NULL),
(40,'User 40','user40@gmail.com','2026-04-03 08:32:30','$2y$12$VZpqwXRy7ekOnu/tsGHhnOwpvnOpjj1rwCDiA019u6vsefIaOwIAa',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:30','2026-04-03 08:32:30',NULL,NULL,NULL,NULL),
(41,'User 41','user41@gmail.com','2026-04-03 08:32:30','$2y$12$j4h6wi82/D/UziVbPC1yWeBSsNCTMBlviK5HGuRM0PjLsPdjJiiRa',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:30','2026-04-03 08:32:30',NULL,NULL,NULL,NULL),
(42,'User 42','user42@gmail.com','2026-04-03 08:32:31','$2y$12$bLnP6.jWaf9UwlT9eHKfvuQyH6uWOeM4RAgaUt9F734tdpL8L01uW',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:31','2026-04-03 08:32:31',NULL,NULL,NULL,NULL),
(43,'User 43','user43@gmail.com','2026-04-03 08:32:31','$2y$12$g.h.wlsAC6xk/TmBF56QQOQhUC/0BLkWlzeHgkPBgRrYDOrFtI3xS',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:31','2026-04-03 08:32:31',NULL,NULL,NULL,NULL),
(44,'User 44','user44@gmail.com','2026-04-03 08:32:31','$2y$12$r5vy4iJKATbF8/eIyVigsOOv77I7/f.XuN3t6Nj5NOVUl3BBxHFTa',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:31','2026-04-03 08:32:31',NULL,NULL,NULL,NULL),
(45,'User 45','user45@gmail.com','2026-04-03 08:32:31','$2y$12$SXlD2mAw7FTabAmfbxQg3uwgzahJuecR759d3iaz6.0cjdM82idUG',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:31','2026-04-03 08:32:31',NULL,NULL,NULL,NULL),
(46,'User 46','user46@gmail.com','2026-04-03 08:32:31','$2y$12$oHzyFiQi5hWiOl2rFQ8ewOQJtlNComMJmbU1NGat5EQwwSx7HvJhe',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:31','2026-04-03 08:32:31',NULL,NULL,NULL,NULL),
(47,'User 47','user47@gmail.com','2026-04-03 08:32:32','$2y$12$u7WJjqn3Ou0A62UnKEm/y.o9X83Cs1inyh.FOzdEq/ZS.gsnm0W4y',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:32','2026-04-03 08:32:32',NULL,NULL,NULL,NULL),
(48,'User 48','user48@gmail.com','2026-04-03 08:32:32','$2y$12$Loj/w.GyMXKHsvBKLeY7ieaMhIZ.LB6JXngCQPy9cfQZsCW72FH2m',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:32','2026-04-03 08:32:32',NULL,NULL,NULL,NULL),
(49,'User 49','user49@gmail.com','2026-04-03 08:32:32','$2y$12$ZkH1YtlpX2BdwZ75ufhKgOiahdU5qG9jsbWQyKBTHqW6..4/Q38J6',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:32','2026-04-03 08:32:32',NULL,NULL,NULL,NULL),
(50,'User 50','user50@gmail.com','2026-04-03 08:32:32','$2y$12$ZAOxGO4m4lnlvCgzPYUl5ODRLL6uJjmz6xR1WAlxBpUz5FZZzZys6',NULL,NULL,NULL,NULL,0,'2026-04-03 08:32:32','2026-04-03 08:32:32',NULL,NULL,NULL,NULL),
(51,'Rendy','rendy@gmail.com','2026-04-03 08:32:32','$2y$12$Y9/LOz2rVzMKFrMRtz2IG.2ACKkl4mIr3Da/w6vB5fgtv88N00b6q',NULL,NULL,NULL,NULL,1,'2026-04-03 08:32:32','2026-06-09 08:24:53',NULL,51,NULL,NULL),
(52,'Edo','edo@gmail.com','2026-04-03 08:32:33','$2y$12$Y9/LOz2rVzMKFrMRtz2IG.2ACKkl4mIr3Da/w6vB5fgtv88N00b6q',NULL,NULL,NULL,NULL,1,'2026-04-03 08:32:33','2026-04-03 08:32:33',NULL,NULL,NULL,NULL),
(53,'Zenia Baker','vysu@mailinator.com',NULL,'$2y$12$BS7TiiVdPRGAeReoerbzs.71yMQqYvL3bqNAxIDirTFAnzHQqbeL6',NULL,NULL,NULL,NULL,1,'2026-04-10 10:58:12','2026-06-23 11:10:26',51,51,NULL,NULL),
(54,'tes131','tes131@gmail.com',NULL,'$2y$12$vT1CzWcY6EtUI8w8jZ41euXeiBQEDmhN8HvNt6tdOEzuXPQc9zvQG',NULL,NULL,NULL,NULL,1,'2026-05-14 14:30:33','2026-05-14 14:30:33',51,51,NULL,NULL),
(55,'tes1312','edo1@gmail.com',NULL,'$2y$12$3BubSASAMaSGS0bRX0rSuupW0pjedydLVV7hhcZDg8jV1ZaKj.MaC',NULL,NULL,NULL,NULL,1,'2026-05-14 14:31:49','2026-06-23 09:21:06',51,51,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
