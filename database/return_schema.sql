-- Table structure for return transactions
CREATE TABLE IF NOT EXISTS `return_transactions` (
  `return_id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(10) NOT NULL COMMENT 'References product_sales.transaction_id',
  `return_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `return_type` enum('refund','exchange','store_credit') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `processed_by` int NOT NULL COMMENT 'References users.id',
  `customer_name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`return_id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `processed_by` (`processed_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for return items
CREATE TABLE IF NOT EXISTS `return_items` (
  `return_item_id` int NOT NULL AUTO_INCREMENT,
  `return_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `condition` enum('good','damaged','defective') NOT NULL DEFAULT 'good',
  `reason_code` enum('wrong_item','defective','customer_dissatisfaction','pricing_error','other') NOT NULL,
  `added_to_inventory` tinyint(1) NOT NULL DEFAULT '0',
  `other_reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`return_item_id`),
  KEY `return_id` (`return_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for store credits
CREATE TABLE IF NOT EXISTS `store_credits` (
  `credit_id` int NOT NULL AUTO_INCREMENT,
  `return_id` int NOT NULL,
  `credit_amount` decimal(10,2) NOT NULL,
  `credit_code` varchar(20) NOT NULL,
  `issue_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` datetime NOT NULL,
  `used_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`credit_id`),
  UNIQUE KEY `credit_code` (`credit_code`),
  KEY `return_id` (`return_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci; 