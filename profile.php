<?php
require_once 'config.php';
require_once 'header.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Profile Info -->
        <div class="md:w-1/3">
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <div class="flex flex-col items-center mb-6">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-500">
                        <i class="fas fa-user text-4xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <div class="mb-3">
                        <label class="block text-gray-500 text-xs uppercase font-bold mb-1">মোবাইল</label>
                        <div class="text-gray-800"><?php echo htmlspecialchars($user['mobile']); ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-500 text-xs uppercase font-bold mb-1">ঠিকানা</label>
                        <div class="text-gray-800">
                            <?php echo nl2br(htmlspecialchars($user['address'] ?? 'ঠিকানা যোগ করা হয়নি')); ?></div>
                    </div>
                </div>

                <div class="mt-6">
                    <button class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-700 transition text-sm">
                        প্রোফাইল এডিট করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="md:w-2/3">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">অর্ডার হিস্ট্রি</h2>

            <?php if ($orders->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="font-bold text-lg text-gray-800">অর্ডার #<?php echo $order['id']; ?></div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo date('d M, Y h:i A', strtotime($order['created_at'])); ?></div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                <?php
                                if ($order['status'] == 'completed')
                                    echo 'bg-green-100 text-green-800';
                                elseif ($order['status'] == 'pending')
                                    echo 'bg-yellow-100 text-yellow-800';
                                elseif ($order['status'] == 'processing')
                                    echo 'bg-blue-100 text-blue-800';
                                else
                                    echo 'bg-red-100 text-red-800';
                                ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>

                            <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                                <div class="text-gray-600">
                                    পেমেন্ট মেথড: <span
                                        class="font-medium"><?php echo strtoupper($order['payment_method']); ?></span>
                                </div>
                                <div class="text-xl font-bold text-primary">
                                    <?php echo formatPrice($order['total_price']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <div class="text-gray-300 mb-4">
                        <i class="fas fa-box-open text-5xl"></i>
                    </div>
                    <p class="text-gray-500">আপনি এখনও কোন অর্ডার করেননি।</p>
                    <a href="index.php" class="inline-block mt-4 text-primary font-bold hover:underline">কেনাকাটা শুরু
                        করুন</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>