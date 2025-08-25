
<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        $error = "Registration failed. Email may already be used.";
    }
}
?>
<!DOCTYPE html>
<html>
<h1 style="text-align:center; font-family:Arial, sans-serif; color:#2c3e50;">
    <strong>Online Voting System</strong>
</h1>

<head><title>Register</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Login here</a></p>
    <p>admin <a href="admin.php">Login here</a></p>
</div>
</body>
</html>
