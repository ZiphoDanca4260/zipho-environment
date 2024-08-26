-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.33 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for assessment_primoexecutive_co_za
CREATE DATABASE IF NOT EXISTS `assessment_primoexecutive_co_za` /*!40100 DEFAULT CHARACTER SET utf16 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `assessment_primoexecutive_co_za`;

-- Dumping structure for table assessment_primoexecutive_co_za.inf_helps
CREATE TABLE IF NOT EXISTS `inf_helps` (
  `help_id` int NOT NULL AUTO_INCREMENT,
  `help_render_to` varchar(200) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT '[]' COMMENT 'Field specified render section on frontend accept as "admin" and "client"',
  `link_ids` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'This records valid for onliy specified link_id "0" for all page',
  `help_name` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT '' COMMENT 'Definition to records as name same like note for developers',
  `help_section` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT '' COMMENT 'Definition to behavior of the this record for frontend',
  `help_selector` varchar(200) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT '' COMMENT 'Targeted element for frontend',
  `help_header` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Render header content as object, allowed multiple lang',
  `help_summary` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Render summary content as object, allowed multiple lang',
  `help_text` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Render full content as object, allowed multiple lang',
  `help_status` varchar(20) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'Published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`help_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='inf_ as Information, Core:  This table store frontend dom elements description and information for help users to understand';

-- Dumping data for table assessment_primoexecutive_co_za.inf_helps: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.inf_versions
CREATE TABLE IF NOT EXISTS `inf_versions` (
  `version_id` int NOT NULL AUTO_INCREMENT,
  `version` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Version number to track ',
  `version_subject` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'version header ',
  `version_notes` longtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'version change logs. Accept as object data for each languge base',
  `version_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`version_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='inf_ as Information, Core:  This table store version change logs of the project';

-- Dumping data for table assessment_primoexecutive_co_za.inf_versions: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.set_countries
CREATE TABLE IF NOT EXISTS `set_countries` (
  `country_id` int NOT NULL AUTO_INCREMENT,
  `country_iso2` varchar(2) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL COMMENT 'Country iso code',
  `country_name` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Country name',
  `country_phone_code` int DEFAULT NULL COMMENT 'Country phone code',
  `country_time_zone` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Country time zone ',
  `country_phone_area_code_pattern_regex` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL COMMENT 'Country phone area code validation patern',
  `country_phone_number_pattern_regex` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL COMMENT 'Country phone number validation patern',
  `country_status` varchar(10) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`country_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='set_ as Settings, Core:  This table store Countries data ';

-- Dumping data for table assessment_primoexecutive_co_za.set_countries: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.set_links
CREATE TABLE IF NOT EXISTS `set_links` (
  `link_id` int NOT NULL AUTO_INCREMENT,
  `link_link` varchar(200) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL COMMENT 'Field contain url path',
  `link_redirect` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'If any url path set in here it will redirect page to what path written',
  `link_restrict` int DEFAULT '0',
  `link_admin` tinyint DEFAULT '0' COMMENT 'Links definition of admin(1) or client (0) ',
  `link_hit` bigint DEFAULT '0' COMMENT 'Hit counter trigger with each request',
  `link_restrict_redirect` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL,
  `link_restrict_to` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci,
  `link_permission` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Accepted as object, Define privilage of users group or users self, User settings have privilage',
  `link_page` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'This field defination to include on controller or wiev part',
  `link_meta_title` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Render: It sets Meta data Title for page',
  `link_meta_description` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Render: It sets Meta data Description for page',
  `link_meta_keywords` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Render: It sets Meta data Keywords for page',
  `link_header_script` longtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Render: It allow to set customise css,script file or code in header section',
  `link_footer_script` longtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Render: It allow to set customise css,script file or code in footer section',
  `link_js_css` longtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Accepts as object. Customise page merged files css or js',
  `link_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'passive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`link_id`) USING BTREE,
  UNIQUE KEY `links_link` (`link_link`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='set_ as Settings, Core:  This table store links details, permissions privilage of user or users group in here ';

-- Dumping data for table assessment_primoexecutive_co_za.set_links: ~20 rows (approximately)
INSERT INTO `set_links` (`link_id`, `link_link`, `link_redirect`, `link_restrict`, `link_admin`, `link_hit`, `link_restrict_redirect`, `link_restrict_to`, `link_permission`, `link_page`, `link_meta_title`, `link_meta_description`, `link_meta_keywords`, `link_header_script`, `link_footer_script`, `link_js_css`, `link_status`, `created_at`, `updated_at`) VALUES
	(1, '/', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'home.php', 'Primo Executive | Home', NULL, NULL, NULL, NULL, NULL, 'published', '2024-05-14 08:37:54', '2024-05-14 08:37:56'),
	(2, '/inventory', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'inventory.php', 'Primo Executive | Inventory', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-27 09:16:33', '2024-06-27 09:16:37'),
	(3, '/inventory-single', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'inventory-single.php', 'Primo Executive | Vehicle', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-27 09:16:33', '2024-06-27 09:16:37'),
	(4, '/about/about-us', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'about/about-us.php', 'Primo Executive | About Us', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-28 10:20:43', '2024-06-28 10:20:46'),
	(5, '/about/mission-statement', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'about/mission-statement.php', 'Primo Executive | Mission Statement', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-28 14:47:29', '2024-06-28 14:47:32'),
	(6, '/about/meet-our-team', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'about/meet-our-team.php', 'Primo Executive | Meet Our Team', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-29 08:06:50', '2024-06-29 08:06:53'),
	(7, '/about/client-reviews', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'about/client-reviews.php', 'Primo Executive | Client Reviews', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-29 11:26:10', '2024-06-29 11:26:13'),
	(8, '/about/join-primo-executive', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'about/join-primo-executive.php', 'Primo Executive | Join The Team', NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-01 09:58:13', '2024-07-01 09:58:15'),
	(9, '/finance/arrange-finance', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'finance/arrange-finance.php', 'Primo Executive | Arrange Finance', NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-01 11:03:12', '2024-07-01 11:03:20'),
	(10, '/finance/warranties', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'finance/warranties.php', 'Primo Executive | Warranties & Service Plans', NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-01 11:04:00', '2024-07-01 11:04:02'),
	(11, '/finance/we-buy-cars', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'finance/we-buy-cars.php', 'Primo Executive | We Buy Cars', NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-01 11:04:52', '2024-07-01 11:04:52'),
	(12, '/contact', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'contact.php', 'Primo Executive | Contact', NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-01 15:25:01', '2024-07-01 15:25:02'),
	(13, '/cron/update-listings', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'cron/update-listings.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-02 10:25:41', '2024-07-02 10:25:42'),
	(14, '/blog', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'blog.php', 'Primo Executive | Blog', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-27 09:16:33', '2024-06-27 09:16:37'),
	(15, '/blog-post', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'blog-post.php', 'Primo Executive | Blog Post', NULL, NULL, NULL, NULL, NULL, 'published', '2024-06-27 09:16:33', '2024-06-27 09:16:37'),
	(16, '/api/auth', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer",\r\n            "verifyCaptcha": "1"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest",\r\n            "verifyCaptcha": "1"\r\n        }\r\n    }\r\n}', 'api-auth.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-02 10:25:41', '2024-07-02 10:25:42'),
	(17, '/api/mailer', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer",\r\n            "contactFinance": "1",\r\n            "evaluateMyCar": "1",\r\n            "sendJobApplication": "1"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest",\r\n            "contactFinance": "1",\r\n            "evaluateMyCar": "1",\r\n            "sendJobApplication": "1",\r\n            "contactUs": "1"\r\n        }\r\n    }\r\n}', 'api-mailer.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-07-02 10:25:41', '2024-07-02 10:25:42'),
	(18, '/admin/login', NULL, 0, 1, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "1",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "1",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "1",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "1",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'admin-login.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-08-01 10:26:57', '2024-08-01 10:27:04'),
	(19, '/admin/dashboard', NULL, 0, 1, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "0",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "0",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "0",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "0",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'admin-dashboard.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-08-01 10:26:57', '2024-08-01 10:27:04'),
	(20, '/assessment', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "0",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "0",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "0",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "0",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'assessment.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-08-01 10:26:57', '2024-08-01 10:27:04'),
	(21, '/assessment-api', NULL, 0, 0, 0, NULL, NULL, '{\r\n    "user_group_id": {\r\n        "1": {\r\n            "show": "1",\r\n            "name": "developer"\r\n        },\r\n        "2": {\r\n            "show": "0",\r\n            "name": "admin"\r\n        },\r\n        "3": {\r\n            "show": "0",\r\n            "name": "vendor"\r\n        },\r\n        "4": {\r\n            "show": "0",\r\n            "name": "user"\r\n        },\r\n        "5": {\r\n            "show": "0",\r\n            "name": "guest"\r\n        }\r\n    }\r\n}', 'api/assessment-api.php', NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2024-08-01 10:26:57', '2024-08-01 10:27:04');

-- Dumping structure for table assessment_primoexecutive_co_za.set_link_menus
CREATE TABLE IF NOT EXISTS `set_link_menus` (
  `link_menu_id` int NOT NULL AUTO_INCREMENT,
  `link_menu_subid` int NOT NULL DEFAULT '0' COMMENT 'categorise the menu with parent',
  `link_menu_order` int DEFAULT '0' COMMENT 'Order  to show  menu piority',
  `link_menu_name` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Accept as object in each language Name of the menu',
  `link_menu_type` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Accept as array as "admin","api","footer" ect . of the menu section display',
  `link_link` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'url path same with link',
  `link_menu_icon_left` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Add a icon left side of the menu name',
  `link_menu_icon_right` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Add a icon right side of the menu name',
  `link_menu_permissions` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Accepted as object, Define privilage of users group or users self, User settings have privilage',
  `link_menu_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`link_menu_id`) USING BTREE,
  UNIQUE KEY `link_link` (`link_link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='set_ as Settings, Core:  This table store links details, permissions privilage of user or users group in here ';

-- Dumping data for table assessment_primoexecutive_co_za.set_link_menus: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.set_recycle_bin
CREATE TABLE IF NOT EXISTS `set_recycle_bin` (
  `recycle_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'The primary id of the recycle entry',
  `user_id` bigint DEFAULT NULL COMMENT 'Id of the user that deleted the data',
  `recycle_table` varchar(50) DEFAULT NULL COMMENT 'The table which the object was recycled from',
  `recycle_object_id` bigint DEFAULT NULL COMMENT 'the objects auto_increment primary key value.',
  `recycle_object` longtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'The recycle object in JSON format. ',
  `recycle_related_objects` longtext COMMENT 'If the deleted item is from a main table (e.g. usr_users [one-to-many table]) then also holds related tables rows',
  `recycle_archieve_location` varchar(255) DEFAULT NULL COMMENT 'if the data is archieved, where is it located?',
  `recycle_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'status of the recycle (e.g. ''pending'', ''archieved'', ''recycled'')',
  `created_at` datetime DEFAULT NULL COMMENT 'entry creation date',
  `updated_at` datetime DEFAULT NULL COMMENT 'entry update date',
  PRIMARY KEY (`recycle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='This table will hold any deleted entry and its relational table''s entries as JSON. (set_ for settings and core tables of the framework)';

-- Dumping data for table assessment_primoexecutive_co_za.set_recycle_bin: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.usr_pass
CREATE TABLE IF NOT EXISTS `usr_pass` (
  `pass_id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL COMMENT 'This records relation with user ',
  `pass_password` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'hashed password to access project',
  `pass_expire` datetime DEFAULT NULL COMMENT 'if its set paswords validation period',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pass_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `users_id` (`user_id`) USING BTREE,
  CONSTRAINT `usr_users.usr_pass` FOREIGN KEY (`user_id`) REFERENCES `usr_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='usr_ as User : This table collects users passwords ';

-- Dumping data for table assessment_primoexecutive_co_za.usr_pass: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.usr_phones
CREATE TABLE IF NOT EXISTS `usr_phones` (
  `phone_id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL COMMENT 'This records relation with user ',
  `phone_number` bigint DEFAULT NULL COMMENT 'Users Phone number',
  `phone_country_code` smallint DEFAULT NULL COMMENT 'Users Phone number country code',
  `phone_area_code` smallint DEFAULT NULL COMMENT 'Users Phone number area code',
  `phone_default` tinyint DEFAULT NULL COMMENT 'This recod set as default in use. 0,1 values are expected',
  `phone_verified` tinyint(1) DEFAULT '0' COMMENT 'This phone verified. 0,1 values are expected',
  `phone_validation_code` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Verification code sent to phone number for validation',
  `phone_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`phone_id`) USING BTREE,
  UNIQUE KEY `unique_phone_number` (`phone_number`,`phone_country_code`),
  KEY `phone_number` (`phone_country_code`,`phone_area_code`,`phone_number`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `usr_users.usr_phones` FOREIGN KEY (`user_id`) REFERENCES `usr_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='usr_ as User : This table collects users phone numbers';

-- Dumping data for table assessment_primoexecutive_co_za.usr_phones: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.usr_temp
CREATE TABLE IF NOT EXISTS `usr_temp` (
  `temp_id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL DEFAULT '0' COMMENT 'Related to user if loged in ',
  `user_group_id` int DEFAULT '5' COMMENT 'Related to user with role member of group default is Guest if not loged in',
  `user_mail` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Guest mail if not loged in automaticly genereate one ',
  `session_id` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Current session of Guest, User',
  `token` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Security of the data interracted with ',
  `temp_lang` varchar(3) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'en' COMMENT 'Default language',
  `temp_remote_addr` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Guest IP adress',
  `temp_user_addr` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Guest Masked IP adress ',
  `temp_currency` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'ZAR' COMMENT 'Guest default currency',
  `browser_id` int DEFAULT '0' COMMENT 'Visiter''s browser in used',
  `temp_browser` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Visiter''s browser in used',
  `temp_referer` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Visiter''s referer page to come',
  `temp_settings` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'various settings accpeted as object',
  `notes` mediumtext CHARACTER SET utf16 COLLATE utf16_general_ci COMMENT 'Notes for devop',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`temp_id`) USING BTREE,
  KEY `session_id` (`session_id`),
  KEY `updated_at` (`updated_at`),
  KEY `users_id` (`user_id`) USING BTREE,
  KEY `users_mail` (`user_mail`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='usr_ as User : This table collects visiters guest accounts for temproray use';

-- Dumping data for table assessment_primoexecutive_co_za.usr_temp: ~9 rows (approximately)
INSERT INTO `usr_temp` (`temp_id`, `user_id`, `user_group_id`, `user_mail`, `session_id`, `token`, `temp_lang`, `temp_remote_addr`, `temp_user_addr`, `temp_currency`, `browser_id`, `temp_browser`, `temp_referer`, `temp_settings`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 0, 5, NULL, 'is0afrk7dmtsninpd6806e5g97', 'fa3dddcc3c152359b7ffaf8203267450', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', '[]', '2024-08-06 11:16:52', '2024-08-06 13:38:22'),
	(2, 0, 5, NULL, 'janhlmbn348461hlvtl3v805ol', '6800988f5ca8224b400654d82849acd3', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 11:23:20', '2024-08-06 11:23:20'),
	(3, 0, 5, NULL, 'igbuabrn0mevc2tdbeaanr0nmr', '252bc93da0a613ac9569d29d6a204541', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 11:29:15', '2024-08-06 11:29:15'),
	(4, 0, 5, NULL, 'q8pmrod7ubbi6h11qv4klba69g', '7d38072e89f2ffdf4fe478694508b951', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 11:29:43', '2024-08-06 11:29:43'),
	(5, 0, 5, NULL, 'i2lm061glccittj49skbrugpon', '55ef3351c9a1f99883b824520d3490da', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 11:42:19', '2024-08-06 11:42:19'),
	(6, 0, 5, NULL, 'qihmsaabqblpik05n8rkc7pebm', 'f7678434b0e5556fdefa56eb6afc8b6d', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 11:55:21', '2024-08-06 11:55:21'),
	(7, 0, 5, NULL, '74q4rm0d26ujs8tv1b1tv4matf', '52be97a2c419c880ee89386732acd249', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 13:38:22', '2024-08-06 13:38:22'),
	(8, 0, 5, NULL, 'krfqksc70ibjl8vh8mi94b1l1e', '372d6771a358466024d231e5c5521e5e', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', '[]', '2024-08-06 13:43:38', '2024-08-06 13:48:41'),
	(9, 0, 5, NULL, 'lujdpulqlsnpv6r4265tnm55v5', '4f5833df24dc66449a66e9af345582b8', 'en', '127.0.0.1', '127.0.0.1', 'ZAR', 0, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0', NULL, '[]', NULL, '2024-08-06 13:44:19', '2024-08-06 13:44:19');

-- Dumping structure for table assessment_primoexecutive_co_za.usr_users
CREATE TABLE IF NOT EXISTS `usr_users` (
  `user_id` bigint NOT NULL AUTO_INCREMENT,
  `user_group_id` int DEFAULT '5' COMMENT 'Users group role that related',
  `user_force_log_in` tinyint DEFAULT '0' COMMENT 'User force to login agin if its loged in or marked remember me. Accepted 0,1',
  `complete_required_fields` tinyint DEFAULT '0' COMMENT 'This user has necessary data to fill check the required task to complate, accepted data as 0,1',
  `user_mail` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL COMMENT 'Users default email in use ',
  `token` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Users security to before activity of posted data',
  `user_nat_id` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Users national ID or passport number (if not south african)',
  `user_name` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Users full name ',
  `user_gender` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Users gender',
  `user_validation_code` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Users validation code to sent before activate',
  `user_validated` tinyint DEFAULT '0' COMMENT 'If user has clicked on the activation link on their email, they activated their account.',
  `user_lang` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'en' COMMENT 'Users default language',
  `user_avatar` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'Users selected,uploaded avatar',
  `user_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'published',
  `firebase_key` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT NULL COMMENT 'This users firebase reg key for mobile aplication notification use',
  `last_activity_at` datetime DEFAULT NULL COMMENT 'This users last activity time record',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE KEY `users_mail_unique` (`user_mail`) USING BTREE,
  UNIQUE KEY `session_id_unique` (`token`) USING BTREE,
  KEY `session_id` (`token`) USING BTREE,
  KEY `user_group_id` (`user_group_id`),
  KEY `users_mail` (`user_mail`) USING BTREE,
  CONSTRAINT `user_group_id` FOREIGN KEY (`user_group_id`) REFERENCES `usr_user_group` (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='usr_ as User : This table collects user''s accounts';

-- Dumping data for table assessment_primoexecutive_co_za.usr_users: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.usr_user_group
CREATE TABLE IF NOT EXISTS `usr_user_group` (
  `user_group_id` int NOT NULL AUTO_INCREMENT,
  `user_group_name` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL COMMENT 'Name of the group role relate to user',
  `user_group_priority` smallint DEFAULT NULL COMMENT 'Piorty of the group record',
  `user_group_status` varchar(50) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='usr_ as User : This table collects user''s roles for project';

-- Dumping data for table assessment_primoexecutive_co_za.usr_user_group: ~0 rows (approximately)

-- Dumping structure for table assessment_primoexecutive_co_za.usr_blog_posts
CREATE TABLE IF NOT EXISTS `usr_blog_posts` (
  `usr_blog_posts_id` int NOT NULL AUTO_INCREMENT,
  `usr_blog_posts_image` LONGTEXT,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`usr_blog_posts_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='usr_ as User : This table collects user''s blog posts for project';

-- Dumping data for table assessment_primoexecutive_co_za.usr_blog_posts: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
