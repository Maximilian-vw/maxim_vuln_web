<?php
session_start();

// Function to sanitize user input
function sanitizeInput($input) {
    $input = trim($input);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    return $input;
}

// Function to validate price
function validatePrice($price) {
    return is_numeric($price) && $price > 0;
}

// Check if the user is logged in
if (isset($_COOKIE['login_cookie']) && $_COOKIE['login_cookie'] === base64_encode('admin:admin')) {
} else {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "apotek"; // Nama database Anda

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT * FROM produk_obat";
$result = $conn->query($sql);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = sanitizeInput($_POST['nama']);
    $harga = sanitizeInput($_POST['harga']);
    $gambar = $_FILES['gambar'];

    // Validate input
    if (empty($nama)) {
        $_SESSION['error'] = "Nama obat harus diisi.";
    } elseif (!validatePrice($harga)) {
        $_SESSION['error'] = "Harga harus berupa angka positif.";
    } else {
        // Check if file is uploaded without error
        if ($gambar['error'] == 0) {
            // Check file size
            $maxFileSize = 10 * 1024 * 1024; // 10 MB
            if ($gambar['size'] > $maxFileSize) {
                $_SESSION['error'] = "Maaf, ukuran file tidak boleh lebih dari 10 MB.";
            } else {
                // Define the directory for storing files
                $targetDir = "assets/img/";

                // Move the file to the target directory with the same name
                if (move_uploaded_file($gambar['tmp_name'], $targetDir . $gambar['name'])) {
                    // Prepare and execute SQL statement to save data to the database
                    $stmt = $conn->prepare("INSERT INTO produk_obat (nama, harga) VALUES (?, ?)");
                    $stmt->bind_param("si", $nama, $harga);

                    if ($stmt->execute()) {
                        // If successful, clear the error message
                        $_SESSION['error'] = "";
                        echo '<script>window.location.href="product.php";</script>';
                        exit;
                    } else {
                        $_SESSION['error'] = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $_SESSION['error'] = "Maaf, terjadi kesalahan saat mengupload file.";
                }
            }
        } else {
            $_SESSION['error'] = "Maaf, terjadi kesalahan saat mengupload file.";
        }
    }
}

// Display error message if there is one
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']); // Clear error message after displaying
}
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
                <li><a href="admin.php">Home</a></li>
                <li><a href="#">Tentang</a></li>
                <li><a href="#">Kontak</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="produk-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class ="produk-item">
                        <img src="/assets/img/<?php echo strtolower($row['nama']); ?>.jpg" alt="<?php echo $row['nama']; ?>" style="width: 100%; height: 150px; object-fit: cover;">
                        <h2><?php echo $row['nama']; ?></h2>
                        <p>Harga: Rp <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada obat yang tersedia.</p>
            <?php endif; ?>
        </div>

        <div class="form-container">
            <h2>Tambah Obat Baru</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <input type="text" name="nama" placeholder="Nama Obat" required>
                <input type="number" name="harga" placeholder="Harga" required>
                <input type="file" name="gambar" required>
                <button type="submit">Tambah Obat</button>
            </form>
	    <div id="upload-error" class="error-message" style="color: red; margin-top: 10px;"></div>
        </div>
    </div>
    <script src="assets/js/upload.js"></script>
</body>
</html>

<?php
$conn->close();
?>
