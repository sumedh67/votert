
<?php
include 'db.php';
session_start();


// CREATE
if (isset($_POST['add_candidate'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO candidates (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $success = "Candidate added!";
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Candidate deleted!";
}

// UPDATE
if (isset($_POST['update_candidate'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $stmt = $conn->prepare("UPDATE candidates SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    $success = "Candidate updated!";
}

// FETCH CANDIDATES
$candidates = [];
$result = $conn->query("SELECT * FROM candidates");
while ($row = $result->fetch_assoc()) $candidates[] = $row;

// GET CANDIDATE TO EDIT
$edit_candidate = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_candidate = $result->fetch_assoc();
}


?>
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="assets/adming.css">
</head>
<body>
<div class="container">
    <h2>Admin Panel - Manage Candidates</h2>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <!-- ADD or UPDATE Form -->
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_candidate['id'] ?? '' ?>">
        <input type="text" name="name" placeholder="Candidate Name" required
               value="<?= htmlspecialchars($edit_candidate['name'] ?? '') ?>"><br>
        <?php if ($edit_candidate): ?>
            <button type="submit" name="update_candidate">Update Candidate</button>
            <a href="admin.php" style="margin-left:10px;">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_candidate">Add Candidate</button>
        <?php endif; ?>
    </form>

    <!-- Candidate List -->
    <h3>All Candidates:</h3>
    <ul>
        <?php foreach ($candidates as $c): ?>
            <li>
                <?= htmlspecialchars($c['name']) ?>
                - <a href="?edit=<?= $c['id'] ?>">Edit</a>
                - <a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Delete this candidate?')">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3><strong>See Result</strong></h3>

    <?php  
        $candidates = [];
        $result = $conn->query("SELECT * FROM candidates ORDER BY votes DESC");
        
        if ($result) {
            $candidates = $result->fetch_all(MYSQLI_ASSOC);
            
            // Loop through the candidates and print the data
            foreach ($candidates as $candidate) {
                echo "Candidate: " . $candidate['name'] . "<br>";
                echo "Votes: " . $candidate['votes'] . "<br><br>";
            }
        }
        
    ?>
    <p><a href="logout.php">Logout</a></p>
    
</div>
</body>
</html>