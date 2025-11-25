<?php
session_start();

$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = ''; // Default XAMPP password is empty
$db_name = 'mrm_mobile_shop';

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Check if it's a connection refused error (server down)
    $is_refused = strpos($e->getMessage(), 'Connection refused') !== false || strpos($e->getMessage(), 'No such file or directory') !== false;

    die('
        <div style="font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; border-radius: 5px;">
            <h2 style="margin-top: 0;">Database Connection Failed</h2>
            <p>The application could not connect to the database.</p>
            <p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>
            ' . ($is_refused ? '
            <div style="background-color: #fff3cd; padding: 15px; border: 1px solid #ffeeba; color: #856404; border-radius: 5px; margin-top: 15px;">
                <strong>Possible Solution:</strong><br>
                It looks like your MySQL database server is <strong>not running</strong>.
                <ul>
                    <li>If you are using XAMPP/MAMP, open the control panel and start the "MySQL" module.</li>
                    <li>If you are on Linux, try running <code>sudo service mysql start</code> in your terminal.</li>
                </ul>
            </div>
            ' : '') . '
        </div>
    ');
}

// Set charset to handle Bengali characters
$conn->set_charset("utf8mb4");

// Helper function to format price in BDT
function formatPrice($price)
{
    return '৳' . number_format($price, 2);
}

// Helper function to check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Helper function to check if user is admin
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>