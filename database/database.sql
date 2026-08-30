CREATE DATABASE IF NOT EXISTS web_ban_hang_nhom_6
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE web_ban_hang_nhom_6;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    address VARCHAR(255) NULL,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(15,2) UNSIGNED NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    note VARCHAR(500) NULL,
    total_amount DECIMAL(15,2) UNSIGNED NOT NULL,
    status ENUM('pending', 'confirmed', 'shipping', 'completed', 'cancelled')
        NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL,
    product_name VARCHAR(180) NOT NULL,
    price DECIMAL(15,2) UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id)
        REFERENCES orders(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO categories (name, slug) VALUES
    ('Thời trang', 'thoi-trang'),
    ('Phụ kiện', 'phu-kien');
    -- Dữ liệu mẫu Admin & Customer
INSERT INTO users (name, email, password, role) VALUES 
('Admin System', 'admin@gmail.com', '$2y$10$YourHashHere', 'admin'),
('Test Customer', 'customer@gmail.com', '$2y$10$YourHashHere', 'customer');

-- Dữ liệu mẫu Danh mục
INSERT INTO categories (id, name) VALUES 
(1, 'Áo nam'), (2, 'Quần nam'), (3, 'Phụ kiện');

-- Dữ liệu mẫu 15 Sản phẩm
INSERT INTO products (name, price, category_id) VALUES 
('Sản phẩm 01', 100000, 1),
('Sản phẩm 02', 120000, 1),
('Sản phẩm 03', 150000, 1),
('Sản phẩm 04', 200000, 1),
('Sản phẩm 05', 250000, 1),
('Sản phẩm 06', 110000, 2),
('Sản phẩm 07', 130000, 2),
('Sản phẩm 08', 170000, 2),
('Sản phẩm 09', 210000, 2),
('Sản phẩm 10', 260000, 2),
('Sản phẩm 11', 50000, 3),
('Sản phẩm 12', 70000, 3),
('Sản phẩm 13', 90000, 3),
('Sản phẩm 14', 180000, 3),
('Sản phẩm 15', 300000, 3);

