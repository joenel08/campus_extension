-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for extension_db
CREATE DATABASE IF NOT EXISTS `extension_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `extension_db`;

-- Dumping structure for table extension_db.banner
CREATE TABLE IF NOT EXISTS `banner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `media_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.banner: ~0 rows (approximately)
INSERT IGNORE INTO `banner` (`id`, `type`, `media_path`, `title`, `subtitle`, `updated_at`) VALUES
	(1, 'video', 'uploads/banner/1787753394_banner.mp4', 'Extension & Training Services', 'Bridging academic excellence with community development.', '2026-08-26 14:09:54');

-- Dumping structure for table extension_db.colleges
CREATE TABLE IF NOT EXISTS `colleges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `abbreviation` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abbreviation` (`abbreviation`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.colleges: ~8 rows (approximately)
INSERT IGNORE INTO `colleges` (`id`, `abbreviation`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'CDC', 'College of Development Communication', '2026-08-12 07:36:30', '2026-08-12 07:36:30'),
	(2, 'CCSICT', 'College of Computing Studies, Information and Communication Technology', '2026-08-12 07:37:10', '2026-08-12 07:37:10'),
	(3, 'CEAT', 'College of Engineering and Architecture', '2026-08-12 08:05:15', '2026-08-12 08:05:15'),
	(4, 'CED', 'College of Education', '2026-08-12 08:05:15', '2026-08-12 08:05:15'),
	(5, 'CBM', 'College of Business Management', '2026-08-12 08:05:15', '2026-08-12 08:05:15'),
	(6, 'CAS', 'College of Arts and Sciences', '2026-08-12 08:05:15', '2026-08-12 08:05:15'),
	(7, 'CJE', 'College of Criminal Justice Education', '2026-08-12 08:05:15', '2026-08-12 08:05:15');

-- Dumping structure for table extension_db.evaluator_ratings
CREATE TABLE IF NOT EXISTS `evaluator_ratings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evaluator_id` int NOT NULL,
  `report_type` enum('proposal','progress','terminal') DEFAULT 'proposal',
  `submission_id` int NOT NULL,
  `criterion_id` int NOT NULL,
  `rating` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_rating` (`evaluator_id`,`submission_id`,`report_type`,`criterion_id`),
  KEY `criterion_id` (`criterion_id`),
  KEY `idx_submission` (`submission_id`,`report_type`),
  KEY `idx_evaluator` (`evaluator_id`),
  CONSTRAINT `evaluator_ratings_ibfk_1` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluator_ratings_ibfk_3` FOREIGN KEY (`criterion_id`) REFERENCES `eval_criteria` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluator_ratings_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.evaluator_ratings: ~20 rows (approximately)
INSERT IGNORE INTO `evaluator_ratings` (`id`, `evaluator_id`, `report_type`, `submission_id`, `criterion_id`, `rating`, `created_at`, `updated_at`) VALUES
	(121, 5, 'proposal', 9, 1, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(122, 5, 'proposal', 9, 21, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(123, 5, 'proposal', 9, 3, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(124, 5, 'proposal', 9, 4, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(125, 5, 'proposal', 9, 5, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(126, 5, 'proposal', 9, 6, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(127, 5, 'proposal', 9, 7, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(128, 5, 'proposal', 9, 8, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(129, 5, 'proposal', 9, 9, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(130, 5, 'proposal', 9, 10, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(131, 5, 'proposal', 9, 11, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(132, 5, 'proposal', 9, 12, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(133, 5, 'proposal', 9, 13, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(134, 5, 'proposal', 9, 14, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(135, 5, 'proposal', 9, 15, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(136, 5, 'proposal', 9, 16, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(137, 5, 'proposal', 9, 17, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(138, 5, 'proposal', 9, 18, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(139, 5, 'proposal', 9, 19, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(140, 5, 'proposal', 9, 20, 5, '2026-09-10 15:48:35', '2026-09-10 15:48:35');

-- Dumping structure for table extension_db.evaluator_votes
CREATE TABLE IF NOT EXISTS `evaluator_votes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evaluator_id` int NOT NULL,
  `report_type` enum('proposal','progress','terminal') DEFAULT 'proposal',
  `submission_id` int NOT NULL,
  `vote` enum('approve','revision','decline') NOT NULL,
  `comments` text,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_vote` (`evaluator_id`,`submission_id`,`report_type`),
  KEY `idx_submission` (`submission_id`,`report_type`),
  KEY `idx_evaluator` (`evaluator_id`),
  CONSTRAINT `evaluator_votes_ibfk_1` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.evaluator_votes: ~2 rows (approximately)
INSERT IGNORE INTO `evaluator_votes` (`id`, `evaluator_id`, `report_type`, `submission_id`, `vote`, `comments`, `submitted_at`, `updated_at`) VALUES
	(8, 5, 'proposal', 9, 'approve', 'asdgfhgjhj', '2026-09-10 15:48:35', '2026-09-10 15:48:35'),
	(14, 5, 'progress', 1, 'approve', 'asdgfgh', '2026-09-10 16:05:46', '2026-09-10 16:07:49'),
	(18, 5, 'terminal', 1, 'approve', 'asgdfgh', '2026-09-10 16:12:33', '2026-09-10 16:12:33');

-- Dumping structure for table extension_db.eval_criteria
CREATE TABLE IF NOT EXISTS `eval_criteria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `criteria_text` text NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `eval_criteria_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `eval_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.eval_criteria: ~20 rows (approximately)
INSERT IGNORE INTO `eval_criteria` (`id`, `group_id`, `criteria_text`, `display_order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Relevance of the training/topic', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(3, 2, 'Mastery of the Subject Matter', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(4, 2, 'Clarity of Presentation', 2, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(5, 2, 'Reasonableness of the duration of the presentation', 3, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(6, 2, 'Facilitation to learning', 4, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(7, 2, 'Initiative to sustain interest to the topic', 5, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(8, 3, 'Facilitation', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(9, 3, 'Ability to respond to the needs of the participants', 2, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(10, 3, 'Time Management', 3, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(11, 4, 'Food', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(12, 4, 'Accommodation', 2, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(13, 4, 'Venue of the training', 3, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(14, 5, 'Attainment of the training objectives', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(15, 5, 'Responsiveness of the training objective/s', 2, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(16, 5, 'Adequacy of knowledge and skills gained', 3, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(17, 5, 'Availability/completeness of the workshop documents, supplies, materials and equipment', 4, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(18, 6, 'Service is done correctly', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(19, 6, 'Service is rendered promptly', 2, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(20, 6, 'Service is provided appropriately and advantageously', 3, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(21, 1, 'Training is conducted on time or as scheduled', 2, '2026-08-19 13:07:08', '2026-08-19 13:07:08');

-- Dumping structure for table extension_db.eval_groups
CREATE TABLE IF NOT EXISTS `eval_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.eval_groups: ~6 rows (approximately)
INSERT IGNORE INTO `eval_groups` (`id`, `name`, `display_order`, `created_at`, `updated_at`) VALUES
	(1, 'I. Training Proper', 1, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(2, 'II. Resource Speaker', 2, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(3, 'III. Training Coordinator/Secretariat', 3, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(4, 'IV. Food Accommodation/Venue', 4, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(5, 'V. Overall Assessment of the Training', 5, '2026-08-19 13:01:56', '2026-08-19 13:01:56'),
	(6, 'VI. Timeliness in Service Delivery', 6, '2026-08-19 13:01:56', '2026-08-19 13:01:56');

-- Dumping structure for table extension_db.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `event_date` date NOT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.events: ~0 rows (approximately)
INSERT IGNORE INTO `events` (`id`, `title`, `description`, `image`, `event_date`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'sample Event', 'lorem ipsum', 'uploads/events/1786537220_6a7c65043b899.jpg', '2026-08-13', 'published', '2026-08-12 12:20:20', '2026-08-12 13:43:07');

-- Dumping structure for table extension_db.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.news: ~0 rows (approximately)
INSERT IGNORE INTO `news` (`id`, `title`, `content`, `image`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Sample News', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quos exercitationem nobis quam eum qui aspernatur, dolore cupiditate, laudantium illo voluptates ea, soluta illum aperiam ipsa? Dicta possimus modi quibusdam eligendi!', 'uploads/news/1786536836_6a7c6384ce344.png', 'published', '2026-08-12 12:13:56', '2026-08-12 12:13:56');

-- Dumping structure for table extension_db.officials
CREATE TABLE IF NOT EXISTS `officials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `category` enum('administrative','research_extension','deans') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `display_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.officials: ~0 rows (approximately)
INSERT IGNORE INTO `officials` (`id`, `name`, `position`, `email`, `category`, `image`, `status`, `display_order`, `created_at`, `updated_at`) VALUES
	(1, 'Dr. Sample Sample', 'Dean', 'sampledean@gmial.com', 'administrative', 'uploads/officials/1786538656_6a7c6aa0225ad.png', 'published', 1, '2026-08-12 12:44:16', '2026-08-12 12:44:16');

-- Dumping structure for table extension_db.otp_verifications
CREATE TABLE IF NOT EXISTS `otp_verifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.otp_verifications: ~1 rows (approximately)
INSERT IGNORE INTO `otp_verifications` (`id`, `email`, `otp_code`, `expires_at`, `created_at`, `is_verified`) VALUES
	(4, 'joenelespejo6@gmail.com', '036144', '2026-09-10 15:19:05', '2026-09-10 14:49:05', 1);

-- Dumping structure for table extension_db.progress_reports
CREATE TABLE IF NOT EXISTS `progress_reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `user_id` int NOT NULL,
  `report_date` date NOT NULL,
  `accomplishments` text,
  `issues` text,
  `next_plan` text,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','revision') DEFAULT 'draft',
  `evaluator_comments` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `submission_id` (`submission_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `progress_reports_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progress_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.progress_reports: ~0 rows (approximately)
INSERT IGNORE INTO `progress_reports` (`id`, `submission_id`, `user_id`, `report_date`, `accomplishments`, `issues`, `next_plan`, `attachment`, `status`, `evaluator_comments`, `created_at`, `updated_at`) VALUES
	(1, 9, 7, '2026-09-11', 'dfhghjm', 'sdfhgh', 'sdfgh', NULL, 'approved', NULL, '2026-09-10 15:54:50', '2026-09-10 16:05:46');

-- Dumping structure for table extension_db.proposals
CREATE TABLE IF NOT EXISTS `proposals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `college_id` int DEFAULT NULL,
  `category` enum('internally_funded','externally_funded') DEFAULT 'internally_funded',
  `status` enum('open','closed') DEFAULT 'open',
  `opening_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `description` text,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `college_id` (`college_id`),
  CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.proposals: ~0 rows (approximately)
INSERT IGNORE INTO `proposals` (`id`, `title`, `college_id`, `category`, `status`, `opening_date`, `closing_date`, `description`, `file_path`, `created_at`, `updated_at`) VALUES
	(1, 'Sample Title', 2, 'internally_funded', 'open', '2026-08-20', '2026-09-19', 'samoksdgjhskdghksdg', 'uploads/proposals/1787145441_6a85ace108683.docx', '2026-08-19 13:17:21', '2026-08-19 14:01:30');

-- Dumping structure for table extension_db.proposal_evaluators
CREATE TABLE IF NOT EXISTS `proposal_evaluators` (
  `id` int NOT NULL AUTO_INCREMENT,
  `proposal_id` int NOT NULL,
  `evaluator_id` int NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  KEY `evaluator_id` (`evaluator_id`),
  CONSTRAINT `proposal_evaluators_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_evaluators_ibfk_2` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.proposal_evaluators: ~0 rows (approximately)

-- Dumping structure for table extension_db.publications
CREATE TABLE IF NOT EXISTS `publications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `year` year NOT NULL,
  `category` enum('research_journal','extension_publication','terminal_report','community_development') NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `description` text,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.publications: ~0 rows (approximately)
INSERT IGNORE INTO `publications` (`id`, `title`, `year`, `category`, `cover_image`, `pdf_file`, `description`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'xfdhh', '2026', 'extension_publication', 'uploads/crest/covers/1787146248_6a85b008ee701.png', 'uploads/crest/pdfs/1787146248_6a85b008ee9b0.pdf', 'dlksdg', 'published', '2026-08-19 13:30:48', '2026-08-19 13:30:48');

-- Dumping structure for table extension_db.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.settings: ~8 rows (approximately)
INSERT IGNORE INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'about_description', 'The Extension and Training Services office is committed to providing community-based programs, trainings, seminars, and outreach activities that promote education, innovation, leadership, and sustainable development.', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(2, 'vision', 'By 2030, ISU as a model Smart-Green University internationally recognized for positive societal transformation.', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(3, 'mission', 'The Isabela State University is committed to developing lifelong and future-ready professionals empowered to become generators of knowledge and agents of positive change for the inclusive and sustainable transformation of communities and viable resource generation management through paramount Smart-Green higher education experience.', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(4, 'objectives', '["Provide quality extension and community services.","Conduct seminars, workshops, and training programs.","Promote community engagement and social responsibility.","Strengthen partnerships with public and private sectors.","Support innovation and sustainable development."]', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(5, 'services', '["Skills and Livelihood Training","Community Outreach Programs","Leadership and Development Seminars","Capacity Building Workshops","Technical Assistance and Consultancy","Educational and Professional Trainings"]', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(6, 'contact_email', 'extension@isu.edu.ph', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(7, 'contact_phone', '+63 912 345 6789', '2026-08-12 12:50:00', '2026-08-12 12:50:00'),
	(8, 'contact_address', 'Cabagan, Isabela, Philippines', '2026-08-12 12:50:00', '2026-08-12 12:50:00');

-- Dumping structure for table extension_db.submissions
CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `proposal_id` int DEFAULT NULL,
  `report_type` enum('proposal','progress','terminal') NOT NULL,
  `status` enum('draft','submitted','approved','rejected','revision','pending_evaluation','under_evaluation') DEFAULT 'draft',
  `evaluator_id` int DEFAULT NULL,
  `evaluator_comments` text,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `form_data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.submissions: ~1 rows (approximately)
INSERT IGNORE INTO `submissions` (`id`, `user_id`, `proposal_id`, `report_type`, `status`, `evaluator_id`, `evaluator_comments`, `viewed_at`, `form_data`, `created_at`, `updated_at`) VALUES
	(9, 7, 1, 'proposal', 'approved', NULL, NULL, NULL, '{"attachment": "uploads/submissions/1789053930_6aa2cbea8bd50.pdf", "basic_info": {"budget": "123456", "status": "New", "lead_unit": "CCSICT", "date_started": "2026-09-22", "project_site": "dgsfghj", "beneficiaries": "adsdfhghj", "project_title": "sdxfcgvhj", "date_completed": "2026-10-01", "funding_agency": "asfdgfhgjhkj", "project_leader": "aesdfj", "cooperating_unit": "CAS", "implementing_campus": "ISU", "cooperating_agencies": "sdfhgjhjk"}, "components": [{"title": "dfghjk", "leader": "sdfhgjhj"}], "admin_remarks": "good skdhfkahsg", "budget_breakdown": {"year1_co": "12345", "year1_ps": "123456", "year2_co": "2134354", "year2_ps": "12345", "year3_co": "21324354", "year3_ps": "1234567", "year1_mooe": "1323454567", "year2_mooe": "12345", "year3_mooe": "21324354"}}', '2026-09-10 15:25:30', '2026-09-10 15:48:35');

-- Dumping structure for table extension_db.submission_evaluators
CREATE TABLE IF NOT EXISTS `submission_evaluators` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `evaluator_id` int NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sub_eval` (`submission_id`,`evaluator_id`),
  KEY `evaluator_id` (`evaluator_id`),
  CONSTRAINT `submission_evaluators_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submission_evaluators_ibfk_2` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.submission_evaluators: ~1 rows (approximately)
INSERT IGNORE INTO `submission_evaluators` (`id`, `submission_id`, `evaluator_id`, `assigned_at`) VALUES
	(3, 9, 5, '2026-09-10 15:34:21');

-- Dumping structure for table extension_db.terminal_reports
CREATE TABLE IF NOT EXISTS `terminal_reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `submission_id` int NOT NULL,
  `user_id` int NOT NULL,
  `completion_date` date NOT NULL,
  `overall_status` enum('completed','on-going','discontinued') NOT NULL,
  `final_summary` text,
  `lessons_learned` text,
  `recommendations` text,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','revision') DEFAULT 'draft',
  `evaluator_comments` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `submission_id` (`submission_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `terminal_reports_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `terminal_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.terminal_reports: ~0 rows (approximately)
INSERT IGNORE INTO `terminal_reports` (`id`, `submission_id`, `user_id`, `completion_date`, `overall_status`, `final_summary`, `lessons_learned`, `recommendations`, `attachment`, `status`, `evaluator_comments`, `created_at`, `updated_at`) VALUES
	(1, 9, 7, '2026-09-09', 'completed', 'dsgfhg', 'shfdgjh', 'dgfg', NULL, 'approved', NULL, '2026-09-10 16:06:50', '2026-09-10 16:12:33');

-- Dumping structure for table extension_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','extensionist','evaluator') NOT NULL,
  `college_id` int DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_college` (`college_id`),
  CONSTRAINT `fk_users_college` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table extension_db.users: ~3 rows (approximately)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `role`, `college_id`, `status`, `created_at`, `updated_at`) VALUES
	(3, 'Admin User', 'admin@isu.edu', '$2y$10$o6D9j6sK7Es6WdMOXHJwO.MOfxPooLRP/SPey92B0aRomWz0USGnq', 'admin', NULL, 'approved', '2026-08-12 03:48:54', '2026-08-12 08:04:46'),
	(4, 'Sample Extensionist', 'sampleextensionist@gmail.com', '$2y$10$mmBLRCelZlWRAHwgaHggUesd4QGVnNvQrplJW6CUOgGTgkVExpdzG', 'extensionist', 2, 'approved', '2026-08-12 08:05:58', '2026-08-12 08:05:58'),
	(5, 'Sample Evaluator', 'sampleevaluator@gmail.com', '$2y$10$9cGKWrxtqqT3HEJ5mXYFGu1rFOTm6HA0711vEOVFcIVpSAF.a271u', 'evaluator', 2, 'approved', '2026-08-12 08:07:21', '2026-08-12 08:07:21'),
	(7, 'aesdfj', 'sampleextensionist1@gmail.com', '$2y$10$ZjAIUYwjmz6Kn5n6IZmysueK0WRb60jEfh1MB2Lq2KdsxA7VH2Uvu', 'extensionist', 2, 'approved', '2026-09-10 14:49:20', '2026-09-17 13:59:09');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
