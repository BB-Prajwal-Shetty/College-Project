<?php
// LEASYT Database Setup Script
// Run this file once to set up the database automatically

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'leasyt_db';

echo "<h2>LEASYT Database Setup</h2>";

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
    echo "<p style='color: green;'>✓ Database '$database' created successfully</p>";
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Read and execute SQL file
    $sql = file_get_contents('database/leasyt_schema.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "<p style='color: green;'>✓ Database tables created successfully</p>";
    echo "<p style='color: green;'>✓ Sample data inserted</p>";
    echo "<p style='color: green;'>✓ Admin user created (username: admin, password: password)</p>";
    
    echo "<hr>";
    echo "<h3>Setup Complete! 🎉</h3>";
    echo "<p><strong>Your LEASYT platform is ready to use:</strong></p>";
    echo "<ul>";
    echo "<li><a href='index.php' target='_blank'>Main Website</a></li>";
    echo "<li><a href='admin/login.php' target='_blank'>Admin Panel</a> (admin/password)</li>";
    echo "<li><a href='user/register.php' target='_blank'>User Registration</a></li>";
    echo "</ul>";
    
    echo "<p style='color: orange;'><strong>Note:</strong> Delete this setup.php file after setup for security.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure:</p>";
    echo "<ul>";
    echo "<li>XAMPP MySQL is running</li>";
    echo "<li>Database credentials are correct</li>";
    echo "<li>The database/leasyt_schema.sql file exists</li>";
    echo "</ul>";
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
h2 { color: #333; }
p { margin: 10px 0; }
ul { margin: 10px 0; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
