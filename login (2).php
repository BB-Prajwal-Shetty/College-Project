<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">LEASYT</h1>
                <p class="lead">Your Ultimate Rental Platform</p>
                <p class="mb-4">Rent anything from jewelry to vehicles, cameras to books - all in one place!</p>
                <div class="d-flex gap-3">
                    <a href="user/register.php" class="btn btn-light btn-lg">Get Started</a>
                    <a href="#browse" class="btn btn-outline-light btn-lg">Browse Items</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/hero-image.jpg" alt="Rental Platform" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<section id="browse" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Browse Categories</h2>
        <div class="row g-4">
            <?php
            $categories_query = "SELECT * FROM categories WHERE status = 'Active' ORDER BY category_name";
            $categories_result = mysqli_query($conn, $categories_query);
            
            if (mysqli_num_rows($categories_result) > 0) {
                while ($category = mysqli_fetch_assoc($categories_result)) {
                    echo '<div class="col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-' . ($category['icon'] ?: 'box') . ' fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">' . htmlspecialchars($category['category_name']) . '</h5>
                                    <p class="card-text">' . htmlspecialchars($category['description']) . '</p>
                                    <a href="user/browse.php?category=' . $category['category_id'] . '" class="btn btn-primary">Browse</a>
                                </div>
                            </div>
                          </div>';
                }
            } else {
                echo '<div class="col-12 text-center">
                        <p class="text-muted">No categories available at the moment.</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Items</h2>
        <div class="row g-4">
            <?php
            $featured_query = "SELECT i.*, c.category_name FROM items i 
                              JOIN categories c ON i.category_id = c.category_id 
                              WHERE i.availability_status = 'Available' 
                              ORDER BY i.added_date DESC LIMIT 8";
            $featured_result = mysqli_query($conn, $featured_query);
            
            if (mysqli_num_rows($featured_result) > 0) {
                while ($item = mysqli_fetch_assoc($featured_result)) {
                    echo '<div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <img src="' . ($item['image'] ?: 'assets/images/placeholder.jpg') . '" class="card-img-top" alt="' . htmlspecialchars($item['item_name']) . '" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title">' . htmlspecialchars($item['item_name']) . '</h6>
                                    <p class="card-text text-muted small">' . htmlspecialchars($item['category_name']) . '</p>
                                    <p class="card-text"><strong>₹' . number_format($item['rent_price'], 2) . '/day</strong></p>
                                    <a href="user/item_details.php?id=' . $item['item_id'] . '" class="btn btn-primary btn-sm">View Details</a>
                                </div>
                            </div>
                          </div>';
                }
            } else {
                echo '<div class="col-12 text-center">
                        <p class="text-muted">No items available at the moment.</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
