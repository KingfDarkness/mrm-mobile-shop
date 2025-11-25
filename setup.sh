#!/bin/bash

echo "========================================="
echo "MRM Mobile Shop - Database Setup Script"
echo "========================================="
echo ""

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL is not installed on your system."
    echo ""
    echo "Please install MySQL first:"
    echo "  Ubuntu/Debian: sudo apt-get install mysql-server"
    echo "  Fedora/RHEL:   sudo dnf install mysql-server"
    echo "  macOS:         brew install mysql"
    exit 1
fi

echo "✓ MySQL is installed"
echo ""

# Try to start MySQL service
echo "Attempting to start MySQL service..."
echo "(You may be prompted for your password)"
echo ""

if sudo service mysql start 2>/dev/null; then
    echo "✓ MySQL service started successfully"
elif sudo systemctl start mysql 2>/dev/null; then
    echo "✓ MySQL service started successfully"
elif sudo systemctl start mysqld 2>/dev/null; then
    echo "✓ MySQL service started successfully"
else
    echo "⚠ Could not start MySQL automatically."
    echo "Please start MySQL manually using your system's service manager."
    echo ""
    read -p "Press Enter once MySQL is running..."
fi

echo ""
echo "Checking MySQL connection..."

# Test connection
if mysql -u root -e "SELECT 1;" &> /dev/null; then
    echo "✓ MySQL connection successful (no password)"
    MYSQL_CMD="mysql -u root"
elif mysql -u root -p -e "SELECT 1;" 2>/dev/null; then
    echo "✓ MySQL connection successful (with password)"
    MYSQL_CMD="mysql -u root -p"
else
    echo "❌ Cannot connect to MySQL."
    echo "Please check your MySQL installation and credentials."
    exit 1
fi

echo ""
echo "Creating database and importing schema..."

# Import the database
if [ -f "db.sql" ]; then
    $MYSQL_CMD < db.sql
    if [ $? -eq 0 ]; then
        echo "✓ Database created and schema imported successfully"
    else
        echo "❌ Failed to import database schema"
        exit 1
    fi
else
    echo "❌ db.sql file not found"
    exit 1
fi

echo ""
echo "========================================="
echo "✓ Setup Complete!"
echo "========================================="
echo ""
echo "You can now access the application:"
echo "  - Frontend: http://localhost:8000"
echo "  - Admin Panel: http://localhost:8000/admin_login.php"
echo ""
echo "Default admin credentials:"
echo "  Email: admin@mrm.com"
echo "  Password: admin123"
echo ""
echo "To start the PHP development server, run:"
echo "  php -S localhost:8000"
echo ""
