
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['is_admin']) {
    header("Location: admin.php");
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    if ($_SESSION['has_voted']) {
        $message = "You have already voted!";
    } else {
        $candidate_id = (int)$_POST['candidate_id'];
        
        // Update candidate votes
        $stmt = $conn->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = ?");
        $stmt->bind_param("i", $candidate_id);
        $stmt->execute();
        
        // Mark user as voted
        $stmt = $conn->prepare("UPDATE users SET has_voted = 1 WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        
        $_SESSION['has_voted'] = 1;
        $message = "Thank you for voting!";
    }
}

// Get all candidates
$candidates = [];
$result = $conn->query("SELECT * FROM candidates");
if ($result) {
    $candidates = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vote - Online Voting System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Cast Your Vote</h2>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($_SESSION['has_voted']): ?>
        <div class="already-voted">
            <p>You have already voted. Thank you for participating!</p>
            <a href="results.php" class="btn">View Results</a>
        </div>
    <?php else: ?>
        <form method="POST" class="voting-form">
            <div class="candidates-list">
                <?php foreach ($candidates as $candidate): ?>
                    <div class="candidate-option">
                        <input type="radio" name="candidate_id" id="candidate<?php echo $candidate['id']; ?>" 
                               value="<?php echo $candidate['id']; ?>" required>
                        <label for="candidate<?php echo $candidate['id']; ?>">
                            <?php echo htmlspecialchars($candidate['name']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="vote" class="btn">Submit Vote</button>
        </form>
    <?php endif; ?>
    
    <div class="logout-link">
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>