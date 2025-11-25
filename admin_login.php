<?php
require_once 'config.php';

if (isAdmin()) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: admin.php');
            exit;
        } else {
            $error = "ভুল পাসওয়ার্ড!";
        }
    } else {
        $error = "অ্যাডমিন অ্যাকাউন্ট পাওয়া যায়নি।";
    }
}
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MRM Mobile Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">অ্যাডমিন লগইন</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">ইমেইল</label>
                <input type="email" name="email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">পাসওয়ার্ড</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-gray-800 text-white font-bold py-3 rounded-lg hover:bg-gray-700 transition shadow-md">
                লগইন
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <a href="index.php" class="text-blue-600 hover:underline">সাইটে ফিরে যান</a>
        </div>
    </div>

</body>

</html>