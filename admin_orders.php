<?php
require_once 'config.php';

if (!isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

// Handle Status Update
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
    $success = "অর্ডার স্ট্যাটাস আপডেট করা হয়েছে।";
}

$orders = $conn->query("SELECT o.*, u.name as user_name, u.mobile FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - MRM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold text-center border-b border-gray-700">MRM Admin</div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="admin.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> ড্যাশবোর্ড
                </a>
                <a href="admin_products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-box mr-2"></i> পণ্য ম্যানেজমেন্ট
                </a>
                <a href="admin_orders.php"
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-700 text-white">
                    <i class="fas fa-shopping-cart mr-2"></i> অর্ডার ম্যানেজমেন্ট
                </a>
                <a href="index.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-globe mr-2"></i> সাইট দেখুন
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">অর্ডার ম্যানেজমেন্ট</h2>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <?php if (isset($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">ID</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">গ্রাহক</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">ঠিকানা</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">মোট মূল্য</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">তারিখ</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">স্ট্যাটাস</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $orders->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 border-b last:border-0">
                                    <td class="py-3 px-4 text-sm">#<?php echo $row['id']; ?></td>
                                    <td class="py-3 px-4 text-sm">
                                        <div class="font-bold"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['mobile']); ?>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-sm truncate max-w-xs"
                                        title="<?php echo htmlspecialchars($row['shipping_address']); ?>">
                                        <?php echo htmlspecialchars($row['shipping_address']); ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold"><?php echo formatPrice($row['total_price']); ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-500">
                                        <?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    <?php
                                    if ($row['status'] == 'completed')
                                        echo 'bg-green-100 text-green-800';
                                    elseif ($row['status'] == 'pending')
                                        echo 'bg-yellow-100 text-yellow-800';
                                    elseif ($row['status'] == 'processing')
                                        echo 'bg-blue-100 text-blue-800';
                                    else
                                        echo 'bg-red-100 text-red-800';
                                    ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <form action="" method="POST" class="flex items-center gap-2">
                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="border rounded px-2 py-1 text-xs">
                                                <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="processing" <?php echo $row['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="update_status"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

</body>

</html>