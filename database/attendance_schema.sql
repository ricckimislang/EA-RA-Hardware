-- Create database if not exists
CREATE DATABASE IF NOT EXISTS ea_ra_hardware;
USE ea_ra_hardware;

-- QR Codes table for employee attendance
CREATE TABLE IF NOT EXISTS employee_qr_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    qr_code_hash VARCHAR(25) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Attendance records table
CREATE TABLE IF NOT EXISTS attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    time_in DATETIME NOT NULL,
    time_out DATETIME,
    total_hours DECIMAL(5,2),
    status ENUM('present', 'late', 'half-day', 'absent') DEFAULT 'present',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Attendance settings table
CREATE TABLE IF NOT EXISTS attendance_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(100) NOT NULL,
    setting_value VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO attendance_settings (setting_name, setting_value, description) VALUES
('work_start_time', '08:00:00', 'Regular work start time'),
('work_end_time', '17:00:00', 'Regular work end time'),
('late_threshold_minutes', '15', 'Minutes after work start time to mark as late'),
('half_day_hours', '4', 'Minimum hours to be counted as half-day');

-- Generate QR codes for existing employees
INSERT INTO employee_qr_codes (employee_id, qr_code_hash)
SELECT id, MD5(CONCAT(id, RAND(), NOW())) FROM employees;
