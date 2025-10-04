<?php
require_once 'db_config.php';
// Auth check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Check if an event ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage-events.php");
    exit();
}

$event_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    die("Event not found.");
}
?>
<?php include 'header_admin.php'; ?>

<div class="container">
    <h2 class="page-title">Edit Event: <?php echo htmlspecialchars($event['title']); ?></h2>
    <div class="card">
        <form action="event-actions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
            
            <div class="form-row">
                <label for="title">Event Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>
            <div class="form-row">
                <label for="event_datetime">Date and Time</label>
                <input type="datetime-local" name="event_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_datetime'])); ?>" required>
            </div>
            <div class="form-row">
                <label for="location">Location</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>">
            </div>
            <div class="form-row">
                <label for="description">Description</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="form-row">
                <label>Current Poster</label>
                <div>
                    <?php if (!empty($event['poster'])): ?>
                        <img src="data:<?php echo htmlspecialchars($event['poster_type']); ?>;base64,<?php echo base64_encode($event['poster']); ?>" alt="Current Poster" style="max-width: 200px; border-radius: 8px;">
                    <?php else: ?>
                        <p>No poster has been uploaded for this event.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <label for="poster">Upload New Poster (Optional)</label>
                <input type="file" name="poster" accept="image/*">
                <small>If you upload a new poster, it will replace the current one.</small>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="manage-events.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>