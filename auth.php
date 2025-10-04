<?php
require_once 'db_config.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: index.php?error=Email and password are required.");
        exit();
    }
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User exists, verify password
        $user = $result->fetch_assoc();
        
        // --- MODIFICATION #1: Replaced password_verify() with a direct comparison ---
        if ($password === $user['password']) { // INSECURE plain text check
            // Password is correct, set session
            $_SESSION['user'] = $user;
            if ($user['role'] === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: student-dashboard.php");
            }
        } else {
            header("Location: index.php?error=Invalid password.");
        }
    } else {
        // User does not exist. If it's an admin email, it's an error.
        if ($email === 'admin@college.edu') {
             header("Location: index.php?error=Invalid admin credentials.");
             exit();
        }
        
        // If not admin, it's a new student. Create the account.
        $name = explode('@', $email)[0]; // Default name from email
        
        // --- MODIFICATION #2: Removed password_hash() ---
        $plain_password = $password; // Storing the password as plain text
        $role = 'student';

        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        // Pass the plain password directly to the database
        $insert_stmt->bind_param("ssss", $name, $email, $plain_password, $role);
        
        if ($insert_stmt->execute()) {
            // New student created, log them in
            $user_id = $conn->insert_id;
            $_SESSION['user'] = [
                'id' => $user_id,
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];
            header("Location: student-dashboard.php");
        } else {
            header("Location: index.php?error=Registration failed.");
        }
        $insert_stmt->close();
    }
    $stmt->close();
    $conn->close();
}
?>