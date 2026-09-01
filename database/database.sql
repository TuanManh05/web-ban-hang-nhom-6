SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Bảng Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    price DECIMAL(12, 2) NOT NULL CHECK (price >= 0),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_categories
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu: Users
-- Admin: admin@gmail.com / Pass: 11111111 
-- Customer: customer@gmail.com / Pass: 88888888
INSERT INTO users (name, email, password, role) VALUES 
('System Admin', 'admin@gmail.com', '$2y$10$Q78K6mD.Yh8Z3L9vX.1u3e9Y9v9v9v9v9v9v9v9v9v9v9v9v9v9v9', 'admin'),
('Customer', 'customer@gmail.com', '$2y$10$H8.Rk4vP28H6L/e6vT.pL.nS2xZ5x7x9v9v9v9v9v9v9v9v9v9v9v', 'customer');

-- Thêm dữ liệu mẫu: Categories
INSERT INTO categories (id, name, slug) VALUES 
(1, 'Điện thoại', 'dien-thoai'),
(2, 'Laptop', 'laptop'),
(3, 'Phụ kiện', 'phu-kien'),
(4, 'Máy ảnh', 'may-anh');

-- Thêm dữ liệu mẫu: Products
INSERT INTO products (category_id, name, slug, price, description) VALUES 
(1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 30000000, 'Điện thoại Apple cao cấp'),
(1, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 29000000, 'Flagship Samsung'),
(1, 'Xiaomi 14 Ultra', 'xiaomi-14-ultra', 25000000, 'Điện thoại nhiếp ảnh Xiaomi'),
(1, 'Google Pixel 8 Pro', 'google-pixel-8-pro', 20000000, 'Điện thoại Google'),

(2, 'MacBook Pro 14 M3', 'macbook-pro-14-m3', 40000000, 'Laptop Apple chip M3'),
(2, 'Dell XPS 13', 'dell-xps-13', 35000000, 'Laptop mỏng nhẹ Windows'),
(2, 'ThinkPad X1 Carbon', 'thinkpad-x1-carbon', 38000000, 'Laptop doanh nhân Lenovo'),
(2, 'Asus ROG Zephyrus G14', 'asus-rog-zephyrus-g14', 33000000, 'Laptop gaming mỏng nhẹ'),

(3, 'Tai nghe AirPods Pro 2', 'tai-nghe-airpods-pro-2', 5500000, 'Tai nghe chống ồn Apple'),
(3, 'Sạc Anker 65W GaN', 'sac-anker-65w-gan', 1200000, 'Củ sạc nhanh đa năng'),
(3, 'Chuột Logitech MX Master 3S', 'chuot-logitech-mx-master-3s', 2500000, 'Chuột công thái học'),
(3, 'Bàn phím cơ Keychron K2', 'ban-phim-co-keychron-k2', 1800000, 'Bàn phím cơ không dây'),

(4, 'Sony FX3', 'sony-fx3', 90000000, 'Máy ảnh Cinema Sony'),
(4, 'Canon EOS R6 Mark II', 'canon-eos-r6-mark-ii', 60000000, 'Máy ảnh mirrorless Canon'),
(4, 'Fujifilm X-T5', 'fujifilm-x-t5', 42000000, 'Máy ảnh Fujifilm chuẩn màu');