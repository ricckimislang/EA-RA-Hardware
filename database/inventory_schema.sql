--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Stanley', 'Quality hand tools and storage solutions', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(2, 'DeWalt', 'Professional-grade power tools and equipment', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(3, 'Makita', 'Innovative power tools and outdoor equipment', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(4, 'Bosch', 'High-performance tools and accessories', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(5, '3M', 'Industrial and consumer products', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(6, 'Sanrio', NULL, '2025-04-18 05:16:17', '2025-04-18 05:16:17');

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tools', 'Hand tools and power tools for construction and repairs', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(2, 'Hardware', 'General hardware items including fasteners and fittings', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(3, 'Electrical', 'Electrical supplies, wiring, and components', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(4, 'Plumbing', 'Plumbing supplies, pipes, and fixtures', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(5, 'Paint', 'Paint, primers, and painting supplies', '2025-04-18 04:51:27', '2025-04-18 04:51:27');



CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `unit` varchar(20) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock_level` int DEFAULT '0',
  `reorder_point` int DEFAULT '10',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `sku` (`sku`),
  UNIQUE KEY `barcode` (`barcode`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `sku`, `barcode`, `name`, `description`, `category_id`, `brand_id`, `unit`, `cost_price`, `selling_price`, `stock_level`, `reorder_point`, `created_at`, `updated_at`) VALUES
(1, 'HMR-001', '1234567890', 'Claw Hammers', '16oz steel claw hammer with rubber grip', 1, 1, 'piece', 12.50, 24.99, 25, 10, '2025-04-18 04:51:27', '2025-04-21 03:15:24'),
(2, 'DRL-001', '2345678901', 'Cordless Drill', '20V max lithium-ion cordless drill', 1, 2, 'piece', 89.99, 149.99, 23, 8, '2025-04-18 04:51:27', '2025-04-21 03:16:18'),
(3, 'SCW-001', '3456789012', 'Screwdriver Set', '6-piece precision screwdriver set', 1, 1, 'set', 15.99, 29.99, 11, 10, '2025-04-18 04:51:27', '2025-04-20 05:17:45'),
(4, 'PLR-001', '4567890123', 'Pliers Set', '3-piece pliers set with wire cutter', 1, 4, 'set', 24.99, 44.99, 29, 12, '2025-04-18 04:51:27', '2025-04-21 03:36:23'),
(5, 'NLS-001', '5678901234', 'Nails Assorted', 'Box of 500 assorted nails', 2, 1, 'box', 8.99, 16.99, 95, 25, '2025-04-18 04:51:27', '2025-04-21 03:35:45'),
(7, 'PVC-001', '7890123456', 'PVC Pipe 2\"', '10ft PVC pipe 2-inch diameter', 4, 4, 'piece', 9.99, 18.99, 40, 15, '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(8, 'PNT-001', '8901234567', 'White Paint', '1-gallon interior white paint', 5, 5, 'gallon', 19.99, 34.99, 20, 8, '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(9, 'TP-001', '56487215', 'Electrical Tapes', 'Electrical tapes 6 meters 50pcs/box.', 3, 1, 'piece', 70.00, 150.00, 50, 10, '2025-04-18 05:31:18', '2025-04-18 06:01:22'),
(10, 'SPL-001', '09932323', 'sample', 'sample lang ni', 5, 6, 'piece', 20.00, 50.00, 59, 15, '2025-04-18 10:01:54', '2025-04-21 03:36:23');


--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
CREATE TABLE IF NOT EXISTS `stock_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `transaction_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `notes` text,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`transaction_id`, `product_id`, `transaction_type`, `quantity`, `unit_price`, `total_amount`, `reference_no`, `notes`, `transaction_date`, `created_at`) VALUES
(1, 2, 'stock_in', 20, 0.00, 0.00, NULL, '', '2025-04-21 03:15:04', '2025-04-21 03:15:04'),
(2, 1, 'stock_in', 20, 0.00, 0.00, NULL, 're-stock', '2025-04-21 03:15:24', '2025-04-21 03:15:24'),
(3, 2, 'stock_out', 5, 0.00, 0.00, NULL, 'wrong count', '2025-04-21 03:16:18', '2025-04-21 03:16:18');


--
-- Table structure for table `product_sales`
--

DROP TABLE IF EXISTS `product_sales`;
CREATE TABLE IF NOT EXISTS `product_sales` (
  `sale_id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(10) NOT NULL,
  `cashier_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `product_id` int NOT NULL,
  `quantity_sold` int NOT NULL,
  `discount_applied` decimal(5,2) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `sale_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_transaction_id` (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`sale_id`, `transaction_id`, `cashier_name`, `product_id`, `quantity_sold`, `discount_applied`, `sale_price`, `sale_timestamp`) VALUES
(1, '00001', 'Juan Dela ', 5, 3, 10.19, 40.78, '2025-04-21 11:34:55'),
(2, '00002', 'Juan Dela ', 5, 1, 3.40, 13.59, '2025-04-21 11:35:34'),
(3, '00003', 'Juan Dela ', 5, 1, 3.40, 13.59, '2025-04-21 11:35:45'),
(4, '00004', 'Juan Dela Cruz', 4, 1, 19.00, 75.99, '2025-04-21 11:36:23'),
(5, '00004', 'Juan Dela Cruz', 10, 1, 19.00, 75.99, '2025-04-21 11:36:23');
