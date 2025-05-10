-- Add cash_advances table for employee cash advance management
-- This should be run after the main database setup

CREATE TABLE IF NOT EXISTS `cash_advances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `request_date` date NOT NULL,
  `approval_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'cash',
  `notes` text DEFAULT NULL,
  `payroll_id` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `payroll_id` (`payroll_id`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `cash_advances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cash_advances_ibfk_2` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cash_advances_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add setting for maximum cash advance percentage
INSERT INTO `pay_settings` (`setting_name`, `setting_value`, `description`) 
VALUES ('max_cash_advance_percent', '30', 'Maximum percentage of monthly salary that can be given as cash advance') 
ON DUPLICATE KEY UPDATE `setting_value` = '30';

-- Add function to integrate cash advances with payroll processing
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `process_cash_advances_for_payroll`(IN payroll_id INT, IN employee_id INT)
BEGIN
    -- Find all approved cash advances for this employee that haven't been deducted yet
    UPDATE cash_advances 
    SET payroll_id = payroll_id,
        status = 'paid'
    WHERE employee_id = employee_id 
      AND status = 'approved'
      AND payroll_id IS NULL;
    
    -- Return the total amount to deduct
    SELECT SUM(amount) as total_deduction 
    FROM cash_advances 
    WHERE payroll_id = payroll_id AND employee_id = employee_id;
END //
DELIMITER ;

-- Create a view for cash advance summary reports
CREATE OR REPLACE VIEW `cash_advance_summary` AS
SELECT 
    e.full_name AS employee_name,
    p.title AS position,
    ca.status,
    COUNT(ca.id) AS count,
    SUM(ca.amount) AS total_amount,
    MAX(ca.request_date) AS latest_request,
    MIN(CASE WHEN ca.status = 'pending' THEN ca.request_date ELSE NULL END) AS oldest_pending
FROM 
    cash_advances ca
JOIN 
    employees e ON ca.employee_id = e.id
JOIN 
    positions p ON e.position_id = p.id
GROUP BY 
    e.id, ca.status; 