<?php
require_once 'db_config.php';
// Auth check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get total users
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Get total events
$total_events = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];

// Get event registration counts
$events_report = $conn->query("
    SELECT e.title, e.event_datetime, e.location, COUNT(r.id) as registration_count
    FROM events e
    LEFT JOIN registrations r ON e.id = r.event_id
    GROUP BY e.id
    ORDER BY e.event_datetime DESC
");
?>

<?php include 'header_admin.php'; ?>
<style>
    .card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    .stat-card {
        text-align: center;
    }
    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        color: #667eea;
        margin: 1rem 0;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    .table th, .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .btn-secondary {
        display: inline-block;
        margin-top: 1.5rem;
        background: #6c757d;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
    }
</style>

<div class="container">
    <h2>Reports & Analytics</h2>

    <div class="grid-2">
        <div class="card stat-card">
            <h3>Total Users</h3>
            <div class="stat-number"><?php echo $total_users; ?></div>
        </div>
        <div class="card stat-card">
            <h3>Total Events</h3>
            <div class="stat-number"><?php echo $total_events; ?></div>
        </div>
    </div>

    <div class="card">
        <h3>Event Registration Report</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date & Time</th>
                    <th>Registrations</th>
                </tr>
            </thead>
            <tbody>
                <?php while($event = $events_report->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo date('M d, Y h:i A', strtotime($event['event_datetime'])); ?></td>
                    <td style="text-align: center;"><?php echo $event['registration_count']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin-dashboard.php" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>