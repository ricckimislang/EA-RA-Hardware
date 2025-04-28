-- Sample data for EA RA Hardware Database

-- Insert positions first
INSERT INTO `positions` (`title`, `base_salary`, `description`) VALUES
('Store Manager', 25000.00, 'Oversees store operations and staff'),
('Assistant Manager', 22000.00, 'Assists store manager with daily operations'),
('Cashier', 15000.00, 'Handles customer transactions and receipts'),
('Senior Cashier', 17000.00, 'Experienced cashier with additional responsibilities'),
('Inventory Clerk', 14000.00, 'Manages inventory records and stock levels'),
('Sales Associate', 13000.00, 'Assists customers and promotes products'),
('Senior Sales Associate', 17000.00, 'Experienced sales staff with product expertise'),
('Warehouse Supervisor', 28000.00, 'Manages warehouse operations and inventory'),
('Warehouse Staff', 12000.00, 'Handles product storage and movement'),
('Maintenance Technician', 16000.00, 'Handles store repairs and equipment maintenance'),
('Customer Service Representative', 14500.00, 'Handles customer inquiries and returns'),
('Security Guard', 13000.00, 'Ensures store security and safety'),
('Delivery Driver', 12000.00, 'Handles product deliveries and logistics'),
('Janitor', 12000.00, 'Maintains store cleanliness'),
('IT Support', 20000.00, 'Handles technical issues and system maintenance');

-- Insert employees
INSERT INTO `employees` (`full_name`, `position_id`, `employment_type`, `salary_rate_type`, `date_hired`, `overtime_rate`, `contact_number`, `email_address`) VALUES
-- Management (2)
('Juan Dela Cruz', 1, 'full-time', 'monthly', '2023-01-15', 150.00, '09171234567', 'juan.delacruz@example.com'),
('Maria Santos', 2, 'full-time', 'monthly', '2023-02-20', 140.00, '09171234568', 'maria.santos@example.com'),

-- Cashiers (3)
('Pedro Reyes', 3, 'full-time', 'monthly', '2023-03-10', 100.00, '09171234569', 'pedro.reyes@example.com'),
('Ana Martinez', 3, 'full-time', 'monthly', '2023-04-05', 100.00, '09171234570', 'ana.martinez@example.com'),
('Luis Garcia', 4, 'full-time', 'monthly', '2023-05-12', 110.00, '09171234571', 'luis.garcia@example.com'),

-- Warehouse and Inventory (5)
('Carlos Lopez', 5, 'full-time', 'monthly', '2023-06-15', 90.00, '09171234572', 'carlos.lopez@example.com'),
('Elena Torres', 8, 'full-time', 'monthly', '2023-07-20', 120.00, '09171234573', 'elena.torres@example.com'),
('Miguel Ramos', 9, 'full-time', 'monthly', '2023-08-10', 80.00, '09171234574', 'miguel.ramos@example.com'),
('Rosa Mendoza', 9, 'full-time', 'monthly', '2023-09-05', 80.00, '09171234575', 'rosa.mendoza@example.com'),
('Jose Cruz', 9, 'full-time', 'monthly', '2023-10-12', 80.00, '09171234576', 'jose.cruz@example.com'),

-- Sales and Customer Service (5)
('Sofia Rivera', 6, 'full-time', 'monthly', '2023-11-15', 90.00, '09171234577', 'sofia.rivera@example.com'),
('Antonio Gomez', 7, 'full-time', 'monthly', '2023-12-20', 110.00, '09171234578', 'antonio.gomez@example.com'),
('Carmen Diaz', 6, 'full-time', 'monthly', '2024-01-10', 90.00, '09171234579', 'carmen.diaz@example.com'),
('Ricardo Castro', 11, 'full-time', 'monthly', '2024-02-05', 95.00, '09171234580', 'ricardo.castro@example.com'),
('Isabel Ortega', 11, 'full-time', 'monthly', '2024-03-12', 95.00, '09171234581', 'isabel.ortega@example.com'),

-- Support Staff (5)
('Fernando Ruiz', 10, 'full-time', 'monthly', '2024-04-15', 100.00, '09171234582', 'fernando.ruiz@example.com'),
('Patricia Silva', 12, 'full-time', 'monthly', '2024-05-20', 90.00, '09171234583', 'patricia.silva@example.com'),
('Roberto Chavez', 13, 'full-time', 'monthly', '2024-06-10', 85.00, '09171234584', 'roberto.chavez@example.com'),
('Teresa Vargas', 14, 'full-time', 'monthly', '2024-07-05', 80.00, '09171234585', 'teresa.vargas@example.com'),
('Alberto Herrera', 15, 'full-time', 'monthly', '2024-08-12', 120.00, '09171234586', 'alberto.herrera@example.com');

-- Insert users
INSERT INTO `users` (`employee_id`, `username`, `password`, `usertype`) VALUES
-- Super Admin
(NULL, 'superadmin', '17c4520f6cfd1ab53d8745e84681eb49', '1'),
-- Admin
(NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2'),
-- Cashiers
(3, 'pedro.reyes', '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(4, 'ana.martinez', '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(5, 'luis.garcia', '6ac2470ed8ccf204fd5ff89b32a355cf', '3');

-- Insert employee government IDs
INSERT INTO `employee_government_ids` (`employee_id`, `sss_number`, `pagibig_number`, `philhealth_number`, `tin_number`) VALUES
(1, '34-1234567-9', '1234-5678-9012', '12-345678901-2', '123-456-789-000'),
(2, '34-2345678-0', '1234-5678-9013', '12-345678902-3', '123-456-789-001'),
(3, '34-3456789-1', '1234-5678-9014', '12-345678903-4', '123-456-789-002'),
(4, '34-4567890-2', '1234-5678-9015', '12-345678904-5', '123-456-789-003'),
(5, '34-5678901-3', '1234-5678-9016', '12-345678905-6', '123-456-789-004'),
(6, '34-6789012-4', '1234-5678-9017', '12-345678906-7', '123-456-789-005'),
(7, '34-7890123-5', '1234-5678-9018', '12-345678907-8', '123-456-789-006'),
(8, '34-8901234-6', '1234-5678-9019', '12-345678908-9', '123-456-789-007'),
(9, '34-9012345-7', '1234-5678-9020', '12-345678909-0', '123-456-789-008'),
(10, '34-0123456-8', '1234-5678-9021', '12-345678910-1', '123-456-789-009'),
(11, '34-1234567-0', '1234-5678-9022', '12-345678911-2', '123-456-789-010'),
(12, '34-2345678-1', '1234-5678-9023', '12-345678912-3', '123-456-789-011'),
(13, '34-3456789-2', '1234-5678-9024', '12-345678913-4', '123-456-789-012'),
(14, '34-4567890-3', '1234-5678-9025', '12-345678914-5', '123-456-789-013'),
(15, '34-5678901-4', '1234-5678-9026', '12-345678915-6', '123-456-789-014'),
(16, '34-6789012-5', '1234-5678-9027', '12-345678916-7', '123-456-789-015'),
(17, '34-7890123-6', '1234-5678-9028', '12-345678917-8', '123-456-789-016'),
(18, '34-8901234-7', '1234-5678-9029', '12-345678918-9', '123-456-789-017'),
(19, '34-9012345-8', '1234-5678-9030', '12-345678919-0', '123-456-789-018'),
(20, '34-0123456-9', '1234-5678-9031', '12-345678920-1', '123-456-789-019');

-- Insert employee QR codes
INSERT INTO `employee_qr_codes` (`employee_id`, `qr_code_hash`, `is_active`) VALUES
(1, '9200ca8411029178bbaebb001', 1),
(2, '9200ca8411029178bbaebb002', 1),
(3, '9200ca8411029178bbaebb003', 1),
(4, '9200ca8411029178bbaebb004', 1),
(5, '9200ca8411029178bbaebb005', 1),
(6, '9200ca8411029178bbaebb006', 1),
(7, '9200ca8411029178bbaebb007', 1),
(8, '9200ca8411029178bbaebb008', 1),
(9, '9200ca8411029178bbaebb009', 1),
(10, '9200ca8411029178bbaebb010', 1),
(11, '9200ca8411029178bbaebb011', 1),
(12, '9200ca8411029178bbaebb012', 1),
(13, '9200ca8411029178bbaebb013', 1),
(14, '9200ca8411029178bbaebb014', 1),
(15, '9200ca8411029178bbaebb015', 1),
(16, '9200ca8411029178bbaebb016', 1),
(17, '9200ca8411029178bbaebb017', 1),
(18, '9200ca8411029178bbaebb018', 1),
(19, '9200ca8411029178bbaebb019', 1),
(20, '9200ca8411029178bbaebb020', 1);

-- Insert sample attendance records for the last 7 days

INSERT INTO `attendance_settings` (`id`, `setting_name`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'work_start_time', '08:00:00', 'Regular work start time', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(2, 'work_end_time', '17:00:00', 'Regular work end time', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(3, 'late_threshold_minutes', '15', 'Minutes after work start time to mark as late', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(4, 'half_day_hours', '4', 'Minimum hours to be counted as half-day', '2025-04-19 01:08:43', '2025-04-19 01:08:43');

-- Sample attendance records for weekdays in April 2025 for each employee
INSERT INTO `attendance_records` (`employee_id`, `time_in`, `time_out`, `total_hours`, `status`, `notes`) VALUES
-- Employee 1
(1, '2025-04-01 08:00:00', '2025-04-01 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-02 08:15:00', '2025-04-02 17:00:00', 8.75, 'late', 'Late arrival'),
(1, '2025-04-03 08:00:00', '2025-04-03 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-04 NULL', NULL, 0, 'absent', 'No show'),
(1, '2025-04-05 08:00:00', '2025-04-05 12:00:00', 4, 'half-day', 'Left early - personal reasons'),
(1, '2025-04-06 08:00:00', '2025-04-06 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-07 08:30:00', '2025-04-07 17:00:00', 8.5, 'late', 'Traffic delay'),
(1, '2025-04-08 08:00:00', '2025-04-08 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-09 08:00:00', '2025-04-09 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-10 NULL', NULL, 0, 'absent', 'Sick leave'),
(1, '2025-04-11 08:00:00', '2025-04-11 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-12 08:20:00', '2025-04-12 17:00:00', 8.67, 'late', 'Car trouble'),
(1, '2025-04-13 08:00:00', '2025-04-13 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-14 08:00:00', '2025-04-14 12:30:00', 4.5, 'half-day', 'Doctor appointment'),
(1, '2025-04-15 08:00:00', '2025-04-15 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-16 08:00:00', '2025-04-16 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-17 NULL', NULL, 0, 'absent', 'Family emergency'),
(1, '2025-04-18 08:00:00', '2025-04-18 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-19 08:10:00', '2025-04-19 17:00:00', 8.83, 'late', 'Minor delay'),
(1, '2025-04-20 08:00:00', '2025-04-20 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-21 08:00:00', '2025-04-21 12:00:00', 4, 'half-day', 'Personal matters'),
(1, '2025-04-22 08:00:00', '2025-04-22 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-23 08:25:00', '2025-04-23 17:00:00', 8.58, 'late', 'Heavy traffic'),
(1, '2025-04-24 08:00:00', '2025-04-24 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-25 NULL', NULL, 0, 'absent', 'Vacation leave'),
(1, '2025-04-26 08:00:00', '2025-04-26 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-27 08:00:00', '2025-04-27 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-28 08:00:00', '2025-04-28 12:15:00', 4.25, 'half-day', 'Family event'),
(1, '2025-04-29 08:00:00', '2025-04-29 17:00:00', 9, 'present', 'Normal shift'),
(1, '2025-04-30 08:18:00', '2025-04-30 17:00:00', 8.7, 'late', 'Public transport delay'),

-- Employee 2
(2, '2025-04-01 08:00:00', '2025-04-01 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-02 NULL', NULL, 0, 'absent', 'Sick leave'),
(2, '2025-04-03 08:00:00', '2025-04-03 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-04 08:22:00', '2025-04-04 17:00:00', 8.63, 'late', 'Traffic jam'),
(2, '2025-04-05 08:00:00', '2025-04-05 12:30:00', 4.5, 'half-day', 'Personal appointment'),
(2, '2025-04-06 08:00:00', '2025-04-06 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-07 08:00:00', '2025-04-07 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-08 08:17:00', '2025-04-08 17:00:00', 8.72, 'late', 'Car problems'),
(2, '2025-04-09 08:00:00', '2025-04-09 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-10 08:00:00', '2025-04-10 12:00:00', 4, 'half-day', 'Medical checkup'),
(2, '2025-04-11 NULL', NULL, 0, 'absent', 'Personal leave'),
(2, '2025-04-12 08:00:00', '2025-04-12 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-13 08:25:00', '2025-04-13 17:00:00', 8.58, 'late', 'Overslept'),
(2, '2025-04-14 08:00:00', '2025-04-14 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-15 08:00:00', '2025-04-15 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-16 08:00:00', '2025-04-16 12:15:00', 4.25, 'half-day', 'Family matter'),
(2, '2025-04-17 08:00:00', '2025-04-17 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-18 08:15:00', '2025-04-18 17:00:00', 8.75, 'late', 'Public transport delay'),
(2, '2025-04-19 08:00:00', '2025-04-19 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-20 NULL', NULL, 0, 'absent', 'Emergency leave'),
(2, '2025-04-21 08:00:00', '2025-04-21 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-22 08:00:00', '2025-04-22 12:30:00', 4.5, 'half-day', 'Personal reasons'),
(2, '2025-04-23 08:20:00', '2025-04-23 17:00:00', 8.67, 'late', 'Traffic congestion'),
(2, '2025-04-24 08:00:00', '2025-04-24 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-25 08:00:00', '2025-04-25 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-26 NULL', NULL, 0, 'absent', 'Vacation'),
(2, '2025-04-27 08:00:00', '2025-04-27 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-28 08:18:00', '2025-04-28 17:00:00', 8.7, 'late', 'Road closure'),
(2, '2025-04-29 08:00:00', '2025-04-29 17:00:00', 9, 'present', 'Normal shift'),
(2, '2025-04-30 08:00:00', '2025-04-30 12:00:00', 4, 'half-day', 'Doctor visit'),

-- Employee 3
(3, '2025-04-01 08:15:00', '2025-04-01 17:00:00', 8.75, 'late', 'Bus delay'),
(3, '2025-04-02 08:00:00', '2025-04-02 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-03 08:00:00', '2025-04-03 12:00:00', 4, 'half-day', 'Personal errand'),
(3, '2025-04-04 08:00:00', '2025-04-04 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-05 NULL', NULL, 0, 'absent', 'Sick leave'),
(3, '2025-04-06 08:00:00', '2025-04-06 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-07 08:20:00', '2025-04-07 17:00:00', 8.67, 'late', 'Train delay'),
(3, '2025-04-08 08:00:00', '2025-04-08 12:15:00', 4.25, 'half-day', 'Family emergency'),
(3, '2025-04-09 08:00:00', '2025-04-09 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-10 08:00:00', '2025-04-10 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-11 08:25:00', '2025-04-11 17:00:00', 8.58, 'late', 'Heavy traffic'),
(3, '2025-04-12 NULL', NULL, 0, 'absent', 'Personal leave'),
(3, '2025-04-13 08:00:00', '2025-04-13 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-14 08:00:00', '2025-04-14 12:30:00', 4.5, 'half-day', 'Dental appointment'),
(3, '2025-04-15 08:17:00', '2025-04-15 17:00:00', 8.72, 'late', 'Car trouble'),
(3, '2025-04-16 08:00:00', '2025-04-16 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-17 08:00:00', '2025-04-17 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-18 NULL', NULL, 0, 'absent', 'Emergency leave'),
(3, '2025-04-19 08:00:00', '2025-04-19 12:00:00', 4, 'half-day', 'Personal matters'),
(3, '2025-04-20 08:22:00', '2025-04-20 17:00:00', 8.63, 'late', 'Traffic jam'),
(3, '2025-04-21 08:00:00', '2025-04-21 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-22 08:00:00', '2025-04-22 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-23 NULL', NULL, 0, 'absent', 'Sick leave'),
(3, '2025-04-24 08:15:00', '2025-04-24 17:00:00', 8.75, 'late', 'Public transport issues'),
(3, '2025-04-25 08:00:00', '2025-04-25 12:15:00', 4.25, 'half-day', 'Doctor appointment'),
(3, '2025-04-26 08:00:00', '2025-04-26 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-27 08:18:00', '2025-04-27 17:00:00', 8.7, 'late', 'Road construction'),
(3, '2025-04-28 08:00:00', '2025-04-28 17:00:00', 9, 'present', 'Normal shift'),
(3, '2025-04-29 NULL', NULL, 0, 'absent', 'Vacation'),
(3, '2025-04-30 08:00:00', '2025-04-30 17:00:00', 9, 'present', 'Normal shift'),

-- Employee 4
(4, '2025-04-01 NULL', NULL, 0, 'absent', 'Sick leave'),
(4, '2025-04-02 08:20:00', '2025-04-02 17:00:00', 8.67, 'late', 'Traffic delay'),
(4, '2025-04-03 08:00:00', '2025-04-03 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-04 08:00:00', '2025-04-04 12:30:00', 4.5, 'half-day', 'Personal appointment'),
(4, '2025-04-05 08:00:00', '2025-04-05 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-06 08:25:00', '2025-04-06 17:00:00', 8.58, 'late', 'Car problems'),
(4, '2025-04-07 08:00:00', '2025-04-07 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-08 NULL', NULL, 0, 'absent', 'Family emergency'),
(4, '2025-04-09 08:00:00', '2025-04-09 12:00:00', 4, 'half-day', 'Medical checkup'),
(4, '2025-04-10 08:15:00', '2025-04-10 17:00:00', 8.75, 'late', 'Train delay'),
(4, '2025-04-11 08:00:00', '2025-04-11 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-12 08:00:00', '2025-04-12 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-13 08:22:00', '2025-04-13 17:00:00', 8.63, 'late', 'Traffic congestion'),
(4, '2025-04-14 NULL', NULL, 0, 'absent', 'Personal leave'),
(4, '2025-04-15 08:00:00', '2025-04-15 12:15:00', 4.25, 'half-day', 'Family matter'),
(4, '2025-04-16 08:00:00', '2025-04-16 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-17 08:17:00', '2025-04-17 17:00:00', 8.72, 'late', 'Bus delay'),
(4, '2025-04-18 08:00:00', '2025-04-18 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-19 08:00:00', '2025-04-19 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-20 NULL', NULL, 0, 'absent', 'Sick leave'),
(4, '2025-04-21 08:00:00', '2025-04-21 12:30:00', 4.5, 'half-day', 'Doctor visit'),
(4, '2025-04-22 08:25:00', '2025-04-22 17:00:00', 8.58, 'late', 'Heavy traffic'),
(4, '2025-04-23 08:00:00', '2025-04-23 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-24 08:00:00', '2025-04-24 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-25 08:18:00', '2025-04-25 17:00:00', 8.7, 'late', 'Road closure'),
(4, '2025-04-26 NULL', NULL, 0, 'absent', 'Vacation'),
(4, '2025-04-27 08:00:00', '2025-04-27 12:00:00', 4, 'half-day', 'Personal errand'),
(4, '2025-04-28 08:00:00', '2025-04-28 17:00:00', 9, 'present', 'Normal shift'),
(4, '2025-04-29 08:20:00', '2025-04-29 17:00:00', 8.67, 'late', 'Public transport issues'),
(4, '2025-04-30 08:00:00', '2025-04-30 17:00:00', 9, 'present', 'Normal shift'),

-- Employee 5
(5, '2025-04-01 08:00:00', '2025-04-01 12:15:00', 4.25, 'half-day', 'Doctor appointment'),
(5, '2025-04-02 08:00:00', '2025-04-02 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-03 08:25:00', '2025-04-03 17:00:00', 8.58, 'late', 'Traffic jam'),
(5, '2025-04-04 NULL', NULL, 0, 'absent', 'Sick leave'),
(5, '2025-04-05 08:00:00', '2025-04-05 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-06 08:15:00', '2025-04-06 17:00:00', 8.75, 'late', 'Car trouble'),
(5, '2025-04-07 08:00:00', '2025-04-07 12:30:00', 4.5, 'half-day', 'Personal matters'),
(5, '2025-04-08 08:00:00', '2025-04-08 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-09 08:22:00', '2025-04-09 17:00:00', 8.63, 'late', 'Train delay'),
(5, '2025-04-10 08:00:00', '2025-04-10 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-11 NULL', NULL, 0, 'absent', 'Family emergency'),
(5, '2025-04-12 08:00:00', '2025-04-12 12:00:00', 4, 'half-day', 'Medical checkup'),
(5, '2025-04-13 08:17:00', '2025-04-13 17:00:00', 8.72, 'late', 'Bus delay'),
(5, '2025-04-14 08:00:00', '2025-04-14 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-15 08:00:00', '2025-04-15 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-16 NULL', NULL, 0, 'absent', 'Personal leave'),
(5, '2025-04-17 08:20:00', '2025-04-17 17:00:00', 8.67, 'late', 'Heavy traffic'),
(5, '2025-04-18 08:00:00', '2025-04-18 12:15:00', 4.25, 'half-day', 'Family matter'),
(5, '2025-04-19 08:00:00', '2025-04-19 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-20 08:25:00', '2025-04-20 17:00:00', 8.58, 'late', 'Road construction'),
(5, '2025-04-21 08:00:00', '2025-04-21 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-22 NULL', NULL, 0, 'absent', 'Sick leave'),
(5, '2025-04-23 08:00:00', '2025-04-23 12:30:00', 4.5, 'half-day', 'Dental appointment'),
(5, '2025-04-24 08:18:00', '2025-04-24 17:00:00', 8.7, 'late', 'Traffic congestion'),
(5, '2025-04-25 08:00:00', '2025-04-25 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-26 08:00:00', '2025-04-26 17:00:00', 9, 'present', 'Normal shift'),
(5, '2025-04-27 08:15:00', '2025-04-27 17:00:00', 8.75, 'late', 'Public transport issues'),
(5, '2025-04-28 NULL', NULL, 0, 'absent', 'Vacation'),
(5, '2025-04-29 08:00:00', '2025-04-29 12:00:00', 4, 'half-day', 'Personal errand'),
(5, '2025-04-30 08:00:00', '2025-04-30 17:00:00', 9, 'present', 'Normal shift');


INSERT INTO `pay_settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'standard_hours', '8'),
(2, 'overtime_multiplier', '1.25'),
(3, 'sss_rate', '5'),
(4, 'philhealth_rate', '2.5'),
(5, 'pagibig_rate', '2'),
(6, 'tin_fixed', '200');



INSERT INTO `attendance_settings` (`id`, `setting_name`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'work_start_time', '08:00:00', 'Regular work start time', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(2, 'work_end_time', '17:00:00', 'Regular work end time', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(3, 'late_threshold_minutes', '15', 'Minutes after work start time to mark as late', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(4, 'half_day_hours', '4', 'Minimum hours to be counted as half-day', '2025-04-19 01:08:43', '2025-04-19 01:08:43');


-- Sample data for EA RA Hardware Products and Stock Transactions

-- Insert sample product categories
INSERT INTO `product_categories` (`name`, `description`) VALUES
('Power Tools', 'Electric and battery-powered tools'),
('Hand Tools', 'Manual tools for various applications'),
('Fasteners', 'Nails, screws, bolts, and other fastening materials'),
('Paint & Supplies', 'Paints, brushes, and painting accessories'),
('Plumbing', 'Pipes, fittings, and plumbing tools'),
('Electrical', 'Wires, switches, and electrical components'),
('Hardware', 'General hardware items'),
('Safety Equipment', 'Personal protective equipment'),
('Garden Tools', 'Tools for gardening and landscaping'),
('Building Materials', 'Construction materials and supplies');

-- Insert sample brands
INSERT INTO `brands` (`name`, `description`) VALUES
('Bosch', 'High-quality power tools and accessories'),
('Stanley', 'Professional hand tools and storage solutions'),
('Makita', 'Power tools and accessories'),
('Dewalt', 'Professional power tools'),
('3M', 'Safety and construction products'),
('Dulux', 'Premium paint products'),
('Cemex', 'Building materials'),
('KYK', 'Paint and coatings'),
('Hilti', 'Professional construction tools'),
('Rust-Oleum', 'Protective paints and coatings');

-- Insert sample products
INSERT INTO `products` (`sku`, `barcode`, `name`, `description`, `category_id`, `brand_id`, `unit`, `cost_price`, `selling_price`, `stock_level`, `reorder_point`) VALUES
-- Power Tools
('PT001', '123456789001', 'Bosch 18V Drill', 'Cordless drill with 2 batteries', 1, 1, 'piece', 2500.00, 3500.00, 15, 5),
('PT002', '123456789002', 'Makita Circular Saw', '7-1/4 inch circular saw', 1, 3, 'piece', 3200.00, 4200.00, 10, 3),
('PT003', '123456789003', 'Dewalt Impact Driver', '20V MAX impact driver', 1, 4, 'piece', 2800.00, 3800.00, 12, 4),

-- Hand Tools
('HT001', '123456789004', 'Stanley Hammer', '16oz claw hammer', 2, 2, 'piece', 350.00, 550.00, 25, 8),
('HT002', '123456789005', 'Stanley Screwdriver Set', '6-piece screwdriver set', 2, 2, 'set', 450.00, 650.00, 20, 6),
('HT003', '123456789006', 'Stanley Wrench Set', '10-piece combination wrench set', 2, 2, 'set', 850.00, 1200.00, 15, 5),

-- Fasteners
('FS001', '123456789007', 'Common Nails 2"', 'Box of 100 2-inch common nails', 3, 2, 'box', 120.00, 180.00, 50, 15),
('FS002', '123456789008', 'Wood Screws #8', 'Box of 100 #8 wood screws', 3, 2, 'box', 150.00, 220.00, 40, 12),
('FS003', '123456789009', 'Drywall Screws', 'Box of 100 drywall screws', 3, 2, 'box', 130.00, 190.00, 45, 14),

-- Paint & Supplies
('PS001', '123456789010', 'Dulux Premium Paint', '1 gallon premium interior paint', 4, 6, 'gallon', 850.00, 1200.00, 30, 10),
('PS002', '123456789011', 'KYK Exterior Paint', '1 gallon exterior paint', 4, 8, 'gallon', 750.00, 1100.00, 25, 8),
('PS003', '123456789012', 'Paint Brush Set', '3-piece paint brush set', 4, 6, 'set', 250.00, 350.00, 40, 12),

-- Plumbing
('PL001', '123456789013', 'PVC Pipe 1/2"', '10ft PVC pipe 1/2 inch', 5, 7, 'piece', 120.00, 180.00, 100, 30),
('PL002', '123456789014', 'PVC Fittings Kit', 'Assorted PVC fittings', 5, 7, 'kit', 350.00, 500.00, 25, 8),
('PL003', '123456789015', 'Pipe Wrench', '14-inch pipe wrench', 5, 2, 'piece', 450.00, 650.00, 15, 5),

-- Electrical
('EL001', '123456789016', 'Electrical Wire 14/2', '100ft 14/2 electrical wire', 6, 7, 'roll', 850.00, 1200.00, 20, 6),
('EL002', '123456789017', 'Switch Box', 'Standard electrical switch box', 6, 7, 'piece', 45.00, 65.00, 50, 15),
('EL003', '123456789018', 'Circuit Breaker', '20A circuit breaker', 6, 7, 'piece', 150.00, 220.00, 30, 10),

-- Hardware
('HW001', '123456789019', 'Door Hinge Set', 'Set of 3 door hinges', 7, 2, 'set', 120.00, 180.00, 40, 12),
('HW002', '123456789020', 'Door Knob Set', 'Standard door knob set', 7, 2, 'set', 250.00, 350.00, 30, 10),
('HW003', '123456789021', 'Drawer Slides', 'Pair of drawer slides', 7, 2, 'pair', 180.00, 250.00, 35, 11),

-- Safety Equipment
('SE001', '123456789022', 'Safety Glasses', 'Clear safety glasses', 8, 5, 'pair', 120.00, 180.00, 50, 15),
('SE002', '123456789023', 'Work Gloves', 'Pair of work gloves', 8, 5, 'pair', 150.00, 220.00, 40, 12),
('SE003', '123456789024', 'Hard Hat', 'Standard hard hat', 8, 5, 'piece', 250.00, 350.00, 25, 8),

-- Garden Tools
('GT001', '123456789025', 'Garden Shovel', 'Standard garden shovel', 9, 2, 'piece', 350.00, 500.00, 20, 6),
('GT002', '123456789026', 'Pruning Shears', 'Professional pruning shears', 9, 2, 'piece', 450.00, 650.00, 15, 5),
('GT003', '123456789027', 'Garden Hose', '50ft garden hose', 9, 2, 'piece', 550.00, 750.00, 10, 3),

-- Building Materials
('BM001', '123456789028', 'Cement Bag', '40kg cement bag', 10, 7, 'bag', 250.00, 350.00, 100, 30),
('BM002', '123456789029', 'Sand Bag', '50kg sand bag', 10, 7, 'bag', 150.00, 220.00, 80, 25),
('BM003', '123456789030', 'Gravel Bag', '50kg gravel bag', 10, 7, 'bag', 180.00, 250.00, 70, 20);

-- Insert sample stock transactions
INSERT INTO `stock_transactions` (`product_id`, `transaction_type`, `quantity`, `unit_price`, `total_amount`, `reference_no`, `notes`) VALUES
-- Initial stock entries
(1, 'initial', 15, 2500.00, 37500.00, 'INIT001', 'Initial stock entry'),
(2, 'initial', 10, 3200.00, 32000.00, 'INIT002', 'Initial stock entry'),
(3, 'initial', 12, 2800.00, 33600.00, 'INIT003', 'Initial stock entry'),
(4, 'initial', 25, 350.00, 8750.00, 'INIT004', 'Initial stock entry'),
(5, 'initial', 20, 450.00, 9000.00, 'INIT005', 'Initial stock entry'),
(6, 'initial', 15, 850.00, 12750.00, 'INIT006', 'Initial stock entry'),
(7, 'initial', 50, 120.00, 6000.00, 'INIT007', 'Initial stock entry'),
(8, 'initial', 40, 150.00, 6000.00, 'INIT008', 'Initial stock entry'),
(9, 'initial', 45, 130.00, 5850.00, 'INIT009', 'Initial stock entry'),
(10, 'initial', 30, 850.00, 25500.00, 'INIT010', 'Initial stock entry'),
(11, 'initial', 25, 750.00, 18750.00, 'INIT011', 'Initial stock entry'),
(12, 'initial', 40, 250.00, 10000.00, 'INIT012', 'Initial stock entry'),
(13, 'initial', 100, 120.00, 12000.00, 'INIT013', 'Initial stock entry'),
(14, 'initial', 25, 350.00, 8750.00, 'INIT014', 'Initial stock entry'),
(15, 'initial', 15, 450.00, 6750.00, 'INIT015', 'Initial stock entry'),
(16, 'initial', 20, 850.00, 17000.00, 'INIT016', 'Initial stock entry'),
(17, 'initial', 50, 45.00, 2250.00, 'INIT017', 'Initial stock entry'),
(18, 'initial', 30, 150.00, 4500.00, 'INIT018', 'Initial stock entry'),
(19, 'initial', 40, 120.00, 4800.00, 'INIT019', 'Initial stock entry'),
(20, 'initial', 30, 250.00, 7500.00, 'INIT020', 'Initial stock entry'),
(21, 'initial', 35, 180.00, 6300.00, 'INIT021', 'Initial stock entry'),
(22, 'initial', 50, 120.00, 6000.00, 'INIT022', 'Initial stock entry'),
(23, 'initial', 40, 150.00, 6000.00, 'INIT023', 'Initial stock entry'),
(24, 'initial', 25, 250.00, 6250.00, 'INIT024', 'Initial stock entry'),
(25, 'initial', 20, 350.00, 7000.00, 'INIT025', 'Initial stock entry'),
(26, 'initial', 15, 450.00, 6750.00, 'INIT026', 'Initial stock entry'),
(27, 'initial', 10, 550.00, 5500.00, 'INIT027', 'Initial stock entry'),
(28, 'initial', 100, 250.00, 25000.00, 'INIT028', 'Initial stock entry'),
(29, 'initial', 80, 150.00, 12000.00, 'INIT029', 'Initial stock entry'),
(30, 'initial', 70, 180.00, 12600.00, 'INIT030', 'Initial stock entry'),

-- Sample sales transactions
(1, 'sale', -2, 3500.00, -7000.00, 'SALE001', 'Customer purchase'),
(2, 'sale', -1, 4200.00, -4200.00, 'SALE002', 'Customer purchase'),
(3, 'sale', -3, 3800.00, -11400.00, 'SALE003', 'Customer purchase'),
(4, 'sale', -5, 550.00, -2750.00, 'SALE004', 'Customer purchase'),
(5, 'sale', -3, 650.00, -1950.00, 'SALE005', 'Customer purchase'),
(6, 'sale', -2, 1200.00, -2400.00, 'SALE006', 'Customer purchase'),
(7, 'sale', -10, 180.00, -1800.00, 'SALE007', 'Customer purchase'),
(8, 'sale', -8, 220.00, -1760.00, 'SALE008', 'Customer purchase'),
(9, 'sale', -5, 190.00, -950.00, 'SALE009', 'Customer purchase'),
(10, 'sale', -3, 1200.00, -3600.00, 'SALE010', 'Customer purchase'),

-- Sample restock transactions
(1, 'restock', 5, 2500.00, 12500.00, 'REST001', 'Restock order'),
(2, 'restock', 3, 3200.00, 9600.00, 'REST002', 'Restock order'),
(3, 'restock', 4, 2800.00, 11200.00, 'REST003', 'Restock order'),
(4, 'restock', 8, 350.00, 2800.00, 'REST004', 'Restock order'),
(5, 'restock', 6, 450.00, 2700.00, 'REST005', 'Restock order'),
(6, 'restock', 5, 850.00, 4250.00, 'REST006', 'Restock order'),
(7, 'restock', 15, 120.00, 1800.00, 'REST007', 'Restock order'),
(8, 'restock', 12, 150.00, 1800.00, 'REST008', 'Restock order'),
(9, 'restock', 14, 130.00, 1820.00, 'REST009', 'Restock order'),
(10, 'restock', 10, 850.00, 8500.00, 'REST010', 'Restock order');



INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tools', 'Hand tools and power tools for construction and repairs', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(2, 'Hardware', 'General hardware items including fasteners and fittings', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(3, 'Electrical', 'Electrical supplies, wiring, and components', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(4, 'Plumbing', 'Plumbing supplies, pipes, and fixtures', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(5, 'Paint', 'Paint, primers, and painting supplies', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(6, 'Safety Equipment', 'Protective gear and safety devices', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(7, 'Fasteners', 'Screws, nails, bolts, and other fastening hardware', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 'Garden Tools', 'Equipment for landscaping and gardening', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(9, 'Power Tools', 'Electric and battery-powered tools', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 'Hand Tools', 'Manual tools for various applications', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(11, 'Building Materials', 'Construction materials and supplies', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(12, 'Automotive', 'Tools and supplies for vehicle maintenance', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(13, 'Adhesives', 'Glues, tapes, and bonding agents', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(14, 'Storage Solutions', 'Tool boxes, cabinets, and organizers', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(15, 'Lighting', 'Indoor and outdoor lighting fixtures', '2025-04-21 12:14:27', '2025-04-21 12:14:27');


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



INSERT INTO `expense_transactions` (`category_id`, `amount`, `expense_name`, `transaction_date`) VALUES
-- January 2025
(1, 350.00, 'Printer ink cartridges', '2025-01-01'),
(1, 250.00, 'Office paper supplies', '2025-01-01'),
(1, 200.00, 'Pens and markers', '2025-01-01'),
(2, 980.00, 'Internet bill', '2025-01-01'),
(3, 700.00, 'AC maintenance', '2025-01-01'),
(4, 500.00, 'Employee taxi fare', '2025-01-01'),
(5, 400.00, 'Cleaning supplies', '2025-01-01'),
(6, 600.00, 'Social media ads', '2025-01-01'),
(8, 800.00, 'Legal document fees', '2025-01-01'),
(9, 450.00, 'Cloud storage subscription', '2025-01-01'),

(1, 360.00, 'Printer ink cartridges', '2025-01-02'),
(1, 260.00, 'Office paper supplies', '2025-01-02'),
(1, 210.00, 'Pens and markers', '2025-01-02'),
(2, 985.00, 'Internet bill', '2025-01-02'),
(3, 720.00, 'AC maintenance', '2025-01-02'),
(4, 520.00, 'Employee taxi fare', '2025-01-02'),
(5, 420.00, 'Cleaning supplies', '2025-01-02'),
(6, 620.00, 'Social media ads', '2025-01-02'),
(8, 820.00, 'Legal document fees', '2025-01-02'),
(9, 460.00, 'Cloud storage subscription', '2025-01-02'),

(1, 370.00, 'Printer ink cartridges', '2025-01-03'),
(1, 270.00, 'Office paper supplies', '2025-01-03'),
(1, 220.00, 'Pens and markers', '2025-01-03'),
(2, 990.00, 'Internet bill', '2025-01-03'),
(3, 740.00, 'AC maintenance', '2025-01-03'),
(4, 540.00, 'Employee taxi fare', '2025-01-03'),
(5, 440.00, 'Cleaning supplies', '2025-01-03'),
(6, 640.00, 'Social media ads', '2025-01-03'),
(8, 840.00, 'Legal document fees', '2025-01-03'),
(9, 470.00, 'Cloud storage subscription', '2025-01-03'),

(1, 380.00, 'Printer ink cartridges', '2025-01-06'),
(1, 280.00, 'Office paper supplies', '2025-01-06'),
(1, 230.00, 'Pens and markers', '2025-01-06'),
(2, 995.00, 'Internet bill', '2025-01-06'),
(3, 760.00, 'AC maintenance', '2025-01-06'),
(4, 560.00, 'Employee taxi fare', '2025-01-06'),
(5, 460.00, 'Cleaning supplies', '2025-01-06'),
(6, 660.00, 'Social media ads', '2025-01-06'),
(8, 860.00, 'Legal document fees', '2025-01-06'),
(9, 480.00, 'Cloud storage subscription', '2025-01-06'),

(1, 390.00, 'Printer ink cartridges', '2025-01-07'),
(1, 290.00, 'Office paper supplies', '2025-01-07'),
(1, 240.00, 'Pens and markers', '2025-01-07'),
(2, 998.00, 'Internet bill', '2025-01-07'),
(3, 780.00, 'AC maintenance', '2025-01-07'),
(4, 580.00, 'Employee taxi fare', '2025-01-07'),
(5, 480.00, 'Cleaning supplies', '2025-01-07'),
(6, 680.00, 'Social media ads', '2025-01-07'),
(8, 880.00, 'Legal document fees', '2025-01-07'),
(9, 490.00, 'Cloud storage subscription', '2025-01-07'),

(1, 400.00, 'Printer ink cartridges', '2025-01-08'),
(1, 300.00, 'Office paper supplies', '2025-01-08'),
(1, 250.00, 'Pens and markers', '2025-01-08'),
(2, 999.00, 'Internet bill', '2025-01-08'),
(3, 800.00, 'AC maintenance', '2025-01-08'),
(4, 600.00, 'Employee taxi fare', '2025-01-08'),
(5, 500.00, 'Cleaning supplies', '2025-01-08'),
(6, 700.00, 'Social media ads', '2025-01-08'),
(8, 900.00, 'Legal document fees', '2025-01-08'),
(9, 500.00, 'Cloud storage subscription', '2025-01-08'),

(1, 410.00, 'Printer ink cartridges', '2025-01-09'),
(1, 310.00, 'Office paper supplies', '2025-01-09'),
(1, 260.00, 'Pens and markers', '2025-01-09'),
(2, 999.00, 'Internet bill', '2025-01-09'),
(3, 820.00, 'AC maintenance', '2025-01-09'),
(4, 620.00, 'Employee taxi fare', '2025-01-09'),
(5, 520.00, 'Cleaning supplies', '2025-01-09'),
(6, 720.00, 'Social media ads', '2025-01-09'),
(8, 920.00, 'Legal document fees', '2025-01-09'),
(9, 510.00, 'Cloud storage subscription', '2025-01-09'),

(1, 420.00, 'Printer ink cartridges', '2025-01-10'),
(1, 320.00, 'Office paper supplies', '2025-01-10'),
(1, 270.00, 'Pens and markers', '2025-01-10'),
(2, 999.00, 'Internet bill', '2025-01-10'),
(3, 840.00, 'AC maintenance', '2025-01-10'),
(4, 640.00, 'Employee taxi fare', '2025-01-10'),
(5, 540.00, 'Cleaning supplies', '2025-01-10'),
(6, 740.00, 'Social media ads', '2025-01-10'),
(8, 940.00, 'Legal document fees', '2025-01-10'),
(9, 520.00, 'Cloud storage subscription', '2025-01-10'),

(1, 430.00, 'Printer ink cartridges', '2025-01-13'),
(1, 330.00, 'Office paper supplies', '2025-01-13'),
(1, 280.00, 'Pens and markers', '2025-01-13'),
(2, 999.00, 'Internet bill', '2025-01-13'),
(3, 860.00, 'AC maintenance', '2025-01-13'),
(4, 660.00, 'Employee taxi fare', '2025-01-13'),
(5, 560.00, 'Cleaning supplies', '2025-01-13'),
(6, 760.00, 'Social media ads', '2025-01-13'),
(8, 960.00, 'Legal document fees', '2025-01-13'),
(9, 530.00, 'Cloud storage subscription', '2025-01-13'),

(1, 440.00, 'Printer ink cartridges', '2025-01-14'),
(1, 340.00, 'Office paper supplies', '2025-01-14'),
(1, 290.00, 'Pens and markers', '2025-01-14'),
(2, 999.00, 'Internet bill', '2025-01-14'),
(3, 880.00, 'AC maintenance', '2025-01-14'),
(4, 680.00, 'Employee taxi fare', '2025-01-14'),
(5, 580.00, 'Cleaning supplies', '2025-01-14'),
(6, 780.00, 'Social media ads', '2025-01-14'),
(8, 980.00, 'Legal document fees', '2025-01-14'),
(9, 540.00, 'Cloud storage subscription', '2025-01-14'),

-- February 2025
(1, 500.00, 'Printer ink cartridges', '2025-02-03'),
(1, 400.00, 'Office paper supplies', '2025-02-03'),
(1, 350.00, 'Pens and markers', '2025-02-03'),
(2, 999.00, 'Internet bill', '2025-02-03'),
(3, 999.00, 'AC maintenance', '2025-02-03'),
(4, 800.00, 'Employee taxi fare', '2025-02-03'),
(5, 700.00, 'Cleaning supplies', '2025-02-03'),
(6, 900.00, 'Social media ads', '2025-02-03'),
(8, 999.00, 'Legal document fees', '2025-02-03'),
(9, 600.00, 'Cloud storage subscription', '2025-02-03'),

(1, 510.00, 'Printer ink cartridges', '2025-02-04'),
(1, 410.00, 'Office paper supplies', '2025-02-04'),
(1, 360.00, 'Pens and markers', '2025-02-04'),
(2, 999.00, 'Internet bill', '2025-02-04'),
(3, 999.00, 'AC maintenance', '2025-02-04'),
(4, 820.00, 'Employee taxi fare', '2025-02-04'),
(5, 720.00, 'Cleaning supplies', '2025-02-04'),
(6, 920.00, 'Social media ads', '2025-02-04'),
(8, 999.00, 'Legal document fees', '2025-02-04'),
(9, 610.00, 'Cloud storage subscription', '2025-02-04'),

-- March 2025
(1, 600.00, 'Printer ink cartridges', '2025-03-03'),
(1, 500.00, 'Office paper supplies', '2025-03-03'),
(1, 450.00, 'Pens and markers', '2025-03-03'),
(2, 999.00, 'Internet bill', '2025-03-03'),
(3, 999.00, 'AC maintenance', '2025-03-03'),
(4, 999.00, 'Employee taxi fare', '2025-03-03'),
(5, 900.00, 'Cleaning supplies', '2025-03-03'),
(6, 999.00, 'Social media ads', '2025-03-03'),
(8, 999.00, 'Legal document fees', '2025-03-03'),
(9, 700.00, 'Cloud storage subscription', '2025-03-03'),

(1, 610.00, 'Printer ink cartridges', '2025-03-04'),
(1, 510.00, 'Office paper supplies', '2025-03-04'),
(1, 460.00, 'Pens and markers', '2025-03-04'),
(2, 999.00, 'Internet bill', '2025-03-04'),
(3, 999.00, 'AC maintenance', '2025-03-04'),
(4, 999.00, 'Employee taxi fare', '2025-03-04'),
(5, 920.00, 'Cleaning supplies', '2025-03-04'),
(6, 999.00, 'Social media ads', '2025-03-04'),
(8, 999.00, 'Legal document fees', '2025-03-04'),
(9, 710.00, 'Cloud storage subscription', '2025-03-04'),

(1, 800.00, 'Printer ink cartridges', '2025-03-31'),
(1, 700.00, 'Office paper supplies', '2025-03-31'),
(1, 650.00, 'Pens and markers', '2025-03-31'),
(2, 999.00, 'Internet bill', '2025-03-31'),
(3, 999.00, 'AC maintenance', '2025-03-31'),
(4, 999.00, 'Employee taxi fare', '2025-03-31'),
(5, 999.00, 'Cleaning supplies', '2025-03-31'),
(6, 999.00, 'Social media ads', '2025-03-31'),
(8, 999.00, 'Legal document fees', '2025-03-31'),
(9, 900.00, 'Cloud storage subscription', '2025-03-31');


INSERT INTO `product_sales` (`sale_id`, `transaction_id`, `cashier_name`, `product_id`, `quantity_sold`, `discount_applied`, `sale_price`, `sale_timestamp`) VALUES
-- January 1, 2025 (Wednesday)
(1, '00001', 'Pedro Reyes', 1, 1, 0.00, 3500.00, '2025-01-01 09:15:00'),
(2, '00002', 'Pedro Reyes', 4, 2, 50.00, 1050.00, '2025-01-01 09:45:00'),
(3, '00003', 'Ana Martinez', 7, 3, 0.00, 540.00, '2025-01-01 10:30:00'),
(4, '00004', 'Ana Martinez', 10, 1, 100.00, 1100.00, '2025-01-01 11:15:00'),
(5, '00005', 'Luis Garcia', 13, 5, 0.00, 900.00, '2025-01-01 12:00:00'),
(6, '00006', 'Luis Garcia', 16, 1, 0.00, 1200.00, '2025-01-01 13:30:00'),
(7, '00007', 'Pedro Reyes', 19, 2, 20.00, 340.00, '2025-01-01 14:15:00'),
(8, '00008', 'Ana Martinez', 22, 1, 0.00, 180.00, '2025-01-01 15:00:00'),
(9, '00009', 'Luis Garcia', 25, 1, 0.00, 500.00, '2025-01-01 16:30:00'),
(10, '000010', 'Pedro Reyes', 28, 2, 0.00, 700.00, '2025-01-01 17:45:00'),

-- January 2, 2025 (Thursday)
(11, '00011', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-02 09:00:00'),
(12, '00012', 'Luis Garcia', 5, 2, 0.00, 1300.00, '2025-01-02 09:30:00'),
(13, '00013', 'Pedro Reyes', 8, 4, 40.00, 840.00, '2025-01-02 10:15:00'),
(14, '00014', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-02 11:00:00'),
(15, '00015', 'Luis Garcia', 14, 2, 100.00, 900.00, '2025-01-02 12:30:00'),
(16, '00016', 'Pedro Reyes', 17, 3, 0.00, 195.00, '2025-01-02 13:45:00'),
(17, '00017', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-02 14:30:00'),
(18, '00018', 'Luis Garcia', 23, 2, 20.00, 420.00, '2025-01-02 15:15:00'),
(19, '00019', 'Pedro Reyes', 26, 1, 0.00, 650.00, '2025-01-02 16:00:00'),
(20, '00020', 'Ana Martinez', 29, 3, 0.00, 660.00, '2025-01-02 17:30:00'),

-- January 3, 2025 (Friday)
(21, '00021', 'Luis Garcia', 3, 1, 0.00, 3800.00, '2025-01-03 09:15:00'),
(22, '00022', 'Pedro Reyes', 6, 1, 0.00, 1200.00, '2025-01-03 10:00:00'),
(23, '00023', 'Ana Martinez', 9, 2, 20.00, 360.00, '2025-01-03 10:45:00'),
(24, '00024', 'Luis Garcia', 12, 3, 0.00, 1050.00, '2025-01-03 11:30:00'),
(25, '00025', 'Pedro Reyes', 15, 1, 0.00, 650.00, '2025-01-03 12:15:00'),
(26, '00026', 'Ana Martinez', 18, 2, 0.00, 440.00, '2025-01-03 13:00:00'),
(27, '00027', 'Luis Garcia', 21, 1, 0.00, 250.00, '2025-01-03 14:30:00'),
(28, '00028', 'Pedro Reyes', 24, 1, 0.00, 350.00, '2025-01-03 15:15:00'),
(29, '00029', 'Ana Martinez', 27, 2, 50.00, 1450.00, '2025-01-03 16:45:00'),
(30, '00030', 'Luis Garcia', 30, 4, 0.00, 1000.00, '2025-01-03 17:30:00'),

-- January 6, 2025 (Monday)
(31, '00031', 'Pedro Reyes', 1, 2, 200.00, 6800.00, '2025-01-06 09:00:00'),
(32, '00032', 'Ana Martinez', 4, 1, 0.00, 550.00, '2025-01-06 09:45:00'),
(33, '00033', 'Luis Garcia', 7, 5, 50.00, 850.00, '2025-01-06 10:30:00'),
(34, '00034', 'Pedro Reyes', 10, 2, 100.00, 2300.00, '2025-01-06 11:15:00'),
(35, '00035', 'Ana Martinez', 13, 3, 0.00, 540.00, '2025-01-06 12:00:00'),
(36, '00036', 'Luis Garcia', 16, 1, 0.00, 1200.00, '2025-01-06 13:30:00'),
(37, '00037', 'Pedro Reyes', 19, 2, 0.00, 360.00, '2025-01-06 14:15:00'),
(38, '00038', 'Ana Martinez', 22, 4, 40.00, 680.00, '2025-01-06 15:00:00'),
(39, '00039', 'Luis Garcia', 25, 1, 0.00, 500.00, '2025-01-06 16:30:00'),
(40, '00040', 'Pedro Reyes', 28, 3, 50.00, 1000.00, '2025-01-06 17:45:00'),

-- Continue with more days...
-- January 7, 2025 (Tuesday)
(41, '00041', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-07 09:15:00'),
(42, '00042', 'Luis Garcia', 5, 3, 100.00, 1850.00, '2025-01-07 10:00:00'),
(43, '00043', 'Pedro Reyes', 8, 2, 0.00, 440.00, '2025-01-07 10:45:00'),
(44, '00044', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-07 11:30:00'),
(45, '00045', 'Luis Garcia', 14, 4, 200.00, 1800.00, '2025-01-07 12:15:00'),
(46, '00046', 'Pedro Reyes', 17, 2, 0.00, 130.00, '2025-01-07 13:45:00'),
(47, '00047', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-07 14:30:00'),
(48, '00048', 'Luis Garcia', 23, 3, 30.00, 630.00, '2025-01-07 15:15:00'),
(49, '00049', 'Pedro Reyes', 26, 2, 50.00, 1250.00, '2025-01-07 16:00:00'),
(50, '00050', 'Ana Martinez', 29, 5, 100.00, 1000.00, '2025-01-07 17:30:00'),

-- Continue with more January weekdays...

-- January 8, 2025 (Wednesday)
(51, '00051', 'Luis Garcia', 3, 2, 100.00, 7500.00, '2025-01-08 09:00:00'),
(52, '00052', 'Pedro Reyes', 6, 1, 0.00, 1200.00, '2025-01-08 09:45:00'),
(53, '00053', 'Ana Martinez', 9, 3, 30.00, 540.00, '2025-01-08 10:30:00'),
(54, '00054', 'Luis Garcia', 12, 2, 0.00, 700.00, '2025-01-08 11:15:00'),
(55, '00055', 'Pedro Reyes', 15, 1, 0.00, 650.00, '2025-01-08 12:45:00'),
(56, '00056', 'Ana Martinez', 18, 4, 40.00, 840.00, '2025-01-08 13:30:00'),
(57, '00057', 'Luis Garcia', 21, 2, 20.00, 480.00, '2025-01-08 14:15:00'),
(58, '00058', 'Pedro Reyes', 24, 1, 0.00, 350.00, '2025-01-08 15:45:00'),
(59, '00059', 'Ana Martinez', 27, 3, 75.00, 2175.00, '2025-01-08 16:30:00'),
(60, '00060', 'Luis Garcia', 30, 2, 0.00, 500.00, '2025-01-08 17:15:00'),

-- January 9, 2025 (Thursday)
(61, '00061', 'Pedro Reyes', 1, 1, 0.00, 3500.00, '2025-01-09 09:15:00'),
(62, '00062', 'Ana Martinez', 4, 3, 75.00, 1575.00, '2025-01-09 10:00:00'),
(63, '00063', 'Luis Garcia', 7, 2, 0.00, 360.00, '2025-01-09 10:45:00'),
(64, '00064', 'Pedro Reyes', 10, 1, 0.00, 1200.00, '2025-01-09 11:30:00'),
(65, '00065', 'Ana Martinez', 13, 4, 40.00, 680.00, '2025-01-09 12:15:00'),
(66, '00066', 'Luis Garcia', 16, 2, 100.00, 2300.00, '2025-01-09 13:45:00'),
(67, '00067', 'Pedro Reyes', 19, 1, 0.00, 180.00, '2025-01-09 14:30:00'),
(68, '00068', 'Ana Martinez', 22, 3, 30.00, 510.00, '2025-01-09 15:15:00'),
(69, '00069', 'Luis Garcia', 25, 2, 50.00, 950.00, '2025-01-09 16:45:00'),
(70, '00070', 'Pedro Reyes', 28, 1, 0.00, 350.00, '2025-01-09 17:30:00'),

-- January 10, 2025 (Friday)
(71, '00071', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-10 09:00:00'),
(72, '00072', 'Luis Garcia', 5, 2, 50.00, 1250.00, '2025-01-10 09:45:00'),
(73, '00073', 'Pedro Reyes', 8, 3, 30.00, 630.00, '2025-01-10 10:30:00'),
(74, '00074', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-10 11:15:00'),
(75, '00075', 'Luis Garcia', 14, 2, 100.00, 900.00, '2025-01-10 12:45:00'),
(76, '00076', 'Pedro Reyes', 17, 4, 40.00, 220.00, '2025-01-10 13:30:00'),
(77, '00077', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-10 14:15:00'),
(78, '00078', 'Luis Garcia', 23, 2, 20.00, 420.00, '2025-01-10 15:45:00'),
(79, '00079', 'Pedro Reyes', 26, 3, 75.00, 1875.00, '2025-01-10 16:30:00'),
(80, '00080', 'Ana Martinez', 29, 2, 0.00, 440.00, '2025-01-10 17:15:00'),

-- January 13, 2025 (Monday)
(81, '00081', 'Luis Garcia', 3, 1, 0.00, 3800.00, '2025-01-13 09:15:00'),
(82, '00082', 'Pedro Reyes', 6, 2, 50.00, 2350.00, '2025-01-13 10:00:00'),
(83, '00083', 'Ana Martinez', 9, 3, 30.00, 540.00, '2025-01-13 10:45:00'),
(84, '00084', 'Luis Garcia', 12, 1, 0.00, 350.00, '2025-01-13 11:30:00'),
(85, '00085', 'Pedro Reyes', 15, 2, 100.00, 1200.00, '2025-01-13 12:15:00'),
(86, '00086', 'Ana Martinez', 18, 4, 40.00, 840.00, '2025-01-13 13:45:00'),
(87, '00087', 'Luis Garcia', 21, 1, 0.00, 250.00, '2025-01-13 14:30:00'),
(88, '00088', 'Pedro Reyes', 24, 3, 75.00, 975.00, '2025-01-13 15:15:00'),
(89, '00089', 'Ana Martinez', 27, 2, 50.00, 1450.00, '2025-01-13 16:45:00'),
(90, '00090', 'Luis Garcia', 30, 1, 0.00, 250.00, '2025-01-13 17:30:00'),

-- Continue with more January weekdays...

-- January 14, 2025 (Tuesday)
(91, '00091', 'Pedro Reyes', 1, 1, 0.00, 3500.00, '2025-01-14 09:00:00'),
(92, '00092', 'Ana Martinez', 4, 2, 50.00, 1050.00, '2025-01-14 09:45:00'),
(93, '00093', 'Luis Garcia', 7, 3, 30.00, 510.00, '2025-01-14 10:30:00'),
(94, '00094', 'Pedro Reyes', 10, 1, 0.00, 1200.00, '2025-01-14 11:15:00'),
(95, '00095', 'Ana Martinez', 13, 2, 20.00, 340.00, '2025-01-14 12:45:00'),
(96, '00096', 'Luis Garcia', 16, 1, 0.00, 1200.00, '2025-01-14 13:30:00'),
(97, '00097', 'Pedro Reyes', 19, 4, 80.00, 640.00, '2025-01-14 14:15:00'),
(98, '00098', 'Ana Martinez', 22, 2, 20.00, 340.00, '2025-01-14 15:45:00'),
(99, '00099', 'Luis Garcia', 25, 1, 0.00, 500.00, '2025-01-14 16:30:00'),
(100, '00100', 'Pedro Reyes', 28, 3, 75.00, 975.00, '2025-01-14 17:15:00'),

-- January 15, 2025 (Wednesday)
(101, '00101', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-15 09:15:00'),
(102, '00102', 'Luis Garcia', 5, 2, 50.00, 1250.00, '2025-01-15 10:00:00'),
(103, '00103', 'Pedro Reyes', 8, 3, 30.00, 630.00, '2025-01-15 10:45:00'),
(104, '00104', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-15 11:30:00'),
(105, '00105', 'Luis Garcia', 14, 2, 100.00, 900.00, '2025-01-15 12:15:00'),
(106, '00106', 'Pedro Reyes', 17, 4, 40.00, 220.00, '2025-01-15 13:45:00'),
(107, '00107', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-15 14:30:00'),
(108, '00108', 'Luis Garcia', 23, 2, 20.00, 420.00, '2025-01-15 15:15:00'),
(109, '00109', 'Pedro Reyes', 26, 3, 75.00, 1875.00, '2025-01-15 16:45:00'),
(110, '00110', 'Ana Martinez', 29, 2, 0.00, 440.00, '2025-01-15 17:30:00'),

-- January 16, 2025 (Thursday)
(111, '00111', 'Luis Garcia', 3, 1, 0.00, 3800.00, '2025-01-16 09:00:00'),
(112, '00112', 'Pedro Reyes', 6, 2, 50.00, 2350.00, '2025-01-16 09:45:00'),
(113, '00113', 'Ana Martinez', 9, 3, 30.00, 540.00, '2025-01-16 10:30:00'),
(114, '00114', 'Luis Garcia', 12, 1, 0.00, 350.00, '2025-01-16 11:15:00'),
(115, '00115', 'Pedro Reyes', 15, 2, 100.00, 1200.00, '2025-01-16 12:45:00'),
(116, '00116', 'Ana Martinez', 18, 4, 40.00, 840.00, '2025-01-16 13:30:00'),
(117, '00117', 'Luis Garcia', 21, 1, 0.00, 250.00, '2025-01-16 14:15:00'),
(118, '00118', 'Pedro Reyes', 24, 3, 75.00, 975.00, '2025-01-16 15:45:00'),
(119, '00119', 'Ana Martinez', 27, 2, 50.00, 1450.00, '2025-01-16 16:30:00'),
(120, '00120', 'Luis Garcia', 30, 1, 0.00, 250.00, '2025-01-16 17:15:00'),

-- January 17, 2025 (Friday)
(121, '00121', 'Pedro Reyes', 1, 2, 100.00, 6900.00, '2025-01-17 09:15:00'),
(122, '00122', 'Ana Martinez', 4, 1, 0.00, 550.00, '2025-01-17 10:00:00'),
(123, '00123', 'Luis Garcia', 7, 3, 30.00, 510.00, '2025-01-17 10:45:00'),
(124, '00124', 'Pedro Reyes', 10, 2, 100.00, 2300.00, '2025-01-17 11:30:00'),
(125, '00125', 'Ana Martinez', 13, 1, 0.00, 180.00, '2025-01-17 12:15:00'),
(126, '00126', 'Luis Garcia', 16, 2, 100.00, 2300.00, '2025-01-17 13:45:00'),
(127, '00127', 'Pedro Reyes', 19, 4, 80.00, 640.00, '2025-01-17 14:30:00'),
(128, '00128', 'Ana Martinez', 22, 1, 0.00, 180.00, '2025-01-17 15:15:00'),
(129, '00129', 'Luis Garcia', 25, 2, 50.00, 950.00, '2025-01-17 16:45:00'),
(130, '00130', 'Pedro Reyes', 28, 3, 75.00, 975.00, '2025-01-17 17:30:00');
