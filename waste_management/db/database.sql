-- ============================================================
-- Smart Waste Collection Management System
-- Database Schema
-- Domain: Smart City
-- ============================================================

CREATE DATABASE IF NOT EXISTS waste_management_db;
USE waste_management_db;

-- -----------------------------------------------
-- Table: users (admin, collector, resident)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'collector', 'resident') NOT NULL DEFAULT 'resident',
    phone VARCHAR(20),
    address TEXT,
    profile_pic VARCHAR(255) DEFAULT 'default.png',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- -----------------------------------------------
-- Table: waste_bins
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS waste_bins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bin_code VARCHAR(20) NOT NULL UNIQUE,
    location_name VARCHAR(150) NOT NULL,
    area VARCHAR(100),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    capacity_liters INT DEFAULT 100,
    current_fill_percent INT DEFAULT 0,
    bin_type ENUM('general', 'recyclable', 'organic', 'hazardous') DEFAULT 'general',
    status ENUM('active', 'full', 'maintenance', 'inactive') DEFAULT 'active',
    last_collected DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- -----------------------------------------------
-- Table: vehicles (garbage trucks)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_number VARCHAR(20) NOT NULL UNIQUE,
    vehicle_type VARCHAR(50) DEFAULT 'Garbage Truck',
    capacity_tons DECIMAL(5,2) DEFAULT 5.00,
    driver_name VARCHAR(100),
    driver_phone VARCHAR(20),
    status ENUM('available', 'on_route', 'maintenance', 'inactive') DEFAULT 'available',
    fuel_type ENUM('diesel', 'electric', 'cng') DEFAULT 'diesel',
    last_service_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- -----------------------------------------------
-- Table: collection_routes
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS collection_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(100) NOT NULL,
    area VARCHAR(100),
    assigned_vehicle_id INT,
    assigned_collector_id INT,
    schedule_day ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
    schedule_time TIME,
    status ENUM('active', 'inactive', 'completed') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_collector_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------------------------
-- Table: route_bins (many-to-many: routes <-> bins)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS route_bins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT NOT NULL,
    bin_id INT NOT NULL,
    collection_order INT DEFAULT 1,
    FOREIGN KEY (route_id) REFERENCES collection_routes(id) ON DELETE CASCADE,
    FOREIGN KEY (bin_id) REFERENCES waste_bins(id) ON DELETE CASCADE
);

-- -----------------------------------------------
-- Table: collection_logs
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS collection_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT,
    bin_id INT NOT NULL,
    vehicle_id INT,
    collector_id INT,
    collected_weight_kg DECIMAL(8,2),
    collection_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    before_fill_percent INT,
    after_fill_percent INT DEFAULT 0,
    notes TEXT,
    status ENUM('completed', 'skipped', 'partial') DEFAULT 'completed',
    FOREIGN KEY (route_id) REFERENCES collection_routes(id) ON DELETE SET NULL,
    FOREIGN KEY (bin_id) REFERENCES waste_bins(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    FOREIGN KEY (collector_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------------------------
-- Table: complaints
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_no VARCHAR(20) NOT NULL UNIQUE,
    resident_id INT,
    resident_name VARCHAR(100),
    resident_email VARCHAR(100),
    resident_phone VARCHAR(20),
    complaint_type ENUM('missed_collection', 'overflowing_bin', 'damaged_bin', 'odor', 'illegal_dumping', 'other') DEFAULT 'other',
    bin_id INT,
    location TEXT,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'resolved', 'closed', 'rejected') DEFAULT 'pending',
    assigned_to INT,
    resolution_notes TEXT,
    resolved_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (resident_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (bin_id) REFERENCES waste_bins(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------------------------
-- Table: notifications
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'danger') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- -----------------------------------------------
-- Table: waste_reports (monthly summaries)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS waste_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_month VARCHAR(7) NOT NULL,
    total_collections INT DEFAULT 0,
    total_weight_kg DECIMAL(10,2) DEFAULT 0,
    recyclable_kg DECIMAL(10,2) DEFAULT 0,
    organic_kg DECIMAL(10,2) DEFAULT 0,
    general_kg DECIMAL(10,2) DEFAULT 0,
    complaints_raised INT DEFAULT 0,
    complaints_resolved INT DEFAULT 0,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default Admin (password: admin123)
INSERT INTO users (full_name, email, password, role, phone, address) VALUES
('Admin User', 'admin@wastesmart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '9876543210', 'City Hall, Smart City'),
('Raj Kumar', 'raj@wastesmart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'collector', '9876543211', 'Zone A, Smart City'),
('Priya Singh', 'priya@wastesmart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'collector', '9876543212', 'Zone B, Smart City'),
('Amit Shah', 'amit@wastesmart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'resident', '9876543213', '12, Rose Street, Smart City'),
('Sunita Patel', 'sunita@wastesmart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'resident', '9876543214', '34, Lotus Avenue, Smart City');

-- Waste Bins
INSERT INTO waste_bins (bin_code, location_name, area, capacity_liters, current_fill_percent, bin_type, status) VALUES
('BIN-001', 'MG Road Junction', 'Zone A', 200, 75, 'general', 'active'),
('BIN-002', 'City Park Entrance', 'Zone A', 150, 90, 'recyclable', 'full'),
('BIN-003', 'Market Square', 'Zone B', 200, 40, 'organic', 'active'),
('BIN-004', 'Railway Station', 'Zone B', 300, 60, 'general', 'active'),
('BIN-005', 'Hospital Road', 'Zone C', 200, 85, 'hazardous', 'active'),
('BIN-006', 'School Zone', 'Zone A', 150, 20, 'recyclable', 'active'),
('BIN-007', 'Bus Stand', 'Zone C', 300, 95, 'general', 'full'),
('BIN-008', 'Residential Block D', 'Zone D', 100, 55, 'organic', 'active');

-- Vehicles
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity_tons, driver_name, driver_phone, status, fuel_type) VALUES
('KA-01-GH-1234', 'Compactor Truck', 8.00, 'Mohan Lal', '9988776655', 'available', 'diesel'),
('KA-01-GH-5678', 'Mini Garbage Truck', 3.00, 'Suresh Nair', '9988776644', 'on_route', 'diesel'),
('KA-01-GH-9012', 'Electric Truck', 5.00, 'Anita Rao', '9988776633', 'available', 'electric'),
('KA-01-GH-3456', 'Tipper Truck', 10.00, 'Ravi Kumar', '9988776622', 'maintenance', 'diesel');

-- Collection Routes
INSERT INTO collection_routes (route_name, area, assigned_vehicle_id, assigned_collector_id, schedule_day, schedule_time, status) VALUES
('Route-A Morning', 'Zone A', 1, 2, 'Monday', '06:00:00', 'active'),
('Route-B Afternoon', 'Zone B', 2, 3, 'Tuesday', '13:00:00', 'active'),
('Route-C Evening', 'Zone C', 3, 2, 'Wednesday', '17:00:00', 'active'),
('Route-D Weekly', 'Zone D', 1, 3, 'Saturday', '08:00:00', 'active');

-- Route-Bins mapping
INSERT INTO route_bins (route_id, bin_id, collection_order) VALUES
(1, 1, 1), (1, 2, 2), (1, 6, 3),
(2, 3, 1), (2, 4, 2),
(3, 5, 1), (3, 7, 2),
(4, 8, 1);

-- Collection Logs
INSERT INTO collection_logs (route_id, bin_id, vehicle_id, collector_id, collected_weight_kg, before_fill_percent, after_fill_percent, status) VALUES
(1, 1, 1, 2, 120.5, 80, 5, 'completed'),
(1, 6, 1, 2, 45.0, 70, 0, 'completed'),
(2, 3, 2, 3, 88.0, 90, 10, 'completed'),
(3, 5, 3, 2, 95.0, 85, 5, 'completed');

-- Complaints
INSERT INTO complaints (complaint_no, resident_id, resident_name, resident_email, resident_phone, complaint_type, location, description, priority, status) VALUES
('CMP-2024-001', 4, 'Amit Shah', 'amit@wastesmart.com', '9876543213', 'overflowing_bin', 'City Park Entrance', 'The bin near City Park has been overflowing for 2 days causing bad smell.', 'high', 'in_progress'),
('CMP-2024-002', 5, 'Sunita Patel', 'sunita@wastesmart.com', '9876543214', 'missed_collection', 'Residential Block D', 'Garbage was not collected yesterday morning.', 'medium', 'pending'),
('CMP-2024-003', 4, 'Amit Shah', 'amit@wastesmart.com', '9876543213', 'damaged_bin', 'Bus Stand', 'Bin at bus stand is broken and needs replacement.', 'low', 'resolved'),
('CMP-2024-004', NULL, 'Anonymous', 'anon@email.com', '9000000001', 'illegal_dumping', 'Near Railway Bridge', 'Someone is dumping construction waste illegally.', 'urgent', 'pending');

-- Notifications
INSERT INTO notifications (user_id, title, message, type) VALUES
(1, 'Bin BIN-002 Full', 'Bin at City Park Entrance is 90% full. Schedule collection.', 'warning'),
(1, 'Bin BIN-007 Critical', 'Bus Stand bin is 95% full - urgent collection required.', 'danger'),
(2, 'New Route Assigned', 'You have been assigned Route-A Morning collection.', 'info'),
(3, 'Complaint Assigned', 'Complaint CMP-2024-001 has been assigned to you.', 'info');
