<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_status = ($_POST['current_status'] === 'active') ? 'inactive' : 'active';
    $conn = getDatabaseConnection();
    
    if ($user_id == $_SESSION['user_id']) {
        header("Location: users.php?error=self");
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $user_id);
    $stmt->execute();
    header("Location: users.php?success=updated");
}
?>