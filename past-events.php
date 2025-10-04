<?php
require_once 'db_config.php';
// Auth check for student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// SQL query to get events that have already passed, ordered by the most recent first
$sql = "
    SELECT e.*, r.id AS registration_id
    FROM events e
    LEFT JOIN registrations r ON e.id = r.event_id AND r.user_id = ?
    WHERE e.event_datetime < NOW()
    ORDER BY e.event_datetime DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$events_result = $stmt->get_result();
?>

<?php include 'header_student.php'; ?>

<div class="container">
    <h2 class="page-title">Past Events Archive</h2>
    <p style="margin-bottom: 2rem; color: var(--text-secondary);">A look back at all the amazing events that have taken place on campus.</p>

    <?php if ($events_result->num_rows > 0): ?>
        <?php while($event = $events_result->fetch_assoc()): ?>
            <div class="card event-card" style="opacity: 0.85;">
                <div class="event-details">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p><strong>Held on:</strong> <?php echo date('M d, Y, h:i A', strtotime($event['event_datetime'])); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                    
                    <div class="btn-group" style="margin-top: 1rem;">
                        <?php if ($event['registration_id']): ?>
                            <span class="btn btn-success" style="cursor: default; background: var(--gradient-success);">
                                <i class="fas fa-check-circle" style="margin-right: 8px;"></i> You were registered
                            </span>
                        <?php else: ?>
                            <span class="btn btn-secondary" style="cursor: default;">You were not registered</span>
                        <?php endif; ?>

                        <?php if (!empty($event['poster'])): ?>
                             <a href="download-poster.php?id=<?php echo $event['id']; ?>" class="btn btn-info">View Poster</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($event['poster'])): ?>
                    <div class="event-poster-container" style="filter: grayscale(80%);">
                        <img src="data:<?php echo htmlspecialchars($event['poster_type']); ?>;base64,<?php echo base64_encode($event['poster']); ?>" alt="Event Poster">
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card" style="text-align:center;">
            <h3>No past events found.</h3>
            <p>Check back here after an event has finished!</p>
        </div>
    <?php endif; ?>
</div>
