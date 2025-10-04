<?php
require_once 'db_config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user']['id'];
$sql = "SELECT e.*, r.id AS registration_id FROM events e LEFT JOIN registrations r ON e.id = r.event_id AND r.user_id = ? WHERE e.event_datetime >= NOW() ORDER BY e.event_datetime ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$events_result = $stmt->get_result();
?>
<?php include 'header_student.php'; ?>
<style>
    /* Styles are moved here from the previous step for completeness */
    .event-card {
        border: 1px solid #ddd; border-radius: 8px; margin-bottom: 25px; padding: 20px;
        background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex; align-items: flex-start; gap: 20px;
    }
    .event-details { flex: 1; }
    .event-poster-container { flex-shrink: 0; width: 250px; max-width: 40%; }
    .event-poster-container img { max-width: 100%; height: auto; border-radius: 5px; }
    .btn-group { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 15px; }
    .btn, .btn-success, .btn-info { text-decoration: none; color: white; padding: 8px 12px; border-radius: 5px; border: none; }
    .btn { background: #007bff; }
    .btn-success { background: #28a745; cursor: not-allowed; }
    .btn-info { background: #17a2b8; }
    @media (max-width: 768px) { .event-card { flex-direction: column; } /* More responsive styles */ }
</style>
<div class="container">
    <h2>Upcoming Events</h2>
    <?php while($event = $events_result->fetch_assoc()): ?>
        <div class="event-card">
            <div class="event-details">
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p><strong>Date:</strong> <?php echo date('M d, Y h:i A', strtotime($event['event_datetime'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                <p><?php echo htmlspecialchars($event['description']); ?></p>
                <div class="btn-group">
                    <?php if ($event['registration_id']): ?>
                        <button class="btn-success" disabled>Registered</button>
                    <?php else: ?>
                        <a href="registration-actions.php?action=register&event_id=<?php echo $event['id']; ?>" class="btn">Register</a>
                    <?php endif; ?>
                    <?php if (!empty($event['poster'])): ?>
                         <a href="download-poster.php?id=<?php echo $event['id']; ?>" class="btn-info">Download Poster</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($event['poster'])): ?>
                <div class="event-poster-container">
                    <img src="data:<?php echo htmlspecialchars($event['poster_type']); ?>;base64,<?php echo base64_encode($event['poster']); ?>" alt="Event Poster">
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>