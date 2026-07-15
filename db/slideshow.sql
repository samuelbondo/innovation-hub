CREATE TABLE IF NOT EXISTS `slideshow_images` (
  `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
  `src_type`   enum('upload','url') NOT NULL DEFAULT 'url',
  `src`        varchar(500) NOT NULL,
  `caption`    varchar(200) DEFAULT NULL,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
