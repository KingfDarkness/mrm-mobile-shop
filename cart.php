<?php
require_once 'config.php';

// Handle Cart Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $action = $_POST['action'] ?? '';
    $product_id = intval($_POST['product_id'] ?? 0);

    if ($action === 'add' && $product_id > 0) {
        $quantity = intval($_POST['quantity'] ?? 1);
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        // Redirect back to referring page or cart
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } elseif ($action === 'update' && $product_id > 0) {
        $quantity = intval($_POST['quantity'] ?? 1);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    } elseif ($action === 'remove' && $product_id > 0) {
        unset($_SESSION['cart'][$product_id]);
    }
}

require_once 'header.php';

$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total_price += $row['subtotal'];
        $cart_items[] = $row;
    }
}
?>

<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">শপিং কার্ট</h1>

    <?php if (count($cart_items) > 0): ?>
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left py-4 px-6 text-gray-600 font-medium">পণ্য</th>
                                <th class="text-center py-4 px-6 text-gray-600 font-medium">দাম</th>
                                <th class="text-center py-4 px-6 text-gray-600 font-medium">পরিমাণ</th>
                                <th class="text-right py-4 px-6 text-gray-600 font-medium">মোট</th>
                                <th class="py-4 px-6"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr class="border-b border-gray-100 last:border-0">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"
                                                class="w-16 h-16 object-contain mr-4 rounded border border-gray-100">
                                            <span class="font-medium text-gray-800"><?php echo $item['name']; ?></span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center text-gray-600">
                                        <?php echo formatPrice($item['price']); ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="cart.php" method="POST" class="flex justify-center items-center">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="hidden" name="action" value="update">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"
                                                min="1"
                                                class="w-16 border border-gray-300 rounded px-2 py-1 text-center focus:outline-none focus:ring-1 focus:ring-primary"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-800">
                                        <?php echo formatPrice($item['subtotal']); ?>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form action="cart.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="hidden" name="action" value="remove">
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold mb-6 text-gray-800">অর্ডার সামারি</h2>
                    <div class="flex justify-between mb-4 text-gray-600">
                        <span>সাবটোটাল</span>
                        <span><?php echo formatPrice($total_price); ?></span>
                    </div>
                    <div class="flex justify-between mb-4 text-gray-600">
                        <span>ডেলিভারি চার্জ</span>
                        <span>৳৬০.০০</span>
                    </div>
                    <div class="border-t border-gray-200 my-4"></div>
                    <div class="flex justify-between mb-8 text-xl font-bold text-gray-800">
                        <span>সর্বমোট</span>
                        <span><?php echo formatPrice($total_price + 60); ?></span>
                    </div>
                    <a href="checkout.php"
                        class="block w-full bg-primary text-white text-center py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg">
                        চেকআউট করুন
                    </a>
                    <a href="index.php"
                        class="block w-full text-center py-3 mt-4 text-gray-500 hover:text-gray-700 transition">
                        কেনাকাটা চালিয়ে যান
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="text-gray-300 mb-6">
                <i class="fas fa-shopping-cart text-6xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">আপনার কার্ট খালি!</h2>
            <p class="text-gray-500 mb-8">আপনি এখনও কোন পণ্য যোগ করেননি।</p>
            <a href="index.php" class="bg-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                কেনাকাটা শুরু করুন
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>