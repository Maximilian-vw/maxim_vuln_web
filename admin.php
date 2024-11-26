<?php
session_start(); // Start a session to manage user sessions

// Check if cookie is set to admin:admin
if (isset($_COOKIE['login_cookie']) && $_COOKIE['login_cookie'] === base64_encode('admin:admin')) {
    $_SESSION['access_granted'] = true;
} else {
    $_SESSION['access_granted'] = false;
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css" type="text/css">
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- SIDEBAR -->
    <input type="checkbox" class="menu__checkbox" id="sideview-crawl">
    <div class="side-view">
        <nav class="admin-view__menu">
            <div class="admin-view__header">
                <h3 class="company-name">
                    <span>Apotik1337</span>
                </h3>
                <div class="user-profile">
                    <img src="assets/img/profile.jpg" alt="admin-picture">
                    <h3 class="admin-name">
                        <span>Admin</span>
                    </h3>
                </div>
                <ul class="side-nav">
                    <li class="side-nav__active">
                        <a href="./index.html">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="side-nav__products">
                        <a href="product.php">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="side-nav__users">
                        <a href="./modify.php">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="side-nav__users">
                        <a href="./login.php">
                            <i class="fa fa-close" aria-hidden="true"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <footer class="footer">
                <p>&copy; 1337 Corporation by Wahyu</p>
            </footer>
        </div>

        <!-- MAIN --->
        <main class="main main-content">
            <div class="header">
                <h1>Welcome To Dashboard</h1>
                <p> <div class="current-time"></div></p>
            </div>

            <div class="overview-cards">
                <div class="card product-card">
                    <div class="title">
                        <h2>Products</h2>
                    </div>
                    <span class="content product-content">
                        <svg>
                            <use xlink:href='./icons.svg#icon-package'></use>
                        </svg>
                        <div class="number">
                            <h4>5</h4>
                        </div>
                    </span>
                </div>
                <div class="card user-card">
                    <div class="title">
                        <h2>Users</h2>
                    </div>
                    <span class="content user-content">
                        <svg>
                            <use xlink:href='./icons.svg#icon-user'></use>
                        </svg>
                        <div class="number">
                            <h4>2</h4>
                        </div>
                    </span>
                </div>
                <div class="card order-card">
                    <div class="title">
                        <h2>Orders</h2>
                    </div>
                    <span class="content order-content">
                        <svg>
                            <use xlink:href='./icons.svg#icon-briefcase'></use>
                        </svg>
                        <div class="number">
                            <h4>3</h4>
                        </div>
                    </span>
                </div>
            </div>
        </main>

        <script src="assets/js/app.js"></script>
</body>
</html>
