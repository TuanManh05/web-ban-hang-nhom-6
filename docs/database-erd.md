# Sơ đồ cơ sở dữ liệu

```mermaid
erDiagram
    USERS ||--o{ ORDERS : places
    CATEGORIES ||--o{ PRODUCTS : contains
    PRODUCTS ||--o{ PRODUCT_IMAGES : has
    ORDERS ||--|{ ORDER_ITEMS : includes
    PRODUCTS ||--o{ ORDER_ITEMS : appears_in

    USERS {
        bigint id PK
        varchar name
        varchar email UK
        varchar password
        enum role
        boolean status
    }

    CATEGORIES {
        bigint id PK
        varchar name
        varchar slug UK
        boolean status
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        varchar name
        varchar slug UK
        decimal price
        int stock
        boolean status
    }

    PRODUCT_IMAGES {
        bigint id PK
        bigint product_id FK
        varchar image_path
        boolean is_primary
    }

    ORDERS {
        bigint id PK
        bigint user_id FK
        varchar customer_name
        varchar phone
        varchar address
        decimal total_amount
        enum status
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        varchar product_name
        decimal price
        int quantity
    }
```

`order_items` lưu lại tên và giá sản phẩm tại thời điểm mua để lịch sử đơn hàng không bị thay đổi khi quản trị viên cập nhật sản phẩm. Doanh thu được tính từ `orders.total_amount` với trạng thái `completed`.
