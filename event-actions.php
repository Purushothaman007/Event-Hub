<?php
require_once 'db_config.php';
// Auth check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

$action = $_POST['action'] ?? $_GET['action'];

switch ($action) {
    case 'create':
        // Your existing create logic...
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $loc = $_POST['location'];
        $datetime = $_POST['event_datetime'];
        $creator_id = $_SESSION['user']['id'];
        
        $poster_content = null;
        $poster_type = null;
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
            $poster_content = file_get_contents($_FILES['poster']['tmp_name']);
            $poster_type = $_FILES['poster']['type'];
        }

        $stmt = $conn->prepare("INSERT INTO events (title, description, location, event_datetime, poster, poster_type, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $title, $desc, $loc, $datetime, $poster_content, $poster_type, $creator_id);
        $stmt->execute();
        header("Location: manage-events.php");
        break;

    case 'update':
        $id = $_POST['id'];
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $loc = $_POST['location'];
        $datetime = $_POST['event_datetime'];
        
        // Handle poster update only if a new one is uploaded
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
             $poster_content = file_get_contents($_FILES['poster']['tmp_name']);
             $poster_type = $_FILES['poster']['type'];
             $stmt = $conn->prepare("UPDATE events SET title=?, description=?, location=?, event_datetime=?, poster=?, poster_type=? WHERE id=?");
             $stmt->bind_param("ssssssi", $title, $desc, $loc, $datetime, $poster_content, $poster_type, $id);
        } else {
            // If no new poster, update everything else BUT the poster fields
            $stmt = $conn->prepare("UPDATE events SET title=?, description=?, location=?, event_datetime=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $desc, $loc, $datetime, $id);
        }
        $stmt->execute();
        header("Location: manage-events.php");
        break;

    case 'delete':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: manage-events.php");
        break;
}
?>