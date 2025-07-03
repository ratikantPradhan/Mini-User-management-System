<?php
require "db.php";

$id = $_GET['id'];
// soft delete only update status
$sql='UPDATE users SET status = 1 WHERE id = ?';
// $sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "User deleted successfully";
    header("Location: admin_dashboard.php");
} else {
    echo "Error deleting user";
}
?>