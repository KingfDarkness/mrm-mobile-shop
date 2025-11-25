# MRM Mobile Shop - Setup & Walkthrough

This guide explains how to set up and run the MRM Mobile Shop e-commerce website.

## Prerequisites
- A web server with PHP support (e.g., XAMPP, WAMP, or a live Linux server).
- MySQL Database.

## Installation Steps

1.  **Database Setup**:
    - Open your MySQL administration tool (e.g., phpMyAdmin).
    - Create a new database named `mrm_mobile_shop` (or any name you prefer).
    - Import the `db.sql` file provided in the project folder. This will create all necessary tables and insert demo data.

2.  **Configuration**:
    - Open `config.php`.
    - Update the database credentials if necessary:
        ```php
        $db_host = 'localhost';
        $db_user = 'root'; // Your DB Username
        $db_pass = '';     // Your DB Password
        $db_name = 'mrm_mobile_shop';
        ```

3.  **Run the Website**:
    - Place the project folder in your server's root directory (e.g., `htdocs` for XAMPP).
    - Open your browser and visit `http://localhost/MRM Mobile shop/index.php`.

## Features Walkthrough

### 1. Homepage (`index.php`)
- **Deal of the Day**: Shows a featured product with a special price.
- **Categories**: Quick links to product categories.
- **Popular Products**: A grid of available products.

### 2. User Account
- **Registration**: Users can sign up via `register.php`.
- **Login**: Users can log in via `login.php`.
- **Profile**: View personal details and order history at `profile.php`.

### 3. Shopping Experience
- **Product Details**: Click on any product to view details and reviews (`product.php`).
- **Cart**: Add items to the cart and manage quantities (`cart.php`).
- **Checkout**: Place an order with cash on delivery (`checkout.php`).

### 4. Admin Panel
- **Login**: Access via `admin_login.php`.
    - **Email**: `admin@mrm.com`
    - **Password**: `admin123`
- **Dashboard**: View sales stats, recent orders, and graphs (`admin.php`).
- **Product Management**: Add, edit, or delete products (`admin_products.php`).
- **Order Management**: View orders and update their status (Pending -> Completed) (`admin_orders.php`).

## Files Overview
- `index.php`: Main homepage.
- `style.css`: Custom styles (Tailwind CSS is loaded via CDN).
- `config.php`: Database connection.
- `header.php` / `footer.php`: Shared layout components.
- `admin.php`: Admin dashboard.

## Notes
- The site uses Tailwind CSS via CDN, so an internet connection is required for styling to load properly.
- Images are currently using placeholders. You can update image URLs in the Admin Panel.
