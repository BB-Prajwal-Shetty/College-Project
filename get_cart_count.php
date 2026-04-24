// Custom JavaScript for LEASYT

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('password-strength');
    
    if (passwordInput && passwordStrength) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            
            passwordStrength.className = 'password-strength';
            passwordStrength.classList.add(strength.class);
            passwordStrength.textContent = strength.text;
        });
    }

    // Date picker restrictions
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (startDateInput) {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;
        
        startDateInput.addEventListener('change', function() {
            if (endDateInput) {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
                calculateRentalCost();
            }
        });
    }
    
    if (endDateInput) {
        endDateInput.addEventListener('change', function() {
            calculateRentalCost();
        });
    }

    // Search functionality
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else {
                if (searchResults) {
                    searchResults.innerHTML = '';
                }
            }
        });
    }

    // Quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const isIncrement = this.classList.contains('quantity-increment');
            let value = parseInt(input.value) || 1;
            
            if (isIncrement) {
                value++;
            } else if (value > 1) {
                value--;
            }
            
            input.value = value;
            calculateRentalCost();
        });
    });

    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    let text = '';
    let className = '';
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    switch (strength) {
        case 0:
        case 1:
            text = 'Very Weak';
            className = 'very-weak';
            break;
        case 2:
            text = 'Weak';
            className = 'weak';
            break;
        case 3:
            text = 'Medium';
            className = 'medium';
            break;
        case 4:
            text = 'Strong';
            className = 'strong';
            break;
        case 5:
            text = 'Very Strong';
            className = 'very-strong';
            break;
    }
    
    return { text, class: className };
}

// Calculate rental cost
function calculateRentalCost() {
    const startDate = document.getElementById('start_date')?.value;
    const endDate = document.getElementById('end_date')?.value;
    const quantity = document.getElementById('quantity')?.value || 1;
    const rentPrice = parseFloat(document.getElementById('rent_price')?.value || 0);
    const securityDeposit = parseFloat(document.getElementById('security_deposit')?.value || 0);
    
    if (startDate && endDate && rentPrice) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
        
        if (days > 0) {
            const subtotal = rentPrice * days * quantity;
            const total = subtotal + securityDeposit;
            
            document.getElementById('rental-days').textContent = days;
            document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
            document.getElementById('total-amount').textContent = '₹' + total.toFixed(2);
        }
    }
}

// Search functionality
function performSearch(query) {
    fetch('includes/search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'query=' + encodeURIComponent(query)
    })
    .then(response => response.json())
    .then(data => {
        displaySearchResults(data);
    })
    .catch(error => {
        console.error('Search error:', error);
    });
}

// Display search results
function displaySearchResults(results) {
    const searchResults = document.getElementById('search-results');
    if (!searchResults) return;
    
    if (results.length === 0) {
        searchResults.innerHTML = '<div class="text-muted p-3">No results found</div>';
        return;
    }
    
    let html = '';
    results.forEach(item => {
        html += `
            <div class="search-result-item p-3 border-bottom">
                <div class="d-flex">
                    <img src="${item.image || 'assets/images/placeholder.jpg'}" alt="${item.item_name}" class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.item_name}</h6>
                        <p class="text-muted small mb-1">${item.category_name}</p>
                        <p class="text-primary fw-bold mb-0">₹${item.rent_price}/day</p>
                    </div>
                    <div class="text-end">
                        <a href="user/item_details.php?id=${item.item_id}" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            </div>
        `;
    });
    
    searchResults.innerHTML = html;
}

// Add to cart
function addToCart(itemId, startDate, endDate, quantity = 1) {
    if (!startDate || !endDate) {
        alert('Please select rental dates');
        return;
    }
    
    const formData = new FormData();
    formData.append('item_id', itemId);
    formData.append('start_date', startDate);
    formData.append('end_date', endDate);
    formData.append('quantity', quantity);
    
    fetch('includes/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item added to cart successfully!', 'success');
            updateCartCount();
        } else {
            showAlert(data.message || 'Failed to add item to cart', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    });
}

// Add to wishlist
function addToWishlist(itemId) {
    const formData = new FormData();
    formData.append('item_id', itemId);
    
    fetch('includes/add_to_wishlist.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item added to wishlist!', 'success');
            const btn = document.querySelector(`[onclick="addToWishlist(${itemId})"]`);
            if (btn) {
                btn.innerHTML = '<i class="fas fa-heart"></i> In Wishlist';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
                btn.onclick = () => removeFromWishlist(itemId);
            }
        } else {
            showAlert(data.message || 'Failed to add to wishlist', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    });
}

// Remove from wishlist
function removeFromWishlist(itemId) {
    const formData = new FormData();
    formData.append('item_id', itemId);
    formData.append('action', 'remove');
    
    fetch('includes/add_to_wishlist.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item removed from wishlist!', 'info');
            const btn = document.querySelector(`[onclick="removeFromWishlist(${itemId})"]`);
            if (btn) {
                btn.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
                btn.onclick = () => addToWishlist(itemId);
            }
        } else {
            showAlert(data.message || 'Failed to remove from wishlist', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    });
}

// Update cart count
function updateCartCount() {
    fetch('includes/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.querySelector('.cart-icon .badge');
        if (cartBadge) {
            if (data.count > 0) {
                cartBadge.textContent = data.count;
                cartBadge.style.display = 'inline';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Show alert message
function showAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertContainer);
    
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.parentNode.removeChild(alertContainer);
        }
    }, 5000);
}

// Confirm delete
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Format currency
function formatCurrency(amount) {
    return '₹' + parseFloat(amount).toFixed(2);
}
