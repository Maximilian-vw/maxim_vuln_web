<?php
session_start(); // Start a session to manage user sessions
$errorMessage = "";

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    // Use one of the above alternatives here
    $csrfToken = uniqid('', true); // Example using uniqid
    $_SESSION['csrf_token'] = $csrfToken;
}

// Check if cookie is set to admin:admin
if (isset($_COOKIE['login_cookie']) && $_COOKIE['login_cookie'] === base64_encode('admin:admin')) {
    header("Location: admin.php");
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username = "wahyu";
$password = "wahyu123";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Memeriksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Mencari user di database
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $password); // Bind username dan password
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Login berhasil
            if ($user['username'] === 'admin') {
                $cookieValue = base64_encode('admin:admin');
                setcookie('login_cookie', $cookieValue, time() + 360 , "/"); // Set cookie for 10 days
                header("Location: admin.php");
                exit();
            } else {
                $cookieValue = base64_encode($user['username'] . ':' . $user['password']);
                setcookie('login_cookie', $cookieValue, time() + (86400 * 10), "/"); // Set cookie for 10 days
                header("Location: user.php");
                exit();
            }
        } else {
            $errorMessage = "Username atau password salah";
        }

        $stmt->close();
    } else {
        $errorMessage = "Error preparing statement";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"> <!-- CSRF token -->
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" id="login-btn">Masuk</button>
            <p id="error-message" class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
        </form>
    </div>
    <script src="assets/js/login.js"></script>
</body>
</html>
