<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_id = (int)$_POST['item_id'];
    $start_date = sanitize_input($_POST['start_date']);
    $end_date = sanitize_input($_POST['end_date']);
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Validate inputs
    if (empty($item_id) || empty($start_date) || empty($end_date)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }
    
    if ($quantity < 1 || $quantity > 10) {
        echo json_encode(['success' => false, 'message' => 'Quantity must be between 1 and 10']);
        exit();
    }
    
    // Validate dates
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $today = new DateTime();
    
    if ($start < $today) {
        echo json_encode(['success' => false, 'message' => 'Start date cannot be in the past']);
        exit();
    }
    
    if ($end <= $start) {
        echo json_encode(['success' => false, 'message' => 'End date must be after start date']);
        exit();
    }
    
    // Check if item exists and is available
    $item_query = "SELECT item_id, item_name, rent_price, availability_status FROM items 
                   WHERE item_id = ? AND availability_status = 'Available'";
    $item_stmt = mysqli_prepare($conn, $item_query);
    mysqli_stmt_bind_param($item_stmt, "i", $item_id);
    mysqli_stmt_execute($item_stmt);
    $item_result = mysqli_stmt_get_result($item_stmt);
    
    if (mysqli_num_rows($item_result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Item not found or not available']);
        exit();
    }
    
    $item = mysqli_fetch_assoc($item_result);
    
    // Check if item is already booked for the selected dates
    $booking_check_query = "SELECT booking_id FROM bookings 
                           WHERE item_id = ? AND booking_status = 'Active' 
                           AND ((start_date <= ? AND end_date >= ?) 
                                OR (start_date <= ? AND end_date >= ?)
                                OR (start_date >= ? AND end_date <= ?))";
    $booking_stmt = mysqli_prepare($conn, $booking_check_query);
    mysqli_stmt_bind_param($booking_stmt, "issssss", $item_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    mysqli_stmt_execute($booking_stmt);
    $booking_result = mysqli_stmt_get_result($booking_stmt);
    
    if (mysqli_num_rows($booking_result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Item is already booked for the selected dates']);
        exit();
    }
    
    // Check if item is already in cart for the same dates
    $cart_check_query = "SELECT cart_id FROM cart 
                        WHERE user_id = ? AND item_id = ? 
                        AND ((rent_start_date <= ? AND rent_end_date >= ?) 
                             OR (rent_start_date <= ? AND rent_end_date >= ?)
                             OR (rent_start_date >= ? AND rent_end_date <= ?))";
    $cart_stmt = mysqli_prepare($conn, $cart_check_query);
    mysqli_stmt_bind_param($cart_stmt, "iissssss", $user_id, $item_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    mysqli_stmt_execute($cart_stmt);
    $cart_result = mysqli_stmt_get_result($cart_stmt);
    
    if (mysqli_num_rows($cart_result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Item is already in your cart for overlapping dates']);
        exit();
    }
    
    // Calculate rental days and subtotal
    $rental_days = calculate_days($start_date, $end_date);
    $subtotal = $item['rent_price'] * $rental_days * $quantity;
    
    // Add to cart
    $insert_query = "INSERT INTO cart (user_id, item_id, rent_start_date, rent_end_date, quantity, rent_price, subtotal) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "iissidi", $user_id, $item_id, $start_date, $end_date, $quantity, $item['rent_price'], $subtotal);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        // Update session cart count
        $count_query = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
        $count_stmt = mysqli_prepare($conn, $count_query);
        mysqli_stmt_bind_param($count_stmt, "i", $user_id);
        mysqli_stmt_execute($count_stmt);
        $count_result = mysqli_stmt_get_result($count_stmt);
        $count_data = mysqli_fetch_assoc($count_result);
        $_SESSION['cart_count'] = $count_data['count'];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Item added to cart successfully',
            'cart_count' => $count_data['count']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
