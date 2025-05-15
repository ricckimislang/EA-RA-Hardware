--
-- Table structure for table `credit_usage_history`
--

DROP TABLE IF EXISTS `credit_usage_history`;
CREATE TABLE IF NOT EXISTS `credit_usage_history` (
  `usage_id` int NOT NULL AUTO_INCREMENT,
  `credit_id` int NOT NULL,
  `transaction_id` varchar(10) NOT NULL,
  `amount_used` decimal(10,2) NOT NULL,
  `remaining_after` decimal(10,2) NOT NULL,
  `usage_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usage_id`),
  KEY `credit_id` (`credit_id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci; 