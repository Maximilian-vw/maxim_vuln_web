<?php
session_start();

if (!isset($_COOKIE['login_cookie'])) {
    header("Location: login.php");
    exit();
}

$cookie_value = $_COOKIE['login_cookie'];
$decoded_value = base64_decode($cookie_value);
list($username, $password) = explode(':', $decoded_value);

// Define database connection parameters
$servername = "localhost";
$dbname = "login";
$db_username = "wahyu";
$db_password = "wahyu123";

// Verify the username and password against the database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User is logged in and has a valid session
    // Rest of the code...
} else {
    header("Location: login.php");
    exit();
}

$conn->close();
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
    <title>Editor Dashboard</title>
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
                        <span>Editor</span>
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
                        <a href="./userproduct.php">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>Products</span>
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
