--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE IF NOT EXISTS `expense_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Office Supplies', 'Expenses for office stationery and supplies', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(2, 'Utilities', 'Electricity, water, and internet bills', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(3, 'Maintenance', 'Building and equipment maintenance costs', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(4, 'Travel', 'Employee travel and transportation expenses', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(5, 'Miscellaneous', 'Other uncategorized expenses', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(6, 'Marketing & Advertising', 'Costs for promotions, online ads, flyers, and campaigns', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(7, 'Employee Salaries', 'Regular wages, benefits, and bonuses paid to staff', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(8, 'Professional Services', 'Payments for accounting, legal, and consulting services', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(9, 'Software & Subscriptions', 'Licensing fees for tools, software, and cloud platforms', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(10, 'Training & Development', 'Workshops, courses, and certifications for employees', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(11, 'Insurance', 'Expenses for business insurance, health, property, etc.', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(12, 'Taxes & Government Fees', 'Annual taxes, business permits, and other regulatory costs', '2025-04-18 06:29:56', '2025-04-18 06:29:56');

--
-- Table structure for table `expense_transactions`
--

DROP TABLE IF EXISTS `expense_transactions`;
CREATE TABLE IF NOT EXISTS `expense_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `expense_name` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_transactions`
--

INSERT INTO `expense_transactions` (`transaction_id`, `category_id`, `expense_name`, `amount`, `transaction_date`, `receipt_path`, `notes`, `created_at`) VALUES
(2, 2, 'Socetecco Bill', 2500.00, '2025-04-05', NULL, 'March electricity bill', '2025-04-18 12:35:19'),
(3, 3, 'Aircon repair', 1200.00, '2025-02-20', 'assets/images/receipts/receipt_6802474da52d1.jpg', 'AC unit repair', '2025-04-18 12:35:19'),
(4, 4, 'Fare', 800.00, '2025-03-15', NULL, 'Employee site visit expenses', '2025-04-18 12:35:19'),
(5, 4, 'Site commute', 300.00, '2025-01-09', NULL, 'Commute to supplier site', '2025-04-18 12:35:19'),
(6, 2, 'Water bill', 2300.00, '2025-01-10', NULL, 'Water bill Jan', '2025-04-18 12:35:19'),
(7, 1, 'Printer ink', 400.00, '2025-01-15', NULL, 'Printer ink cartridge', '2025-04-18 12:35:19'),
(8, 10, 'Training Cert.', 2500.00, '2025-01-25', NULL, 'Web training certificate', '2025-04-18 12:35:19'),
(9, 12, 'Business Permit', 4500.00, '2025-01-31', NULL, 'Annual business permit', '2025-04-18 12:35:19'),
(10, 5, 'Meeting snacks', 320.00, '2025-02-01', NULL, 'Snacks for meeting', '2025-04-18 12:35:19'),
(12, 6, 'FB Ads', 1500.00, '2025-02-10', NULL, 'Facebook ads for promo', '2025-04-18 12:35:19'),
(13, 1, 'Pens & Folders', 220.00, '2025-02-14', NULL, 'Pens and folders', '2025-04-18 12:35:19'),
(14, 11, 'Health Insurance', 5200.00, '2025-02-18', NULL, 'Health insurance premium', '2025-04-18 12:35:19'),
(15, 9, 'Zoom Sub', 799.00, '2025-02-20', NULL, 'Zoom subscription', '2025-04-18 12:35:19'),
(16, 6, 'Printed Flyers', 900.00, '2025-02-22', NULL, 'Promotional flyers', '2025-04-18 12:35:19'),
(17, 7, 'Feb Salary', 11500.00, '2025-02-28', NULL, 'Feb payroll', '2025-04-18 12:35:19'),
(18, 9, 'Figma Sub', 499.00, '2025-03-05', NULL, 'Figma annual subscription', '2025-04-18 12:35:19'),
(19, 10, 'Team Training', 1800.00, '2025-03-07', NULL, 'Team development training', '2025-04-18 12:35:19'),
(21, 3, 'Plumbing Repair', 950.00, '2025-03-18', NULL, 'Plumbing service', '2025-04-18 12:35:19'),
(22, 8, 'Consulting Session', 2000.00, '2025-03-29', NULL, 'Marketing strategy session', '2025-04-18 12:35:19'),
(23, 5, 'Cleaning Supplies', 110.00, '2025-03-30', NULL, 'Cleaning supplies', '2025-04-18 12:35:19');
