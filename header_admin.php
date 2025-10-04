<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="brand">EventHub Admin</div>
        <nav>
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="manage-users.php">Users</a>
            <a href="view-reports.php">Reports</a>
            <a href="#" id="logoutLink">Logout</a>
        </nav>
    </header>

    <div class="modal-overlay" id="logoutModalOverlay">
        <div class="modal">
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to log out of your account?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelLogoutBtn">Cancel</button>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <script>
        // Logic for the custom logout modal
        const logoutLink = document.getElementById('logoutLink');
        const logoutModalOverlay = document.getElementById('logoutModalOverlay');
        const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');

        if(logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                logoutModalOverlay.classList.add('active');
            });
        }

        if(cancelLogoutBtn) {
            cancelLogoutBtn.addEventListener('click', function() {
                logoutModalOverlay.classList.remove('active');
            });
        }
        
        // Also close modal if clicking on the overlay background
        if(logoutModalOverlay) {
             logoutModalOverlay.addEventListener('click', function(e) {
                if(e.target === this) {
                    logoutModalOverlay.classList.remove('active');
                }
            });
        }
    </script>