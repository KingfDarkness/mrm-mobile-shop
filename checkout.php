<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$user = $conn->query($sql)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $conn->real_escape_string($_POST['address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);

    // Calculate total
    $total_price = 0;
    $cart_items = [];
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $row['qty'] = $qty;
        $total_price += $row['price'] * $qty;
        $cart_items[] = $row;
    }

    // Add delivery charge
    $total_price += 60;

    // Create Order
    $sql = "INSERT INTO orders (user_id, total_price, shipping_address, payment_method) VALUES ($user_id, $total_price, '$address', '$payment_method')";
    if ($conn->query($sql)) {
        $order_id = $conn->insert_id;

        // Create Order Items
        foreach ($cart_items as $item) {
            $p_id = $item['id'];
            $qty = $item['qty'];
            $price = $item['price'];
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $p_id, $qty, $price)");
        }

        // Clear Cart
        unset($_SESSION['cart']);

        $success_msg = "আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে! অর্ডার আইডি: #$order_id";
    } else {
        $error_msg = "অর্ডার করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।";
    }
}

require_once 'header.php';
?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h1 class="text-3xl font-bold mb-8 text-gray-800 text-center">চেকআউট</h1>

        <?php if (isset($success_msg)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">ধন্যবাদ!</strong>
                <span class="block sm:inline"><?php echo $success_msg; ?></span>
            </div>
            <div class="text-center">
                <a href="index.php"
                    class="bg-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    হোম পেজে ফিরে যান
                </a>
            </div>
        <?php else: ?>
            <form action="checkout.php" method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">নাম</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100" readonly>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">মোবাইল</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['mobile']); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100" readonly>
                    <p class="text-xs text-gray-500 mt-1">প্রোফাইল থেকে মোবাইল নম্বর পরিবর্তন করুন।</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">ডেলিভারি ঠিকানা</label>
                    <textarea name="address" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                        required><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2">পেমেন্ট মেথড</label>
                    <div class="flex gap-4">
                        <label
                            class="flex items-center border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 flex-1">
                            <input type="radio" name="payment_method" value="cod" checked class="mr-3 h-5 w-5 text-primary">
                            <div>
                                <div class="font-bold text-gray-800">ক্যাশ অন ডেলিভারি</div>
                                <div class="text-sm text-gray-500">পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center border border-gray-300 rounded-lg p-4 cursor-pointer hover:bg-gray-50 flex-1 opacity-50">
                            <input type="radio" name="payment_method" value="bkash" disabled
                                class="mr-3 h-5 w-5 text-primary">
                            <div>
                                <div class="font-bold text-gray-800">বিকাশ / নগদ</div>
                                <div class="text-sm text-gray-500">শীঘ্রই আসছে...</div>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-4 rounded-lg hover:bg-blue-700 transition shadow-lg text-lg">
                    অর্ডার কনফার্ম করুন
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>