<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            $redirect = $_GET['redirect'] ?? 'index.php';
            header("Location: $redirect");
            exit;
        } else {
            $error = "ভুল পাসওয়ার্ড!";
        }
    } else {
        $error = "এই ইমেইল দিয়ে কোন অ্যাকাউন্ট নেই।";
    }
}

require_once 'header.php';
?>

<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">লগইন করুন</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">ইমেইল</label>
                <input type="email" name="email"
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
                লগইন
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            অ্যাকাউন্ট নেই? <a href="register.php" class="text-primary font-bold hover:underline">রেজিস্ট্রেশন করুন</a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>