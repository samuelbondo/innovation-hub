-- Enrollment windows: admin sets open/close dates per course (or globally)
CREATE TABLE IF NOT EXISTS `enrollment_windows` (
  `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_id`  int(10) unsigned DEFAULT NULL COMMENT 'NULL = global window',
  `open_from`  datetime NOT NULL,
  `open_until` datetime NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_course` (`course_id`),
  CONSTRAINT `ew_course_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
