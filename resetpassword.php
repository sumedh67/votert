<?php
include 'db.php';
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed, $email);

    if ($stmt->execute()) {
        unset($_SESSION['reset_email']);
        $success = "Password reset successfully! <a href='login.php'>Login now</a>";
    } else {
        $error = "Failed to reset password. Try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>
    <?php if ($error) echo "<p class='error-message'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success-message'>$success</p>"; ?>
    <?php if (!$success): ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Enter new password" required><br>
        <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
