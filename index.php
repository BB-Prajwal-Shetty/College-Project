-- LEASYT Database Schema
-- Create database and tables for the rental platform

CREATE DATABASE IF NOT EXISTS leasyt_db;
USE leasyt_db;

-- Users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10),
    dob DATE,
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    pincode VARCHAR(10),
    profile_pic VARCHAR(255),
    date_joined DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Active', 'Blocked') DEFAULT 'Active',
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_status (status)
);

-- Admin table
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('Super', 'Staff') DEFAULT 'Staff',
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Categories table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
);

-- Items table
CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    rent_price DECIMAL(10,2) NOT NULL,
    security_deposit DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    location VARCHAR(100),
    owner_id INT NOT NULL,
    owner_type ENUM('User', 'Admin') DEFAULT 'Admin',
    availability_status ENUM('Available', 'Rented', 'Maintenance') DEFAULT 'Available',
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_availability (availability_status),
    INDEX idx_owner (owner_id, owner_type),
    INDEX idx_rent_price (rent_price)
);

-- Shopping cart table
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    rent_start_date DATE NOT NULL,
    rent_end_date DATE NOT NULL,
    quantity INT DEFAULT 1,
    rent_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_item (item_id)
);

-- Wishlist table
CREATE TABLE wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, item_id),
    INDEX idx_user (user_id),
    INDEX idx_item (item_id)
);

-- Bookings table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    quantity INT DEFAULT 1,
    total_amount DECIMAL(10,2) NOT NULL,
    security_deposit DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Paid', 'Pending', 'Failed') DEFAULT 'Pending',
    booking_status ENUM('Active', 'Completed', 'Cancelled') DEFAULT 'Active',
    payment_method VARCHAR(50),
    transaction_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_item (item_id),
    INDEX idx_booking_status (booking_status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_dates (start_date, end_date)
);

-- Payments table
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_no VARCHAR(100),
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_status ENUM('Success', 'Failed', 'Refunded') DEFAULT 'Success',
    gateway_response TEXT,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    INDEX idx_booking (booking_id),
    INDEX idx_status (payment_status),
    INDEX idx_transaction (transaction_no)
);

-- Rental history table
CREATE TABLE rental_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    booking_id INT NOT NULL,
    rent_period VARCHAR(50),
    status ENUM('Completed', 'Cancelled') NOT NULL,
    reviewed ENUM('Yes', 'No') DEFAULT 'No',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_item (item_id),
    INDEX idx_booking (booking_id)
);

-- Reviews table
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    booking_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Active', 'Hidden') DEFAULT 'Active',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_item (item_id),
    INDEX idx_rating (rating),
    INDEX idx_status (status)
);

-- Notifications table
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'General',
    is_read TINYINT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read),
    INDEX idx_type (type)
);

-- Admin notifications table
CREATE TABLE admin_notifications (
    admin_notification_id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'System',
    is_read TINYINT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_read (is_read),
    INDEX idx_type (type)
);

-- Reports table
CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(50) NOT NULL,
    title VARCHAR(100) NOT NULL,
    details TEXT,
    generated_by INT,
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES admin(admin_id) ON DELETE SET NULL,
    INDEX idx_type (report_type),
    INDEX idx_date (generated_at)
);

-- Insert default admin user
INSERT INTO admin (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@leasyt.com', 'Super');
-- Default password is 'password'

-- Insert default categories
INSERT INTO categories (category_name, description, icon) VALUES
('Jewelry', 'Beautiful jewelry pieces for special occasions', 'gem'),
('Vehicles', 'Cars, bikes, and other transportation', 'car'),
('Cameras', 'Professional cameras and photography equipment', 'camera'),
('Books', 'Educational and entertainment books', 'book'),
('Electronics', 'Gadgets and electronic devices', 'laptop'),
('Sports Equipment', 'Sports gear and fitness equipment', 'dumbbell'),
('Musical Instruments', 'Guitars, keyboards, and other instruments', 'music'),
('Home Appliances', 'Kitchen and household appliances', 'home'),
('Furniture', 'Tables, chairs, and home furniture', 'couch'),
('Tools', 'Construction and repair tools', 'tools');

-- Insert sample items
INSERT INTO items (item_name, category_id, description, rent_price, security_deposit, location, owner_id, owner_type) VALUES
('Diamond Necklace Set', 1, 'Elegant diamond necklace perfect for weddings', 500.00, 5000.00, 'Mumbai', 1, 'Admin'),
('Canon EOS R5', 3, 'Professional mirrorless camera with 45MP sensor', 200.00, 2000.00, 'Delhi', 1, 'Admin'),
('Honda City', 2, 'Comfortable sedan for city and highway travel', 1500.00, 10000.00, 'Bangalore', 1, 'Admin'),
('MacBook Pro 16"', 5, 'High-performance laptop for professionals', 300.00, 3000.00, 'Pune', 1, 'Admin'),
('Wedding Lehenga', 1, 'Designer bridal lehenga in red and gold', 800.00, 5000.00, 'Chennai', 1, 'Admin');

-- Create indexes for better performance
CREATE INDEX idx_users_email_status ON users(email, status);
CREATE INDEX idx_items_category_availability ON items(category_id, availability_status);
CREATE INDEX idx_bookings_user_status ON bookings(user_id, booking_status);
CREATE INDEX idx_cart_user_date ON cart(user_id, date_added);
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);

-- Create views for common queries
CREATE VIEW active_items AS
SELECT i.*, c.category_name, 
       COALESCE(AVG(r.rating), 0) as avg_rating,
       COUNT(r.review_id) as review_count
FROM items i
JOIN categories c ON i.category_id = c.category_id
LEFT JOIN reviews r ON i.item_id = r.item_id AND r.status = 'Active'
WHERE i.availability_status = 'Available'
GROUP BY i.item_id;

CREATE VIEW user_bookings AS
SELECT b.*, i.item_name, i.image, c.category_name, u.full_name as user_name
FROM bookings b
JOIN items i ON b.item_id = i.item_id
JOIN categories c ON i.category_id = c.category_id
JOIN users u ON b.user_id = u.user_id;

CREATE VIEW booking_summary AS
SELECT 
    DATE(created_at) as booking_date,
    COUNT(*) as total_bookings,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_booking_value
FROM bookings
WHERE payment_status = 'Paid'
GROUP BY DATE(created_at)
ORDER BY booking_date DESC;
