<?php
require_once 'db_config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-row { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        input, textarea { width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px; }
        .preview-container { margin-top: 1rem; text-align: center; }
        .poster-preview { max-width: 300px; max-height: 200px; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-top: 0.5rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 1.5rem; border-radius: 5px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; }
        .btn-secondary { background: #6c757d; }
    </style>
</head>
<body>
    <?php include 'header_admin.php'; ?>
    <div class="container">
        <h2>Create New Event</h2>
        <div class="card">
            <form action="event-actions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="form-row">
                    <label for="title">Event title</label>
                    <input id="title" name="title" type="text" placeholder="Annual Tech Fest 2024" required />
                </div>
                <div class="form-row">
                    <label for="event_datetime">Date and Time</label>
                    <input id="event_datetime" name="event_datetime" type="datetime-local" required />
                </div>
                <div class="form-row">
                    <label for="location">Location</label>
                    <input id="location" name="location" type="text" placeholder="Auditorium" />
                </div>
                <div class="form-row">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief description..."></textarea>
                </div>
                <div class="form-row">
                    <label for="poster">Event Poster (Optional)</label>
                    <input type="file" id="poster" name="poster" accept="image/*" />
                    <div class="preview-container">
                        <img id="posterPreview" class="poster-preview" style="display: none;" />
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <button class="btn" type="submit">Create Event</button>
                    <a href="admin-dashboard.php" class="btn btn-secondary" style="margin-left:10px;">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Poster preview script
        document.getElementById('poster').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('posterPreview');
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>