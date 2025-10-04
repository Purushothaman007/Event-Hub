<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="brand">Student Portal</div>
        <nav>
            <a href="student-dashboard.php">Upcoming Events</a>
            <!-- THIS IS THE NEW LINK -->
            <a href="past-events.php">Past Events</a>
            <a href="my-events.php">My Events</a>
            <a href="profile.php">Profile</a>
            <a href="#" id="logoutLink">Logout</a>
        </nav>
    </header>

    <!-- The rest of your header file (modal, script) remains the same -->
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
        // Your existing modal script...
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
        
        if(logoutModalOverlay) {
             logoutModalOverlay.addEventListener('click', function(e) {
                if(e.target === this) {
                    logoutModalOverlay.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>
