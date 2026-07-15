-- Warning: column statistics not supported by the server.
-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: group1_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `credits` tinyint(3) unsigned NOT NULL DEFAULT 3,
  `year_level` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `semester` tinyint(3) unsigned NOT NULL DEFAULT 1,\n  `description` text DEFAULT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'CS101','Introduction to Programming',3,1,1,1,'2026-07-07 11:50:25'),(2,'CS102','Mathematics for Computing',3,1,1,1,'2026-07-07 11:50:25'),(3,'CS103','Computer Organization',3,1,2,1,'2026-07-07 11:50:25'),(4,'CS104','Discrete Mathematics',3,1,2,1,'2026-07-07 11:50:25'),(5,'CS201','Data Structures & Algorithms',3,2,1,1,'2026-07-07 11:50:25'),(6,'CS202','Database Systems',3,2,1,1,'2026-07-07 11:50:25'),(7,'CS203','Operating Systems',3,2,2,1,'2026-07-07 11:50:25'),(8,'CS204','Computer Networks',3,2,2,1,'2026-07-07 11:50:25'),(9,'CS301','Software Engineering',3,3,1,1,'2026-07-07 11:50:25'),(10,'CS302','Artificial Intelligence',3,3,1,1,'2026-07-07 11:50:25'),(11,'CS303','Web Development',3,3,2,1,'2026-07-07 11:50:25'),(12,'CS304','Cybersecurity',3,3,2,1,'2026-07-07 11:50:25'),(13,'CS401','Machine Learning',3,4,1,1,'2026-07-07 11:50:25'),(14,'CS402','Cloud Computing',3,4,1,1,'2026-07-07 11:50:25'),(15,'CS403','Final Year Project I',3,4,2,1,'2026-07-07 11:50:25'),(16,'CS404','Final Year Project II',3,4,2,1,'2026-07-07 11:50:25');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `head` varchar(100) NOT NULL,
  `icon` varchar(20) NOT NULL DEFAULT '?',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `faculty_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Computer Science','Dr. A. Mensah','🖥️','Covers algorithms, programming, software engineering, artificial intelligence, and computer systems.','2026-07-07 09:24:35',1),(2,'Information Technology','Prof. B. Osei','🌐','Focuses on networking, cybersecurity, database management, and IT infrastructure.','2026-07-07 09:24:35',1),(3,'Business Administration','Dr. C. Kamau','📊','Covers management, marketing, finance, entrepreneurship, and organizational behavior.','2026-07-07 09:24:35',2),(4,'Engineering','Prof. D. Johnson','⚙️','Includes civil, mechanical, electrical, and software engineering disciplines.','2026-07-07 09:24:35',3),(5,'Mathematics','Dr. E. Mwangi','📐','Pure and applied mathematics including statistics, calculus, and discrete mathematics.','2026-07-07 09:24:35',4),(6,'Sciences','Prof. F. Asante','🔬','Biology, chemistry, physics, and environmental science programs.','2026-07-07 09:24:35',4),(7,'Arts & Humanities','Dr. G. Boateng','🎭','Literature, history, philosophy, linguistics, and cultural studies.','2026-07-07 09:24:35',5),(8,'Education','Prof. H. Adjei','📚','Teacher training, curriculum development, educational psychology, and pedagogy.','2026-07-07 09:24:35',5);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculties`
--

DROP TABLE IF EXISTS `faculties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `dean` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculties`
--

LOCK TABLES `faculties` WRITE;
/*!40000 ALTER TABLE `faculties` DISABLE KEYS */;
INSERT INTO `faculties` VALUES (1,'Faculty of Computing & Technology','Prof. A. Mensah','2026-07-07 11:50:25'),(2,'Faculty of Business','Prof. B. Osei','2026-07-07 11:50:25'),(3,'Faculty of Engineering','Prof. C. Kamau','2026-07-07 11:50:25'),(4,'Faculty of Sciences','Prof. D. Johnson','2026-07-07 11:50:25'),(5,'Faculty of Arts','Prof. E. Boateng','2026-07-07 11:50:25');
/*!40000 ALTER TABLE `faculties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `k` varchar(60) NOT NULL,
  `v` text NOT NULL,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('contact_address','123 Dev Street, Tech City'),('contact_email','groupone@email.com'),('contact_phone','+1 234 567 890'),('footer_copy','© 2025 Group One. All rights reserved.'),('footer_note','Web Development Project 2025'),('system_name','Group One'),('system_tagline','Student Management System');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_documents`
--

DROP TABLE IF EXISTS `student_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `doc_type` varchar(80) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `uploaded_by` enum('student','admin') NOT NULL DEFAULT 'student',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `student_documents_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_documents`
--

LOCK TABLES `student_documents` WRITE;
/*!40000 ALTER TABLE `student_documents` DISABLE KEYS */;
INSERT INTO `student_documents` VALUES (4,'STU-2026-001','Medical Certificate','uploads/docs/STU-2026-001_1783503139_Tracking__delivery_status_for_DHL_Express_shipments___MyDHL_.pdf','Tracking, delivery status for DHL Express shipments _ MyDHL+.pdf','admin','2026-07-08 09:32:19'),(5,'STU-2026-001','Birth Certificate','uploads/docs/STU-2026-001_1783503166_pngg.png','pngg.png','admin','2026-07-08 09:32:46'),(6,'STU-2026-004','Birth Certificate','uploads/docs/STU-2026-004_1783503578_Tracking__delivery_status_for_DHL_Express_shipments___MyDHL_.pdf','Tracking, delivery status for DHL Express shipments _ MyDHL+.pdf','student','2026-07-08 09:39:38'),(7,'STU-2026-004','Birth Certificate','uploads/docs/STU-2026-004_1783504128_pngg.png','pngg.png','student','2026-07-08 09:48:48');
/*!40000 ALTER TABLE `student_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `fname` varchar(80) NOT NULL,
  `lname` varchar(80) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `year_of_study` tinyint(3) unsigned NOT NULL,
  `dob` date NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Inactive','Suspended') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  `admission_status` enum('Pending','Under Review','Approved','Rejected') NOT NULL DEFAULT 'Approved',
  `admission_note` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_student_dept` (`department_id`),
  CONSTRAINT `fk_student_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'STU-2026-001','Alice','Johnson','alice@example.com','+1 234 567 001',1,2,'2003-05-14','12 Maple St, Tech City','Active','2026-07-07 09:24:39','uploads/STU-2026-001.png','Approved',NULL,NULL),(2,'STU-2026-002','Bob','Mwangi','bob@example.com','+1 234 567 002',2,1,'2004-08-22','34 Oak Ave, Tech City','Active','2026-07-07 09:24:39',NULL,'Approved',NULL,NULL),(4,'STU-2026-004','David','Kamau','david@example.com','+1 234 567 004',4,4,'2001-03-18','78 Elm Blvd, Tech City','Active','2026-07-07 09:24:39',NULL,'Approved',NULL,NULL),(21,'STU-2026-005','George','Kiazolu','test@gmail.com','+250775990799',3,2,'2009-07-07','Chicken Soup Factory Community','Inactive','2026-07-08 09:48:35',NULL,'Pending',NULL,'2026-07-08 09:48:35');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = cp850 */ ;
/*!50003 SET character_set_results = cp850 */ ;
/*!50003 SET collation_connection  = cp850_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER before_student_insert
BEFORE INSERT ON students
FOR EACH ROW
BEGIN
    DECLARE next_seq INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(student_id, '-', -1) AS UNSIGNED)), 0) + 1
    INTO next_seq FROM students;
    SET NEW.student_id = CONCAT('STU-', YEAR(CURDATE()), '-', LPAD(next_seq, 3, '0'));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `teacher_courses`
--

DROP TABLE IF EXISTS `teacher_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` int(10) unsigned NOT NULL,
  `course_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tc` (`teacher_id`,`course_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `teacher_courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teacher_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_courses`
--

LOCK TABLES `teacher_courses` WRITE;
/*!40000 ALTER TABLE `teacher_courses` DISABLE KEYS */;
INSERT INTO `teacher_courses` VALUES (1,7,1),(2,7,2),(3,7,3),(4,7,4),(5,8,5),(6,8,6),(7,8,7),(8,8,8),(9,9,9),(10,9,10),(11,10,11),(12,10,12),(13,10,13),(14,10,14),(15,10,15),(16,10,16);
/*!40000 ALTER TABLE `teacher_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','student','teacher') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `department_id` int(10) unsigned DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Sam Admin','sam@gmail.com','$2y$10$r/D.7bLfD5oSBjTeAky8g.QHmIjwgzsn/hybp9Z64aVJfYosyizXG','admin','2026-07-07 09:24:37',NULL,NULL,'uploads/user_1.png'),(7,'Dr. Alan Mensah','alan.mensah@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:28',1,NULL,NULL),(8,'Prof. Betty Osei','betty.osei@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:29',1,NULL,NULL),(9,'Dr. Charles Nkrumah','charles.nkrumah@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:29',1,NULL,NULL),(10,'Prof. Diana Asante','diana.asante@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:29',1,NULL,NULL),(11,'Dr. Eric Boateng','eric.boateng@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',2,NULL,NULL),(12,'Prof. Fiona Adjei','fiona.adjei@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',2,NULL,NULL),(13,'Dr. George Kamau','george.kamau@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',3,NULL,NULL),(14,'Prof. Helen Darko','helen.darko@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',3,NULL,NULL),(15,'Dr. Isaac Tetteh','isaac.tetteh@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',4,NULL,NULL),(16,'Prof. Janet Mwangi','janet.mwangi@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',4,NULL,NULL),(17,'Dr. Kevin Asare','kevin.asare@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',5,NULL,NULL),(18,'Prof. Linda Owusu','linda.owusu@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:30',6,NULL,NULL),(19,'Dr. Michael Frimpong','michael.frimpong@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:31',7,NULL,NULL),(20,'Prof. Nancy Quaye','nancy.quaye@sms.edu','$2y$12$shf6ymjUlnvyB/4UP4WLqOlEwK/n941Q9xUSQZGkhEeD2MdiBIvMm','teacher','2026-07-07 11:54:31',8,NULL,NULL),(23,'Alice Johnson','alice@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student','2026-07-08 09:40:57',NULL,NULL,NULL),(24,'Bob Mwangi','bob@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student','2026-07-08 09:40:57',NULL,NULL,NULL),(25,'David Kamau','david@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student','2026-07-08 09:40:57',NULL,NULL,NULL),(26,'George Kiazolu','test@gmail.com','$2y$10$LSYCteO3fM8NPyI5dvYZSe6xoAJFUW7lg6UzTMaqfa8vLLlkHfpl2','student','2026-07-08 09:48:35',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-08 12:13:07
