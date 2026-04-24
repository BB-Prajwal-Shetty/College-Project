/* Custom CSS for LEASYT */

:root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    --success-color: #198754;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #0dcaf0;
    --light-color: #f8f9fa;
    --dark-color: #212529;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
    min-height: 500px;
}

.hero-section h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

/* Cards */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

/* Forms */
.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Navigation */
.navbar-brand {
    font-size: 1.5rem;
    font-weight: 700;
}

.navbar-nav .nav-link {
    font-weight: 500;
    margin: 0 0.25rem;
    transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Dropdown */
.dropdown-menu {
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.dropdown-item {
    padding: 0.75rem 1.5rem;
    transition: background-color 0.3s ease;
}

.dropdown-item:hover {
    background-color: var(--light-color);
}

/* Alerts */
.alert {
    border: none;
    border-radius: 8px;
    font-weight: 500;
}

/* Tables */
.table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 600;
    border: none;
}

.table tbody tr {
    transition: background-color 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

/* Badge */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Footer */
footer {
    background: linear-gradient(135deg, var(--dark-color) 0%, #1a1a1a 100%);
}

footer a {
    transition: color 0.3s ease;
}

footer a:hover {
    color: var(--primary-color) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .hero-section {
        min-height: 400px;
        text-align: center;
    }
}

/* Loading Spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Custom Utilities */
.text-primary-custom {
    color: var(--primary-color) !important;
}

.bg-primary-custom {
    background-color: var(--primary-color) !important;
}

.shadow-custom {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Cart and Wishlist Icons */
.cart-icon, .wishlist-icon {
    position: relative;
}

.cart-icon .badge, .wishlist-icon .badge {
    position: absolute;
    top: -8px;
    right: -8px;
    font-size: 0.75rem;
}

/* Item Cards */
.item-card {
    position: relative;
    overflow: hidden;
}

.item-card .card-img-overlay {
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.item-card:hover .card-img-overlay {
    opacity: 1;
}

/* Price Badge */
.price-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Status Badges */
.status-available {
    background-color: var(--success-color);
}

.status-rented {
    background-color: var(--warning-color);
}

.status-maintenance {
    background-color: var(--danger-color);
}

/* Dashboard Cards */
.dashboard-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-left: 4px solid var(--primary-color);
}

.dashboard-card .card-body {
    padding: 2rem;
}

.dashboard-card .display-6 {
    font-weight: 700;
    color: var(--primary-color);
}

/* Rating Stars */
.rating {
    color: #ffc107;
}

.rating .fa-star {
    margin-right: 2px;
}

/* Search and Filter */
.search-filter-section {
    background-color: var(--light-color);
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.filter-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
