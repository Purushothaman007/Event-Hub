<?php
require_once 'db_config.php';
require_once 'mailer_config.php'; // For sending emails

if (!isset($_SESSION['user'])) {
    die("Access denied.");
}

$action = $_GET['action'];
$event_id = $_GET['event_id'];
$user_id = $_SESSION['user']['id'];
$user_email = $_SESSION['user']['email'];
$user_name = $_SESSION['user']['name'];

switch ($action) {
    case 'register':
        // Get event title for the email
        $event_stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
        $event_stmt->bind_param("i", $event_id);
        $event_stmt->execute();
        $event = $event_stmt->get_result()->fetch_assoc();
        
        if ($event) {
            $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $event_id);
            if ($stmt->execute()) {
                // Send confirmation email
                sendRegistrationEmail($user_email, $user_name, $event['title']);
            }
        }
        header("Location: student-dashboard.php");
        break;

    case 'unregister':
        $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id=? AND event_id=?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        header("Location: my-events.php");
        break;
}
?>