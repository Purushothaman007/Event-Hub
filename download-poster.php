<?php
require_once 'db_config.php';

// Auth check - ensure user is logged in to download
if (!isset($_SESSION['user'])) {
    die("Access Denied: You must be logged in to download posters.");
}

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT title, poster, poster_type FROM events WHERE id = ? AND poster IS NOT NULL");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        
        // Sanitize filename
        $filename = preg_replace('/[^a-zA-Z0-9_.]/', '_', $event['title']) . '_poster.jpg';

        // Set headers to trigger a download
        header("Content-Type: " . $event['poster_type']);
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        
        // Output the raw image data
        echo $event['poster'];
    } else {
        die("Poster not found.");
    }
} else {
    die("Invalid request.");
}
?>