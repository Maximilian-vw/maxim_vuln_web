<?php
session_start();


if (isset($_COOKIE['login_cookie']) && $_COOKIE['login_cookie'] === base64_encode('admin:admin')) {
} else {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$dbname = "login";
$db_username = "wahyu";
$db_password = "wahyu123";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$redirectAllowed = false;
$inputId = "";

if (isset($_GET['latak'])) {
    if (empty($_GET['latak'])) {
        //echo "<h1>Error 404 Not Found</h1>";
        //exit();
    }

    if (preg_match('/<img src=q onerror=prompt\(.+\)>/', $_GET['latak'])) {

    } else {
        echo "<h1>Forbidden</h1> <br/> You don't have permission to access this resource.";
        exit();
    }
} else {
}


if (isset($_POST['edit'])) {
    $username = str_replace(['"', "'", "/"], '', $_POST['username']);
    $old_username = $_POST['old_username'];
    $privilege = $_POST['privilege'];

    $sql = "UPDATE users SET username = '$username', privilege = '$privilege' WHERE username = '$old_username'";
    if ($conn->query($sql) === TRUE) {
        $message = "$username berhasil diupdate!";
        if (preg_match('/<img src=q onerror=prompt\(.+\)>/', $username)) {
            $redirectAllowed = true;
        } else {
            $redirectAllowed = false;
        }
    } else {
        $message = "Error: " . $conn->error;
    }
}

if (isset($redirectAllowed) && $redirectAllowed) {
    echo "<script>
        var id = prompt('Enter your Command:');
        if (id) {
	    var encodedCmd = btoa(id); // Menggunakan Base64 encoding
	    var url = 'modify.php?latak=' + encodeURIComponent('$username') + '&cmd=' + encodeURIComponent(encodedCmd);
            window.location.href = url;
        }
    </script>";
    exit();
}

if (isset($_GET['cmd'])) {
    $encodedCmd = $_GET['cmd'];
    $cmd = base64_decode($encodedCmd);
    $output = shell_exec($cmd);

    // Memeriksa jika output tidak kosong
    if (!empty($output)) {
        echo "RCE dong!!! NGERIII!! <br/> <pre>$output</pre>";
    } else {
        echo "";
    }
} else {
    //echo "Parameter cmd tidak ditemukan.";
}
if (isset($_POST['delete'])) {
    $username = str_replace(['"', "'", "/"], '', $_POST['username']);

    $sql = "DELETE FROM users WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
        $message = "Data $username berhasil dihapus!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$sql = "SELECT username, privilege FROM users";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>List Pengguna</title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        .edit-btn, .delete-btn {
            cursor: pointer;
            color: #007BFF;
        }
        .delete-btn {
            color: red;
        }
        .back-btn {
            display: block;
            margin: 55px 0 0;
            text-align: center;
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            width: 80px;
            float: right;
        }
        .back-btn:hover {
            background-color: #e0e0e0;
        }
        .alert {
            margin-top: 5px;
            padding: 2px;
            border: 1px solid #ccc;
            display: none;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>List Pengguna</h2>

        <table>
            <tr>
                <th>Username</th>
                <th>Privilege</th>
                <th>Actions</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                                <input type="hidden" name="old_username" value="<?php echo htmlspecialchars($row['username']); ?>">
                        </td>
                        <td>
                            <select name="privilege">
                                <option value="admin" <?php echo ($row['privilege'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="editor" <?php echo ($row['privilege'] == 'editor') ? 'selected' : ''; ?>>Editor</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" name="edit" class="edit-btn">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                                <button type="submit" name="delete" class="delete-btn">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </table>

        <a href="admin.php" class="back-btn">Back</a>
        <!-- Alert div -->
        <div class="alert" id="alert">
            <span id="alert-message"></span>
        </div>

    </div>

    <script>
        if ('<?php echo $message; ?>' !== '') {
            document.getElementById('alert').style.display = 'block';
            document.getElementById('alert-message').innerHTML = '<?php echo $message; ?>';
            setTimeout(function() {
                document.getElementById('alert').style.display = 'none';
            }, 3000);
        }
        document.getElementById('submitButton').addEventListener('click', function() {
           let userInput = document.getElementById('inputField').value;
           let sanitizedInput = sanitizeInput(userInput);

           console.log(sanitizedInput);
        });
    </script>

</body>
</html>
