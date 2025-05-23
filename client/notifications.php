<?php
session_start();
$admin = false;
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des notifications non lues en premier
$stmt = $conn->prepare("SELECT * FROM notification 
                       WHERE utilisateur_id = ? 
                       ORDER BY lu ASC, date_envoi DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

// Marquer toutes les notifications comme lues
$update_stmt = $conn->prepare("UPDATE notification SET lu = 1 
                             WHERE utilisateur_id = ? AND lu = 0");
$update_stmt->bind_param("i", $user_id);
$update_stmt->execute();
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
.notifications-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 15px;
}

.notification-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 15px;
    transition: all 0.3s ease;
    border-left: 4px solid;
    overflow: hidden;
}

.notification-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.notification-unread {
    border-left-color: #3a7bd5;
    background-color: rgba(58, 123, 213, 0.05);
}

.notification-read {
    border-left-color: #6c757d;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: white;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.notification-body {
    padding: 15px 20px;
    background-color: white;
}

.notification-date {
    font-size: 0.8rem;
    color: #6c757d;
}

.notification-type {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    background-color: #3a7bd5;
}

.page-title {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f1f1f1;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

.mark-all-read {
    cursor: pointer;
    transition: color 0.2s;
}

.mark-all-read:hover {
    color: #3a7bd5;
}

@media (max-width: 576px) {
    .notification-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>

<div class="notifications-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="fas fa-bell me-2"></i>Mes Notifications
        </h2>
        <?php if ($res->num_rows > 0): ?>
        <span class="text-muted mark-all-read" id="markAllRead">
            <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
        </span>
        <?php endif; ?>
    </div>

    <?php if ($res->num_rows === 0): ?>
    <div class="empty-state animate__animated animate__fadeIn">
        <i class="far fa-bell-slash"></i>
        <h4>Aucune notification</h4>
        <p class="text-muted">Vous n'avez aucune notification pour le moment</p>
    </div>
    <?php else: ?>
    <div class="notification-list">
        <?php while ($n = $res->fetch_assoc()): ?>
        <div
            class="notification-card animate__animated animate__fadeIn <?= $n['lu'] ? 'notification-read' : 'notification-unread' ?>">
            <div class="notification-header">
                <span class="notification-type">
                    <?= htmlspecialchars($n['type']) ?>
                </span>
                <span class="notification-date">
                    <i class="far fa-clock me-1"></i>
                    <?= date('d/m/Y H:i', strtotime($n['date_creation'])) ?>
                </span>
            </div>
            <div class="notification-body">
                <?= nl2br(htmlspecialchars($n['contenu'])) ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marquer toutes les notifications comme lues
    document.getElementById('markAllRead')?.addEventListener('click', function() {
        fetch('mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: <?= $user_id ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'UI
                    document.querySelectorAll('.notification-card').forEach(card => {
                        card.classList.remove('notification-unread');
                        card.classList.add('notification-read');
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Notifications marquées comme lues',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
    });

    // Animation d'entrée progressive
    const notifications = document.querySelectorAll('.notification-card');
    notifications.forEach((notification, index) => {
        notification.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

<?php include('../includes/footer.php'); ?>