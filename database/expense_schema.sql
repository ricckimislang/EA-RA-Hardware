-- Create database if not exists
CREATE DATABASE IF NOT EXISTS ea_ra_hardware;
USE ea_ra_hardware;

-- Expense Categories table
CREATE TABLE expense_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Expense Transactions table
CREATE TABLE expense_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    expense_name TEXT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_date DATE NOT NULL,
    receipt_path VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES expense_categories(category_id)
);

-- Insert sample expense categories
INSERT INTO expense_categories (name, description) VALUES
('Office Supplies', 'Expenses for office stationery and supplies'),
('Utilities', 'Electricity, water, and internet bills'),
('Maintenance', 'Building and equipment maintenance costs'),
('Travel', 'Employee travel and transportation expenses'),
('Miscellaneous', 'Other uncategorized expenses');

-- Insert sample expense transactions
INSERT INTO expense_transactions (transaction_id, category_id, expense_name, amount, transaction_date, receipt_path, notes) VALUES
(1, 1, 'Bond papers', 150.00, '2025-03-28', 'assets/receipts/office_oct1.jpg', 'Monthly office supplies'),
(2, 2, 'Socetecco Bill', 2500.00, '2025-04-05', 'assets/receipts/electricity_oct5.pdf', 'March electricity bill'),
(3, 3, 'Aircon repair', 1200.00, '2025-02-20', 'assets/receipts/maintenance_oct10.pdf', 'AC unit repair'),
(4, 4, 'Fare', 800.00, '2025-03-15', 'assets/receipts/travel_oct15.jpg', 'Employee site visit expenses'),

(5, 4, 'Site commute', 300.00, '2025-01-09', 'assets/receipts/fare_jan.jpg', 'Commute to supplier site'),
(6, 2, 'Water bill', 2300.00, '2025-01-10', 'assets/receipts/water_bill_jan.pdf', 'Water bill Jan'),
(7, 1, 'Printer ink', 400.00, '2025-01-15', 'assets/receipts/printer_ink.jpg', 'Printer ink cartridge'),
(8, 10, 'Training Cert.', 2500.00, '2025-01-25', 'assets/receipts/training_cert.pdf', 'Web training certificate'),
(9, 12, 'Business Permit', 4500.00, '2025-01-31', 'assets/receipts/business_tax_renewal.pdf', 'Annual business permit'),

(10, 5, 'Meeting snacks', 320.00, '2025-02-01', 'assets/receipts/misc_feb28.jpg', 'Snacks for meeting'),
(11, 12, 'Brgy Clearance', 350.00, '2025-02-02', 'assets/receipts/barangay_clearance.pdf', 'Barangay clearance fee'),
(12, 6, 'FB Ads', 1500.00, '2025-02-10', 'assets/receipts/ads_fb.jpg', 'Facebook ads for promo'),
(13, 1, 'Pens & Folders', 220.00, '2025-02-14', 'assets/receipts/office_feb.jpg', 'Pens and folders'),
(14, 11, 'Health Insurance', 5200.00, '2025-02-18', 'assets/receipts/health_insurance.jpg', 'Health insurance premium'),
(15, 9, 'Zoom Sub', 799.00, '2025-02-20', 'assets/receipts/software_zoom.jpg', 'Zoom subscription'),
(16, 6, 'Printed Flyers', 900.00, '2025-02-22', 'assets/receipts/flyers_printed.jpg', 'Promotional flyers'),
(17, 7, 'Feb Salary', 11500.00, '2025-02-28', 'assets/receipts/salary_feb.jpg', 'Feb payroll'),

(18, 9, 'Figma Sub', 499.00, '2025-03-05', 'assets/receipts/figma_sub.png', 'Figma annual subscription'),
(19, 10, 'Team Training', 1800.00, '2025-03-07', 'assets/receipts/team_training_march.pdf', 'Team development training'),
(20, 8, 'Legal Consult', 3500.00, '2025-03-12', 'assets/receipts/legal_consult_march.pdf', 'Legal consultation'),
(21, 3, 'Plumbing Repair', 950.00, '2025-03-18', 'assets/receipts/plumbing_march.jpg', 'Plumbing service'),
(22, 8, 'Consulting Session', 2000.00, '2025-03-29', 'assets/receipts/consulting_april.pdf', 'Marketing strategy session'),
(23, 5, 'Cleaning Supplies', 110.00, '2025-03-30', 'assets/receipts/misc_cleaning.jpg', 'Cleaning supplies'),
(24, 7, 'April Salary', 12000.00, '2025-04-10', 'assets/receipts/salary_april.jpg', 'April staff payroll');
