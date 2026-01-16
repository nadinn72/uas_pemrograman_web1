<?php
// test-login-direct.php
session_start();
require_once 'config/database.php';
require_once 'models/User.php';

echo "<h2>Test Login Langsung</h2>";

// Test 1: Cek database connection
$database = new Database();
$conn = $database->connect();
echo "Database connection: " . ($conn ? "OK" : "FAILED") . "<br>";

// Test 2: Cek user di database
$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll();

echo "<h3>Users in database:</h3>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Password</th><th>Role</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['name'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td>" . $user['password'] . "</td>";
    echo "<td>" . $user['role'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test 3: Coba login
$userModel = new User();

echo "<h3>Test Login dengan admin@film.com / admin123:</h3>";
$result = $userModel->login('admin@film.com', 'admin123');
echo "Login result: " . ($result ? "SUCCESS" : "FAILED") . "<br>";

echo "<h3>Test Login dengan user@film.com / user123:</h3>";
$result = $userModel->login('user@film.com', 'user123');
echo "Login result: " . ($result ? "SUCCESS" : "FAILED") . "<br>";

// Test 4: Cek password verify
echo "<h3>Test Password Verify:</h3>";
$test_password = 'admin123';
$test_hash = password_hash($test_password, PASSWORD_DEFAULT);
echo "Password: $test_password<br>";
echo "New hash: $test_hash<br>";
echo "Verify with itself: " . (password_verify($test_password, $test_hash) ? "TRUE" : "FALSE") . "<br>";

// Update password dengan hash jika perlu
if (isset($_GET['updatehash'])) {
    $new_hash_admin = password_hash('admin123', PASSWORD_DEFAULT);
    $new_hash_user = password_hash('user123', PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$new_hash_admin, 'admin@film.com']);
    $stmt->execute([$new_hash_user, 'user@film.com']);
    
    echo "<h3>Password updated with hash!</h3>";
    echo "Admin hash: $new_hash_admin<br>";
    echo "User hash: $new_hash_user<br>";
}

echo '<br><a href="test-login-direct.php?updatehash=1">Update password dengan hash baru</a>';
?>