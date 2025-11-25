<?php
require_once 'config.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$product_id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<div class='container mx-auto px-4 py-12 text-center'>পণ্যটি পাওয়া যায়নি।</div>";
    require_once 'footer.php';
    exit;
}

$product = $result->fetch_assoc();

// Fetch Reviews
$reviews_sql = "SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = $product_id ORDER BY r.created_at DESC";
$reviews_result = $conn->query($reviews_sql);
?>

<div class="container mx-auto px-4 py-12">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-8 text-gray-500">
        <a href="index.php" class="hover:text-primary">হোম</a> /
        <span class="text-gray-700"><?php echo $product['name']; ?></span>
    </nav>

    <div class="flex flex-col md:flex-row gap-12">
        <!-- Product Image -->
        <div class="md:w-1/2">
            <div class="bg-white rounded-lg shadow-lg p-8 flex items-center justify-center border border-gray-100">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                    class="max-h-96 object-contain">
            </div>
        </div>

        <!-- Product Details -->
        <div class="md:w-1/2">
            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo $product['name']; ?></h1>
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400 text-sm mr-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="text-gray-500 text-sm">(<?php echo $reviews_result->num_rows; ?> রিভিউ)</span>
            </div>
            <div class="text-3xl font-bold text-primary mb-6"><?php echo formatPrice($product['price']); ?></div>

            <p class="text-gray-600 mb-8 leading-relaxed">
                <?php echo nl2br($product['description']); ?>
            </p>

            <form action="cart.php" method="POST" class="flex gap-4 mb-8">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="action" value="add">
                <div class="w-24">
                    <input type="number" name="quantity" value="1" min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary text-center">
                </div>
                <button type="submit"
                    class="flex-1 bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition shadow-lg">
                    <i class="fas fa-cart-plus mr-2"></i> কার্টে যোগ করুন
                </button>
            </form>

            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-truck mr-3 w-6 text-center"></i> দ্রুত ডেলিভারি (২-৩ দিন)
                </div>
                <div class="flex items-center text-gray-600 mb-2">
                    <i class="fas fa-shield-alt mr-3 w-6 text-center"></i> ১ বছরের অফিসিয়াল ওয়ারেন্টি
                </div>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-undo mr-3 w-6 text-center"></i> ৭ দিনের রিটার্ন পলিসি
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-8 text-gray-800 border-b pb-4">রিভিউ এবং রেটিং</h2>

        <?php if ($reviews_result->num_rows > 0): ?>
            <div class="grid gap-6">
                <?php while ($review = $reviews_result->fetch_assoc()): ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-bold text-gray-800"><?php echo htmlspecialchars($review['user_name']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo date('d M, Y', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 text-xs mb-3">
                            <?php for ($i = 0; $i < $review['rating']; $i++)
                                echo '<i class="fas fa-star"></i>'; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++)
                                echo '<i class="far fa-star"></i>'; ?>
                        </div>
                        <p class="text-gray-600"><?php echo htmlspecialchars($review['comment']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500">এখনও কোন রিভিউ নেই।</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>