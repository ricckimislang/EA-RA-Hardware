-- Create database if not exists
CREATE DATABASE IF NOT EXISTS ea_ra_hardware;
USE ea_ra_hardware;

-- Categories table
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Brands table
CREATE TABLE brands (
    brand_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Supplier Contacts table
CREATE TABLE supplier_contacts (
    contact_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    sku VARCHAR(50) UNIQUE NOT NULL,
    barcode VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    category_id INT,
    brand_id INT,
    unit VARCHAR(20) NOT NULL,
    cost_price DECIMAL(10,2) NOT NULL,
    selling_price DECIMAL(10,2) NOT NULL,
    stock_level INT DEFAULT 0,
    reorder_point INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id)
);

-- Stock transactions table
CREATE TABLE stock_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    transaction_type TEXT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    reference_no VARCHAR(50),
    notes TEXT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Tools', 'Hand tools and power tools for construction and repairs'),
('Hardware', 'General hardware items including fasteners and fittings'),
('Electrical', 'Electrical supplies, wiring, and components'),
('Plumbing', 'Plumbing supplies, pipes, and fixtures'),
('Paint', 'Paint, primers, and painting supplies');

-- Insert sample brands
INSERT INTO brands (name, description) VALUES
('Stanley', 'Quality hand tools and storage solutions'),
('DeWalt', 'Professional-grade power tools and equipment'),
('Makita', 'Innovative power tools and outdoor equipment'),
('Bosch', 'High-performance tools and accessories'),
('3M', 'Industrial and consumer products');

-- Insert sample supplier contacts
INSERT INTO supplier_contacts (name, contact_person, phone, email, address) VALUES
('Hardware Wholesale Co.', 'John Smith', '555-0101', 'john@hwwholesale.com', '123 Supply St, Industry City'),
('Tools Direct', 'Mary Johnson', '555-0102', 'mary@toolsdirect.com', '456 Warehouse Ave, Commerce Town'),
('Building Supply Inc.', 'Robert Brown', '555-0103', 'robert@bsupply.com', '789 Industrial Rd, Trade City');

-- Insert sample products
INSERT INTO products (sku, barcode, name, description, category_id, brand_id, unit, cost_price, selling_price, stock_level, reorder_point) VALUES
('HMR-001', '1234567890', 'Claw Hammer', '16oz steel claw hammer with rubber grip', 1, 1, 'piece', 12.50, 24.99, 50, 15),
('DRL-001', '2345678901', 'Cordless Drill', '20V max lithium-ion cordless drill', 1, 2, 'piece', 89.99, 149.99, 25, 8),
('SCW-001', '3456789012', 'Screwdriver Set', '6-piece precision screwdriver set', 1, 1, 'set', 15.99, 29.99, 35, 10),
('PLR-001', '4567890123', 'Pliers Set', '3-piece pliers set with wire cutter', 1, 4, 'set', 24.99, 44.99, 30, 12),
('NLS-001', '5678901234', 'Nails Assorted', 'Box of 500 assorted nails', 2, 1, 'box', 8.99, 16.99, 100, 25),
('WRE-001', '6789012345', 'Wire 14AWG', '100ft 14AWG electrical wire', 3, 3, 'roll', 35.99, 59.99, 15, 5),
('PVC-001', '7890123456', 'PVC Pipe 2"', '10ft PVC pipe 2-inch diameter', 4, 4, 'piece', 9.99, 18.99, 40, 15),
('PNT-001', '8901234567', 'White Paint', '1-gallon interior white paint', 5, 5, 'gallon', 19.99, 34.99, 20, 8);

-- Insert sample stock transactions
INSERT INTO stock_transactions (product_id, transaction_type, quantity, unit_price, total_amount, reference_no, notes) VALUES
(1, 'purchase', 25, 12.50, 312.50, 'PO-001', 'Initial stock purchase'),
(2, 'purchase', 15, 89.99, 1349.85, 'PO-002', 'Restocking power tools'),
(3, 'purchase', 20, 15.99, 319.80, 'PO-003', 'Regular inventory update'),
(1, 'sale', -2, 24.99, 49.98, 'SO-001', 'Customer purchase'),
(4, 'purchase', 18, 24.99, 449.82, 'PO-004', 'New product line addition'),
(2, 'sale', -3, 149.99, 449.97, 'SO-002', 'Bulk sale to contractor');