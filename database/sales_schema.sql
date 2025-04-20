-- Sales Schema

-- Table for tracking product sales
CREATE TABLE IF NOT EXISTS product_sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id VARCHAR(10) NOT NULL,
    cashier_name VARCHAR(10) NOT NULL,
    product_id INT NOT NULL,
    quantity_sold INT NOT NULL,
    discount_applied DECIMAL(5, 2) NOT NULL,
    sale_price DECIMAL(10, 2) NOT NULL,
    sale_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    INDEX idx_transaction_id (transaction_id)
);

-- -- Table for tracking sales performance
-- CREATE TABLE IF NOT EXISTS sales_performance (
--     performance_id INT AUTO_INCREMENT PRIMARY KEY,
--     total_sales DECIMAL(10, 2) NOT NULL,
--     average_transaction_value DECIMAL(10, 2) NOT NULL,
--     sales_trend VARCHAR(255),
--     performance_date DATE DEFAULT CURRENT_DATE
-- );