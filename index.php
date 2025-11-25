<?php
require_once 'config.php';
require_once 'header.php';

// Fetch Deal of the Day
$deal_sql = "SELECT * FROM products WHERE is_deal_of_day = 1 LIMIT 1";
$deal_result = $conn->query($deal_sql);
$deal_product = $deal_result->fetch_assoc();

// Fetch Popular Products (Just fetching all for now, limited to 8)
$products_sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 8";
$products_result = $conn->query($products_sql);
?>

<!-- Hero Section -->
<div class="bg-blue-600 text-white py-12">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-8 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">সেরা স্মার্টফোন কিনুন <br>সবচেয়ে কম দামে!</h1>
            <p class="text-lg mb-6 text-blue-100">অফিসিয়াল ওয়ারেন্টি এবং দ্রুত ডেলিভারি।</p>
            <a href="#products"
                class="bg-yellow-500 text-blue-900 font-bold py-3 px-8 rounded-full hover:bg-yellow-400 transition shadow-lg">
                এখনই কিনুন
            </a>
        </div>
        <div class="md:w-1/2 flex justify-center">
            <img src="https://placehold.co/500x300?text=Mobile+Banner" alt="Banner" class="rounded-lg shadow-2xl">
        </div>
    </div>
</div>

<!-- Deal of the Day -->
<?php if ($deal_product): ?>
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">আজকের সেরা ডিল <i
                class="fas fa-fire text-red-500"></i></h2>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-100 flex items-center justify-center p-8">
                <img src="<?php echo $deal_product['image']; ?>" alt="<?php echo $deal_product['name']; ?>"
                    class="max-h-80 object-contain hover:scale-105 transition duration-300">
            </div>
            <div class="md:w-1/2 p-8 flex flex-col justify-center">
                <span class="text-red-500 font-bold tracking-wider uppercase text-sm mb-2">Limited Time Offer</span>
                <h3 class="text-3xl font-bold mb-4 text-gray-800"><?php echo $deal_product['name']; ?></h3>
                <p class="text-gray-600 mb-6"><?php echo $deal_product['description']; ?></p>
                <div class="flex items-center mb-6">
                    <span
                        class="text-4xl font-bold text-primary mr-4"><?php echo formatPrice($deal_product['price']); ?></span>
                    <span
                        class="text-xl text-gray-400 line-through"><?php echo formatPrice($deal_product['price'] * 1.1); ?></span>
                </div>
                <div class="flex space-x-4">
                    <a href="product.php?id=<?php echo $deal_product['id']; ?>"
                        class="flex-1 bg-primary text-white text-center py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                        বিস্তারিত দেখুন
                    </a>
                    <form action="cart.php" method="POST" class="flex-1">
                        <input type="hidden" name="product_id" value="<?php echo $deal_product['id']; ?>">
                        <input type="hidden" name="action" value="add">
                        <button type="submit"
                            class="w-full bg-gray-800 text-white py-3 rounded-lg font-bold hover:bg-gray-700 transition">
                            <i class="fas fa-cart-plus mr-2"></i> কার্টে যোগ করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Product Categories -->
<section class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-8 text-gray-800">জনপ্রিয় ক্যাটাগরি</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $categories = ['Smartphone', 'Laptop', 'Accessories', 'Wearable'];
            foreach ($categories as $cat):
                ?>
                <a href="#" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition text-center group">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                        <i class="fas fa-mobile-alt text-2xl text-primary"></i>
                    </div>
                    <h3 class="font-bold text-gray-700"><?php echo $cat; ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Popular Products -->
<section id="products" class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-8 text-gray-800">জনপ্রিয় পণ্যসমূহ</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <?php while ($row = $products_result->fetch_assoc()): ?>
            <div
                class="bg-white rounded-lg shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col">
                <div class="relative overflow-hidden group h-64 p-4 flex items-center justify-center bg-gray-50">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"
                        class="max-h-full max-w-full object-contain group-hover:scale-110 transition duration-300">
                    <?php if ($row['is_deal_of_day']): ?>
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">HOT</span>
                    <?php endif; ?>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <div class="text-sm text-gray-500 mb-1"><?php echo $row['category']; ?></div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2 hover:text-primary transition">
                        <a href="product.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                    </h3>
                    <div class="mt-auto">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold text-primary"><?php echo formatPrice($row['price']); ?></span>
                        </div>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit"
                                class="w-full bg-gray-100 text-gray-800 font-bold py-2 rounded hover:bg-primary hover:text-white transition">
                                কার্টে যোগ করুন
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php require_once 'footer.php'; ?>