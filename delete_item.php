<?php
include 'config.php'; 

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit(); 
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ? AND gudang_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['gudang_id']]); 

    header('Location: items.php'); 
    exit(); //
} else {
    header('Location: items.php'); 
    exit(); //
}
?>