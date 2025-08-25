
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get all candidates ordered by votes
$candidates = [];
$result = $conn->query("SELECT * FROM candidates ORDER BY votes DESC");
if ($result) {
    $candidates = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Results - Online Voting System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Voting Results</h2>
    
    <div class="results-container">
        <?php foreach ($candidates as $candidate): ?>
            <div class="candidate-result">
                <h3><?php echo htmlspecialchars($candidate['name']); ?></h3>
                <div class="vote-count"><?php echo $candidate['votes']; ?> votes</div>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php 
                        $total = array_sum(array_column($candidates, 'votes'));
                        echo $total > 0 ? ($candidate['votes'] / $total * 100) : 0;
                    ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="action-buttons">
        <?php if ($_SESSION['is_admin']): ?>
            <a href="admin.php" class="btn">Admin Panel</a>
        <?php else: ?>
            <a href="vote.php" class="btn">Back to Voting</a>
        <?php endif; ?>
        <a href="logout.php" class="btn secondary-btn">Logout</a>
    </div>
</div>
</body>
</html>