<?php
require_once 'db_config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<?php include 'header_admin.php'; ?>

<div class="container">
    <h2 class="page-title">Dashboard Overview</h2>
    
    <div class="card">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div class="card" style="text-align:center;">
                <h3>Manage Events</h3>
                <p>Create, edit, and delete events for the college calendar.</p>
                <a href="manage-events.php" class="btn" style="margin-top: 15px;">Go to Events</a>
            </div>

            <div class="card" style="text-align:center;">
                <h3>Manage Users</h3>
                <p>View and manage registered user accounts.</p>
                <a href="manage-users.php" class="btn" style="margin-top: 15px;">Go to Users</a>
            </div>

            <div class="card" style="text-align:center;">
                <h3>View Reports</h3>
                <p>Analyze event attendance and user stats.</p>
                <a href="view-reports.php" class="btn" style="margin-top: 15px;">Go to Reports</a>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:2rem; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h3>
                <p>Use the panels above to manage your application.</p>
            </div>
            <div>
                <a href="create-event.php" class="btn">Quick Create Event</a>
            </div>
        </div>
    </div>
</div>