
<?php
$host = 'localhost';
$user = 'root';
$pass = 'Sumedh@2006';
$dbname = 'online_voting_system';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>
