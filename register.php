<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Gudang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Register User Baru</h1>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $gudang_id = $_POST['gudang_id'];

            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, gudang_id) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $password, $gudang_id])) {
                    echo '<div class="message success">User berhasil dibuat! <a href="login.php">Login disini</a></div>';
                }
            } catch (PDOException $e) {
                echo '<div class="message error">Error: ' . $e->getMessage() . '</div>';
            }
        }
        ?>

        <div class="login-form">
            <h2>Buat User Baru</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="gudang_id">Gudang ID (unik):</label>
                    <input type="text" id="gudang_id" name="gudang_id" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
        </div>
    </div>
</body>
</html>