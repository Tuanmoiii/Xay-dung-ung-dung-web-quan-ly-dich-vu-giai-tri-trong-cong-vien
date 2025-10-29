-- ==========================================
--  HỆ THỐNG QUẢN LÝ DỊCH VỤ GIẢI TRÍ CÔNG VIÊN
--  Database: QLDV (MySQL)
--  Phiên bản: Lược bỏ email & phone trong bảng users
-- ==========================================

-- 1️⃣ Tạo database
CREATE DATABASE QLDV CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE QLDV;

-- 2️⃣ Bảng roles (Phân quyền)
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE NOT NULL
);

-- 3️⃣ Bảng users (Nhân viên / người dùng hệ thống)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT,
    full_name VARCHAR(200) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

-- 4️⃣ Bảng parks (Công viên)
CREATE TABLE parks (
    park_id INT AUTO_INCREMENT PRIMARY KEY,
    park_name VARCHAR(200) NOT NULL,
    address TEXT,
    description TEXT
);

-- 5️⃣ Bảng services (Dịch vụ / Khu trò chơi)
CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    park_id INT,
    service_name VARCHAR(200) NOT NULL,
    code VARCHAR(50) UNIQUE,
    description TEXT,
    duration_minutes INT,
    capacity INT DEFAULT 0,
    price DECIMAL(12,2) NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (park_id) REFERENCES parks(park_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- 6️⃣ Bảng schedules (Lịch hoạt động dịch vụ)
CREATE TABLE schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    capacity INT DEFAULT 0,
    status ENUM('scheduled','closed','cancelled') DEFAULT 'scheduled',
    FOREIGN KEY (service_id) REFERENCES services(service_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- 7️⃣ Bảng customers (Khách hàng)
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 8️⃣ Bảng bookings (Đặt vé)
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_ref VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    schedule_id INT NOT NULL,
    num_people INT NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending','paid','cancelled','used') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- 9️⃣ Bảng payments (Thanh toán)
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    method ENUM('cash','card','momo','vnpay') DEFAULT 'cash',
    provider_ref VARCHAR(100),
    status ENUM('pending','success','failed') DEFAULT 'pending',
    paid_at DATETIME,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- 🔟 Bảng reviews (Đánh giá dịch vụ)
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT,
    customer_id INT,
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(service_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

-- 11️⃣ Bảng assets (Thiết bị / Dụng cụ)
CREATE TABLE assets (
    asset_id INT AUTO_INCREMENT PRIMARY KEY,
    park_id INT,
    asset_name VARCHAR(200) NOT NULL,
    quantity INT DEFAULT 0,
    status ENUM('operational','maintenance','out_of_order') DEFAULT 'operational',
    FOREIGN KEY (park_id) REFERENCES parks(park_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- 12️⃣ Bảng maintenance_logs (Nhật ký bảo trì)
CREATE TABLE maintenance_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    asset_id INT,
    reported_by INT,
    description TEXT,
    status ENUM('reported','in_progress','completed') DEFAULT 'reported',
    reported_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(asset_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

-- 13️⃣ Index hỗ trợ truy vấn nhanh
CREATE INDEX idx_service_date ON schedules(service_id, date);
CREATE INDEX idx_booking_ref ON bookings(booking_ref);
CREATE INDEX idx_payment_status ON payments(status);

-- 14️⃣ Dữ liệu mẫu (tuỳ chọn)
INSERT INTO roles (role_name)
VALUES ('Admin'), ('Staff'), ('Cashier'), ('Customer');

INSERT INTO users (role_id, full_name, password_hash)
VALUES (1, 'Nguyễn Văn Quản Trị', 'admin123'),
       (2, 'Trần Thị Nhân Viên', 'staff123');

INSERT INTO parks (park_name, address, description)
VALUES ('Công viên Giải trí Trung tâm', '123 Đường Hoa Sen, Hà Nội', 'Công viên vui chơi hiện đại với nhiều khu giải trí.');

INSERT INTO services (park_id, service_name, code, description, duration_minutes, capacity, price)
VALUES 
(1, 'Tàu lượn siêu tốc', 'ROLLER01', 'Trò chơi cảm giác mạnh', 15, 20, 80000),
(1, 'Nhà ma', 'HAUNTED01', 'Trải nghiệm rùng rợn', 10, 10, 50000),
(1, 'Vòng quay mặt trời', 'SUNWHEEL01', 'Ngắm toàn cảnh công viên', 20, 30, 70000);

INSERT INTO schedules (service_id, date, start_time, end_time, capacity)
VALUES 
(1, CURDATE(), '08:00', '08:30', 20),
(1, CURDATE(), '09:00', '09:30', 20),
(2, CURDATE(), '10:00', '10:20', 10),
(3, CURDATE(), '11:00', '11:30', 30);

-- ✅ Kiểm tra
-- SHOW TABLES;
-- SELECT * FROM users;
