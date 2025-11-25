<?php
require_once 'config.php';

if (!isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

// Fetch Stats
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='user'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE status='completed'")->fetch_assoc()['total'] ?? 0;

// Fetch Recent Orders
$recent_orders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MRM Mobile Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold text-center border-b border-gray-700">
                MRM Admin
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="admin.php" class="block py-2.5 px-4 rounded transition duration-200 bg-gray-700 text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> ড্যাশবোর্ড
                </a>
                <a href="admin_products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-box mr-2"></i> পণ্য ম্যানেজমেন্ট
                </a>
                <a href="admin_orders.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-shopping-cart mr-2"></i> অর্ডার ম্যানেজমেন্ট
                </a>
                <a href="index.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-globe mr-2"></i> সাইট দেখুন
                </a>
                <a href="logout.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-600 text-gray-300 hover:text-white mt-8">
                    <i class="fas fa-sign-out-alt mr-2"></i> লগআউট
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">ড্যাশবোর্ড</h2>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-600">স্বাগতম, অ্যাডমিন</span>
                    <i class="fas fa-user-circle text-2xl text-gray-600"></i>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                                <i class="fas fa-shopping-cart text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">মোট অর্ডার</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $total_orders; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">মোট আয়</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo formatPrice($total_revenue); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                                <i class="fas fa-box-open text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">মোট পণ্য</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $total_products; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">মোট গ্রাহক</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $total_users; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphs & Recent Orders -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Graph -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">বিক্রয় সারাংশ</h3>
                        <canvas id="salesChart"></canvas>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">সাম্প্রতিক অর্ডার</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">অর্ডার
                                            আইডি</th>
                                        <th class="py-2 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">গ্রাহক
                                        </th>
                                        <th class="py-2 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">মূল্য
                                        </th>
                                        <th class="py-2 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">
                                            স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                        <tr>
                                            <td class="py-3 px-4 border-b text-sm">#<?php echo $order['id']; ?></td>
                                            <td class="py-3 px-4 border-b text-sm">
                                                <?php echo htmlspecialchars($order['user_name']); ?></td>
                                            <td class="py-3 px-4 border-b text-sm">
                                                <?php echo formatPrice($order['total_price']); ?></td>
                                            <td class="py-3 px-4 border-b text-sm">
                                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            <?php
                                            if ($order['status'] == 'completed')
                                                echo 'bg-green-100 text-green-800';
                                            elseif ($order['status'] == 'pending')
                                                echo 'bg-yellow-100 text-yellow-800';
                                            else
                                                echo 'bg-red-100 text-red-800';
                                            ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right">
                            <a href="admin_orders.php" class="text-blue-600 hover:underline text-sm">সব অর্ডার দেখুন
                                &rarr;</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Dummy Data for Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'বিক্রয় (টাকা)',
                    data: [12000, 19000, 3000, 5000, 2000, 30000],
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>

</body>

</html>