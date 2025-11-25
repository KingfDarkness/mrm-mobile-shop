<?php
require_once 'config.php';

if (!isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM products WHERE id = $id");
    $success = "পণ্যটি সফলভাবে মুছে ফেলা হয়েছে।";
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - MRM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar (Same as admin.php) -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold text-center border-b border-gray-700">MRM Admin</div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="admin.php"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-300 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> ড্যাশবোর্ড
                </a>
                <a href="admin_products.php"
                    class="block py-2.5 px-4 rounded transition duration-200 bg-gray-700 text-white">
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
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">পণ্য তালিকা</h2>
                <a href="admin_product_form.php"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i> নতুন পণ্য যোগ করুন
                </a>
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
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">ছবি</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">নাম</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">ক্যাটাগরি</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b">দাম</th>
                                <th class="py-3 px-4 bg-gray-50 font-bold text-sm text-gray-600 border-b text-right">
                                    অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 border-b">
                                        <img src="<?php echo $row['image']; ?>" alt="img"
                                            class="w-10 h-10 object-cover rounded">
                                    </td>
                                    <td class="py-3 px-4 border-b text-sm"><?php echo $row['name']; ?></td>
                                    <td class="py-3 px-4 border-b text-sm"><?php echo $row['category']; ?></td>
                                    <td class="py-3 px-4 border-b text-sm"><?php echo formatPrice($row['price']); ?></td>
                                    <td class="py-3 px-4 border-b text-right">
                                        <a href="admin_product_form.php?id=<?php echo $row['id']; ?>"
                                            class="text-blue-500 hover:text-blue-700 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="" method="POST" class="inline"
                                            onsubmit="return confirm('আপনি কি নিশ্চিত?');">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
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