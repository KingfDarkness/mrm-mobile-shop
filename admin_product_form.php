<?php
require_once 'config.php';

if (!isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

$id = $_GET['id'] ?? null;
$product = null;

if ($id) {
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $image = $conn->real_escape_string($_POST['image']);
    $description = $conn->real_escape_string($_POST['description']);
    $is_deal = isset($_POST['is_deal_of_day']) ? 1 : 0;

    if ($id) {
        $sql = "UPDATE products SET name='$name', price=$price, category='$category', image='$image', description='$description', is_deal_of_day=$is_deal WHERE id=$id";
    } else {
        $sql = "INSERT INTO products (name, price, category, image, description, is_deal_of_day) VALUES ('$name', $price, '$category', '$image', '$description', $is_deal)";
    }

    if ($conn->query($sql)) {
        header('Location: admin_products.php');
        exit;
    } else {
        $error = "সমস্যা হয়েছে: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Product - MRM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            <?php echo $id ? 'পণ্য সম্পাদনা করুন' : 'নতুন পণ্য যোগ করুন'; ?></h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">পণ্যের নাম</label>
                <input type="text" name="name" value="<?php echo $product['name'] ?? ''; ?>"
                    class="w-full border p-2 rounded" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">দাম</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $product['price'] ?? ''; ?>"
                        class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">ক্যাটাগরি</label>
                    <select name="category" class="w-full border p-2 rounded">
                        <?php
                        $cats = ['Smartphone', 'Laptop', 'Accessories', 'Wearable'];
                        foreach ($cats as $c) {
                            $selected = ($product['category'] ?? '') == $c ? 'selected' : '';
                            echo "<option value='$c' $selected>$c</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">ছবির URL</label>
                <input type="text" name="image" value="<?php echo $product['image'] ?? ''; ?>"
                    class="w-full border p-2 rounded" placeholder="https://example.com/image.jpg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">বিবরণ</label>
                <textarea name="description" rows="4"
                    class="w-full border p-2 rounded"><?php echo $product['description'] ?? ''; ?></textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_deal_of_day" <?php echo ($product['is_deal_of_day'] ?? 0) ? 'checked' : ''; ?> class="mr-2">
                    <span class="text-gray-700 font-bold">Deal of the Day হিসেবে সেট করুন</span>
                </label>
            </div>

            <div class="flex justify-end gap-4">
                <a href="admin_products.php"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">বাতিল</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">সংরক্ষণ
                    করুন</button>
            </div>
        </form>
    </div>

</body>

</html>