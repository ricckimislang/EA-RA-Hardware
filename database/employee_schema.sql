CREATE TABLE IF NOT EXISTS positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    base_salary DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    position_id INT NOT NULL,
    employment_type ENUM('full-time', 'part-time') NOT NULL,
    salary_rate_type ENUM('daily', 'monthly', 'hourly') NOT NULL,
    date_hired DATE NOT NULL,
    overtime_rate DECIMAL(10,2) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email_address VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (position_id) REFERENCES positions(id)
);

CREATE TABLE IF NOT EXISTS employee_government_ids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    sss_number VARCHAR(20),
    sss_file_path VARCHAR(255),
    pagibig_number VARCHAR(20),
    pagibig_file_path VARCHAR(255),
    philhealth_number VARCHAR(20),
    philhealth_file_path VARCHAR(255),
    tin_number VARCHAR(20),
    tin_file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);


INSERT INTO positions (title, base_salary, description) VALUES
('Store Manager', 25000.00, 'Oversees store operations and staff'),
('Cashier', 15000.00, 'Handles customer transactions and receipts'),
('Inventory Clerk', 14000.00, 'Manages inventory records and stock levels'),
('Sales Associate', 13000.00, 'Assists customers and promotes products'),
('Delivery Staff', 12000.00, 'Handles product deliveries and logistics');

INSERT INTO employees (full_name, position_id, employment_type, salary_rate_type, date_hired, overtime_rate, contact_number, email_address) VALUES
('Juan Dela Cruz', 1, 'full-time', 'monthly', '2022-05-10', 150.00, '09171234567', 'juan.manager@email.com'),
('Ana Santos', 2, 'full-time', 'monthly', '2023-01-20', 100.00, '09181234567', 'ana.santos@email.com'),
('Marco Reyes', 2, 'part-time', 'daily', '2023-06-01', 80.00, '09221234567', 'marco.reyes@email.com'),
('Ella Lopez', 3, 'full-time', 'monthly', '2021-11-15', 90.00, '09331234567', 'ella.lopez@email.com'),
('John Mendoza', 3, 'part-time', 'daily', '2024-02-10', 70.00, '09441234567', 'john.mendoza@email.com');


INSERT INTO employee_government_ids (employee_id, sss_number, sss_file_path, pagibig_number, pagibig_file_path, philhealth_number, philhealth_file_path, tin_number, tin_file_path) VALUES
(1, '34-1234567-8', NULL, '1234-5678-9101', NULL, '12-345678901', NULL, '123-456-789', NULL),
(2, '34-2234567-8', NULL, '2234-5678-9101', NULL, '22-345678901', NULL, '223-456-789', NULL),
(3, '34-3234567-8', NULL, '3234-5678-9101', NULL, '32-345678901', NULL, '323-456-789', NULL),
(4, '34-4234567-8', NULL, '4234-5678-9101', NULL, '42-345678901', NULL, '423-456-789', NULL),
(5, '34-5234567-8', NULL, '5234-5678-9101', NULL, '52-345678901', NULL, '523-456-789', NULL);