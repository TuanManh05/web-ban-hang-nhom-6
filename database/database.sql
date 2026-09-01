DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin', 'customer')) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    price REAL NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Admin: admin@gmail.com / 11111111 | Customer: customer@gmail.com / 88888888
INSERT INTO users (name, email, password, role) VALUES 
('System Admin', 'admin@gmail.com', '$2y$10$wK1k6994Gk66b3N8X5D4v.y1pXbVfK7QG.Jg9v0k5N8Q5D4v.y1pK', 'admin'),
('Customer', 'customer@gmail.com', '$2y$10$C8.Rk4vP28H6L/e6vT.pL.nS2xZ5x7x9v9v9v9v9v9v9v9v9v9v9v', 'customer');

-- Categories (Slug chuẩn, không trùng ID)
INSERT INTO categories (id, name, slug) VALUES 
(1, 'Điện thoại', 'dien-thoai'),
(2, 'Laptop', 'laptop'),
(3, 'Phụ kiện', 'phu-kien'),
(4, 'Máy ảnh', 'may-anh');

-- 15 Products liên kết đúng category_id và có slug
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