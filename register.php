<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "এই ইমেইল ইতিমধ্যে ব্যবহৃত হয়েছে।";
    } else {
        $sql = "INSERT INTO users (name, email, password, mobile) VALUES ('$name', '$email', '$password', '$mobile')";
        if ($conn->query($sql)) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = 'user';
            header('Location: index.php');
            exit;
        } else {
            $error = "রেজিস্ট্রেশন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।";
        }
    }
}

require_once 'header.php';
?>

<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">রেজিস্ট্রেশন করুন</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">নাম</label>
                <input type="text" name="name"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">ইমেইল</label>
                <input type="email" name="email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">মোবাইল</label>
                <input type="text" name="mobile"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">পাসওয়ার্ড</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition shadow-md">
                রেজিস্ট্রেশন
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="login.php" class="text-primary font-bold hover:underline">লগইন করুন</a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>