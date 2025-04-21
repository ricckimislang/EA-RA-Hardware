DROP TABLE IF EXISTS `attendance_settings`;
CREATE TABLE IF NOT EXISTS `attendance_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_settings`
--

INSERT INTO `attendance_settings` (`id`, `setting_name`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'work_start_time', '08:00:00', 'Regular work start time', '2025-04-19 09:08:43', '2025-04-19 09:08:43'),
(2, 'work_end_time', '17:00:00', 'Regular work end time', '2025-04-19 09:08:43', '2025-04-19 09:08:43'),
(3, 'late_threshold_minutes', '15', 'Minutes after work start time to mark as late', '2025-04-19 09:08:43', '2025-04-19 09:08:43'),
(4, 'half_day_hours', '4', 'Minimum hours to be counted as half-day', '2025-04-19 09:08:43', '2025-04-19 09:08:43');
