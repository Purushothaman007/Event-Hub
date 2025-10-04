<?php
require_once 'db_config.php';
// Auth check and data fetching logic remains the same...
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user']['id'];
$sql = "SELECT e.* FROM events e JOIN registrations r ON e.id = r.event_id WHERE r.user_id = ? ORDER BY e.event_datetime ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_events_result = $stmt->get_result();
?>

<?php include 'header_student.php'; ?>

<div class="container">
    <h2 class="page-title">Your Registered Events</h2>
    
    <div id="myEventsList">
        <?php if ($my_events_result->num_rows === 0): ?>
            <div class="card" style="text-align:center;">
                <h3>No Registered Events</h3>
                <p>You haven't registered for any events yet.</p>
                <a href="student-dashboard.php" class="btn" style="margin-top: 1rem;">Browse All Events</a>
            </div>
        <?php else: ?>
            <?php while($event = $my_events_result->fetch_assoc()): ?>
                <div class="card event-card">
                    <div class="event-details">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p><strong>Date:</strong> <?php echo date('M d, Y h:i A', strtotime($event['event_datetime'])); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        
                        <div class="btn-group" style="margin-top: 1rem; display: flex; gap: 1rem; align-items: center;">
                            <?php if (strtotime($event['event_datetime']) > time()): ?>
                                <button class="btn btn-danger unregister-btn" 
                                        data-event-title="<?php echo htmlspecialchars($event['title']); ?>"
                                        data-unregister-url="registration-actions.php?action=unregister&event_id=<?php echo $event['id']; ?>">
                                    Unregister
                                </button>
                            <?php else: ?>
                                <span>(Event has passed)</span>
                            <?php endif; ?>
                            <?php if (!empty($event['poster'])): ?>
                                 <a href="download-poster.php?id=<?php echo $event['id']; ?>" class="btn btn-info">Download Poster</a>
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
        <?php endif; ?>
    </div>
</div>

<div class="modal-overlay" id="confirmationModalOverlay">
    <div class="modal">
        <h2 id="modalTitle">Confirm Action</h2>
        <p id="modalText">Are you sure you want to proceed?</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" id="cancelActionBtn">Cancel</button>
            <a href="#" id="confirmActionBtn" class="btn btn-danger">Confirm</a>
        </div>
    </div>
</div>

<script>
    // Get all the elements needed for the modal
    const modalOverlay = document.getElementById('confirmationModalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const cancelBtn = document.getElementById('cancelActionBtn');
    const confirmBtn = document.getElementById('confirmActionBtn');
    const unregisterButtons = document.querySelectorAll('.unregister-btn');

    // Add a click event to all "Unregister" buttons
    unregisterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the specific event title and unregister URL from the button
            const eventTitle = this.getAttribute('data-event-title');
            const unregisterUrl = this.getAttribute('data-unregister-url');

            // Update the modal with the specific information
            modalTitle.textContent = 'Confirm Unregistration';
            modalText.textContent = `Are you sure you want to unregister from "${eventTitle}"?`;
            confirmBtn.setAttribute('href', unregisterUrl);

            // Show the modal
            modalOverlay.classList.add('active');
        });
    });

    // Function to hide the modal
    function hideModal() {
        modalOverlay.classList.remove('active');
    }

    // Add event listeners to close the modal
    cancelBtn.addEventListener('click', hideModal);
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });
</script>