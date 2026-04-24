<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $count_query = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
    $count_stmt = mysqli_prepare($conn, $count_query);
    mysqli_stmt_bind_param($count_stmt, "i", $user_id);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $count_data = mysqli_fetch_assoc($count_result);
    
    $_SESSION['cart_count'] = $count_data['count'];
    
    echo json_encode(['count' => $count_data['count']]);
} else {
    echo json_encode(['count' => 0]);
}
?>
