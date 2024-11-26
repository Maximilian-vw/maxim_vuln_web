<?php
session_start();

// Check if the user is logged in via cookie
if (!isset($_COOKIE['login_cookie'])) {
    header("Location: login.php");
    exit();
}

// Decode the cookie value to get the username and password
$cookie_value = $_COOKIE['login_cookie'];
$decoded_value = base64_decode($cookie_value);
list($username, $password) = explode(':', $decoded_value);

// Database connection parameters
$servername = "localhost";
$dbname = "login"; // Update as necessary for user verification
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

if ($result->num_rows === 0) {
    header("Location: login.php");
    exit();
}

// Close the database connection for user verification
$stmt->close();
$conn->close();

// Now connect to the apotek database to fetch product data
$servername = "localhost";
$dbname = "apotek"; // Your database name
$username = "root"; // Change to your database username
$password = ""; // Change to your database password

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT * FROM produk_obat";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Obat</title>
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">Apotek</a>
            <ul class="navbar-menu">
                <li><a href="user.php">Home</a></li>
                <li><a href="#">Tentang</a></li>
                <li><a href="#">Kontak</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="produk-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="produk-item">
                        <img src="/assets/img/<?php echo strtolower($row['nama']); ?>.jpg" alt="<?php echo $row['nama']; ?>" style="width: 100%; height: 150px; object-fit: cover;">
                        <h2><?php echo $row['nama']; ?></h2>
                        <p>Harga: Rp <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                        <button class="order-button" data-nama="<?php echo $row['nama']; ?>" data-harga="<?php echo $row['harga']; ?>">Order</button>
                        <div class="order-message" style="color: green; margin-top: 10px;"></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada obat yang tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="assets/js/order.js"></script>
</body>
</html>

<?php
$conn->close();
?>

