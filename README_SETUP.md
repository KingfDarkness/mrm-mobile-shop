# MRM Mobile Shop - Setup Instructions

## Problem
You're seeing the error: **"MySQL database server is not running"**

## Solution Options

### Option 1: Automated Setup (Recommended)
Run the setup script which will start MySQL and import the database:

```bash
cd "/home/hacker/Desktop/MRM Mobile shop"
./setup.sh
```

The script will:
1. Check if MySQL is installed
2. Start the MySQL service (requires your password)
3. Create the database and import the schema
4. Show you how to start the application

---

### Option 2: Manual Setup

#### Step 1: Start MySQL Service
Choose the command that works for your system:

```bash
# Try one of these:
sudo service mysql start
# OR
sudo systemctl start mysql
# OR
sudo systemctl start mysqld
```

#### Step 2: Import Database
Once MySQL is running, import the database schema:

```bash
cd "/home/hacker/Desktop/MRM Mobile shop"
mysql -u root < db.sql
```

If you have a MySQL password, use:
```bash
mysql -u root -p < db.sql
```

#### Step 3: Start the Application
```bash
php -S localhost:8000
```

Then open your browser to: http://localhost:8000

---

## Default Credentials

### Admin Panel
- URL: http://localhost:8000/admin_login.php
- Email: `admin@mrm.com`
- Password: `admin123`

---

## Troubleshooting

### "MySQL is not installed"
Install MySQL first:
- **Ubuntu/Debian**: `sudo apt-get install mysql-server`
- **Fedora/RHEL**: `sudo dnf install mysql-server`
- **macOS**: `brew install mysql`

### "Access denied for user 'root'"
Update the database credentials in `config.php`:
```php
$db_user = 'your_username';
$db_pass = 'your_password';
```

### Still having issues?
The application requires:
- PHP 7.4 or higher
- MySQL 5.7 or higher
- PHP extensions: mysqli, pdo_mysql

Check your PHP version: `php -v`
Check installed extensions: `php -m`
