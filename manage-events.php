<?php
require_once 'db_config.php';
// Auth check for admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$events_result = $conn->query("SELECT * FROM events ORDER BY event_datetime DESC");
?>

<?php include 'header_admin.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 class="page-title" style="margin-bottom: 0;">Manage Events</h2>
        <a href="create-event.php" class="btn">Create New Event</a>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date & Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($events_result->num_rows > 0): ?>
                    <?php while($event = $events_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo date('M d, Y, h:i A', strtotime($event['event_datetime'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit-event.php?id=<?php echo $event['id']; ?>" class="btn btn-secondary">Edit</a>
                                <a href="event-actions.php?action=delete&id=<?php echo $event['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this event?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No events found. Create one to get started!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- THIS BUTTON HAS BEEN ADDED -->
        <a href="admin-dashboard.php" class="btn btn-secondary" style="margin-top: 2rem;">Back to Dashboard</a>
    </div>
</div>