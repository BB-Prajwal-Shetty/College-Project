# LEASYT - PHP Rental Platform

A comprehensive rental platform built with PHP, MySQL, Bootstrap, and JavaScript. Users can browse and rent various items like jewelry, vehicles, cameras, books, and more.

## 🚀 Features

### User Features
- **User Registration & Login** - Secure authentication with password hashing
- **Item Browsing** - Advanced search and filtering by category, price, location
- **Shopping Cart** - Add items with rental dates and quantities
- **Checkout System** - Complete booking with multiple payment options
- **User Dashboard** - View bookings, cart, wishlist, and rental history
- **Wishlist** - Save favorite items for later
- **Reviews & Ratings** - Rate and review rented items
- **Notifications** - Get updates on bookings and payments

### Admin Features
- **Admin Dashboard** - Overview of platform statistics
- **User Management** - View and manage user accounts
- **Item Management** - Add, edit, delete rental items
- **Category Management** - Organize items into categories
- **Booking Management** - Track and manage all bookings
- **Payment Management** - Monitor transactions and payments
- **Reports & Analytics** - Generate business insights

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Framework**: Bootstrap 5.3
- **Icons**: Font Awesome 6.4
- **Server**: Apache (XAMPP)

## 📋 Prerequisites

- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Safari, Edge)
- Text editor/IDE (VS Code, Sublime Text, etc.)

## 🔧 Installation & Setup

### 1. Download and Install XAMPP
- Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
- Install and start Apache and MySQL services

### 2. Clone/Copy Project Files
```bash
# Copy the LEASYT folder to your XAMPP htdocs directory
# Path should be: C:\xampp\htdocs\hndsurf\LEASYT\
```

### 3. Database Setup
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `leasyt_db`
3. Import the database schema:
   - Go to the `leasyt_db` database
   - Click on "SQL" tab
   - Copy and paste the contents from `database/leasyt_schema.sql`
   - Click "Go" to execute

### 4. Configuration
- Database settings are already configured in `includes/db.php`
- Default settings:
  - Host: localhost
  - Username: root
  - Password: (empty)
  - Database: leasyt_db

### 5. Access the Application
- **Main Site**: `http://localhost/hndsurf/LEASYT/`
- **Admin Panel**: `http://localhost/hndsurf/LEASYT/admin/login.php`

## 🔐 Default Login Credentials

### Admin Login
- **Username**: admin
- **Password**: password

### Demo User (Create via registration or add manually)
- **Username**: demo_user
- **Email**: demo@example.com
- **Password**: password123

## 📁 Project Structure

```
LEASYT/
├── admin/                  # Admin panel files
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin dashboard
│   └── logout.php         # Admin logout
├── user/                   # User-facing files
│   ├── register.php       # User registration
│   ├── login.php          # User login
│   ├── dashboard.php      # User dashboard
│   ├── browse.php         # Browse items
│   ├── cart.php           # Shopping cart
│   ├── checkout.php       # Checkout process
│   └── logout.php         # User logout
├── includes/               # Shared PHP files
│   ├── db.php             # Database connection
│   ├── header.php         # Common header
│   ├── footer.php         # Common footer
│   └── add_to_cart.php    # Cart functionality
├── assets/                 # Static assets
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Images
├── database/               # Database files
│   └── leasyt_schema.sql  # Database schema
├── index.php              # Homepage
└── README.md              # This file
```

## 🗄️ Database Schema

### Main Tables
- **users** - User account information
- **admin** - Admin account information
- **categories** - Item categories
- **items** - Rental items
- **cart** - Shopping cart items
- **wishlist** - User wishlist
- **bookings** - Rental bookings
- **payments** - Payment transactions
- **reviews** - Item reviews and ratings
- **notifications** - User notifications

## 🎯 Key Features Implemented

### ✅ Completed Features
1. **Project Structure** - Complete folder organization
2. **Database Schema** - All tables with relationships
3. **User Authentication** - Registration, login, logout
4. **Admin Panel** - Login and dashboard
5. **Item Browsing** - Search, filter, pagination
6. **Shopping Cart** - Add, update, remove items
7. **Checkout System** - Complete booking process

### 🚧 Pending Features
- Wishlist functionality
- Reviews and ratings system
- Admin item management
- Payment gateway integration
- Email notifications
- Advanced reporting

## 🔧 Customization

### Adding New Categories
1. Go to Admin Panel
2. Navigate to Category Management
3. Add new categories with icons

### Modifying Styles
- Edit `assets/css/style.css` for custom styling
- Bootstrap classes can be customized

### Adding Payment Gateways
- Modify `user/checkout.php`
- Integrate with Razorpay, Stripe, or other gateways

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check if MySQL is running in XAMPP
   - Verify database credentials in `includes/db.php`

2. **Page Not Found (404)**
   - Ensure correct file paths
   - Check if Apache is running

3. **Permission Errors**
   - Set proper folder permissions
   - Ensure XAMPP has write access

4. **Session Issues**
   - Clear browser cookies
   - Restart Apache server

## 📝 Usage Guide

### For Users
1. **Register** - Create a new account
2. **Browse** - Explore available items
3. **Add to Cart** - Select rental dates and add items
4. **Checkout** - Complete booking with payment
5. **Manage** - View bookings in dashboard

### For Admins
1. **Login** - Access admin panel
2. **Dashboard** - View platform statistics
3. **Manage Items** - Add/edit rental items
4. **Monitor Bookings** - Track all rentals
5. **Generate Reports** - Analyze business data

## 🔒 Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Session management for authentication
- CSRF protection on forms

## 🚀 Future Enhancements

- Mobile app development
- Real-time notifications
- Advanced analytics dashboard
- Multi-language support
- Social media integration
- Automated email/SMS alerts

## 📞 Support

For any issues or questions:
- Check the troubleshooting section
- Review the code comments
- Test with demo data first

## 📄 License

This project is created for educational and demonstration purposes.

---

**LEASYT** - Making rentals easy and accessible! 🎉
