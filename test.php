<?php
// Set error reporting to see all potential issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Login Test Script</h1>";

// 1. Include your database configuration
require_once 'db_config.php';
echo "<p>✅ Database config included.</p>";

// --- Test Database Connection ---
if ($conn) {
    echo "<p>✅ Successfully connected to the database '{$conn->real_escape_string(DB_NAME)}'.</p>";
} else {
    echo "<p>❌ <strong>Error:</strong> Could not connect to the database.</p>";
    die(); // Stop the script if connection fails
}

// 2. Define the credentials to test
$test_email = 'admin@college.edu';
$test_password = 'admin'; // The plain-text password you type in the form

echo "<p><strong>Testing with:</strong><br>Email: {$test_email}<br>Password: {$test_password}</p>";
echo "<hr>";

// 3. Fetch the admin user from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $test_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2>Result: FAILURE ❌</h2>";
    echo "<p><strong>Reason:</strong> No user found with the email '{$test_email}'. Please check your database.</p>";
    die();
}

// 4. Get the user data and verify the password
$user = $result->fetch_assoc();
$hashed_password_from_db = $user['password'];

echo "<p><strong>Hashed Password from DB:</strong><br><code>{$hashed_password_from_db}</code></p>";

if (password_verify($test_password, $hashed_password_from_db)) {
    echo "<h2>Result: SUCCESS! ✅</h2>";
    echo "<p>The password '{$test_password}' correctly matches the hash in the database. Your login logic is working!</p>";
} else {
    echo "<h2>Result: FAILURE ❌</h2>";
    echo "<p><strong>Reason:</strong> The password '{$test_password}' does NOT match the hash in the database. Make sure you ran the UPDATE SQL command correctly.</p>";
}

// Close the connection
$stmt->close();
$conn->close();

?>