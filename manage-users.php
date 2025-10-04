<?php
require_once 'db_config.php';
// Auth check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle user deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id_to_delete = $_GET['id'];
    // Prevent admin from deleting themselves
    if ($user_id_to_delete != $_SESSION['user']['id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id_to_delete);
        $stmt->execute();
        header("Location: manage-users.php"); // Refresh the page
        exit();
    }
}

$users_result = $conn->query("SELECT id, name, email, role FROM users ORDER BY name");
?>

<?php include 'header_admin.php'; ?>
<style>
    .card {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-top: 2rem;
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
    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.9em;
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
    <h2>Manage Users</h2>

    <div class="card">
        <h3>Registered User Accounts</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $users_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                            <a href="manage-users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        <?php else: ?>
                            <span>(Current User)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin-dashboard.php" class="btn-secondary">Back to Dashboard</a>
    </div>
</div>