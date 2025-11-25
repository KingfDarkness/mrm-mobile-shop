<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MRM Mobile Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0056b3',
                        secondary: '#ffc107',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php" class="text-2xl font-bold text-primary">
                <i class="fas fa-mobile-alt mr-2"></i>MRM Mobile
            </a>

            <!-- Search Bar (Hidden on mobile) -->
            <div class="hidden md:flex flex-1 mx-10">
                <input type="text" placeholder="পণ্য খুঁজুন..."
                    class="w-full border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                <button class="bg-primary text-white px-6 py-2 rounded-r-md hover:bg-blue-700 transition">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Icons & Links -->
            <div class="flex items-center space-x-6">
                <a href="cart.php" class="relative text-gray-700 hover:text-primary transition">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if (isLoggedIn()): ?>
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-primary focus:outline-none">
                            <i class="fas fa-user-circle text-xl mr-1"></i>
                            <span class="hidden md:inline"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </button>
                        <!-- Dropdown -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block">
                            <?php if (isAdmin()): ?>
                                <a href="admin.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">অ্যাডমিন
                                    প্যানেল</a>
                            <?php endif; ?>
                            <a href="profile.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">প্রোফাইল</a>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">লগআউট</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="text-gray-700 hover:text-primary font-medium">লগইন</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Mobile Search (Visible only on mobile) -->
    <div class="md:hidden bg-white p-3 shadow-sm">
        <div class="flex">
            <input type="text" placeholder="পণ্য খুঁজুন..."
                class="w-full border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none">
            <button class="bg-primary text-white px-4 py-2 rounded-r-md">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>