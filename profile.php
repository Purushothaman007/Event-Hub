<?php
require_once 'db_config.php';
// Auth check
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$message = '';
$message_type = 'success'; // can be 'success' or 'error'

// Handle profile update
if (isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    
    if (!empty($new_name) && !empty($new_email)) {
        // Check if the new email is already taken by ANOTHER user
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt_check->bind_param("si", $new_email, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $message = "That email is already taken. Please choose another.";
            $message_type = 'error';
        } else {
            // Email is available, proceed with the update
            $stmt_update = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt_update->bind_param("ssi", $new_name, $new_email, $user_id);
            
            if ($stmt_update->execute()) {
                $_SESSION['user']['name'] = $new_name;
                $_SESSION['user']['email'] = $new_email;
                $message = "Profile updated successfully!";
                $message_type = 'success';
            } else {
                $message = "Error updating profile.";
                $message_type = 'error';
            }
            $stmt_update->close();
        }
        $stmt_check->close();
    }
}

// Get the user's first initial for the avatar
$user_initial = strtoupper(substr($_SESSION['user']['name'], 0, 1));
?>

<?php include 'header_student.php'; // Or your relevant header ?>

<div class="container">
    <h2 class="page-title">Your Profile</h2>

    <div class="card">
        <?php if ($message): ?>
            <div class="feedback-message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="profile-layout">
            <div class="profile-avatar">
                <?php echo htmlspecialchars($user_initial); ?>
            </div>

            <div class="profile-form">
                <form action="profile.php" method="POST">
                    <div class="form-row">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>" required>
                    </div>
                    <div class="form-row">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
                    </div>
                     <div class="form-row">
                        <label for="role">Role</label>
                        <input type="text" id="role" value="<?php echo htmlspecialchars(ucfirst($_SESSION['user']['role'])); ?>" disabled style="cursor: not-allowed;">
                    </div>
                    <div class="button-group">
                        <button type="submit" name="update_profile" class="btn">Save Changes</button>
                        <a href="student-dashboard.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>