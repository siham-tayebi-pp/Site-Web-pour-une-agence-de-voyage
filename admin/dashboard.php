<?php

session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

// تحقق من الدور
if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

// الإحصائيات
$nbUsers = $conn->query("SELECT COUNT(*) AS total FROM utilisateur")->fetch_assoc()['total'];
$nbVoyages = $conn->query("SELECT COUNT(*) AS total FROM voyage")->fetch_assoc()['total'];
$nbReservations = $conn->query("SELECT COUNT(*) AS total FROM reservation")->fetch_assoc()['total'];

include('../includes/header.php');
?>

<section class="dashboard-admin-modern">
    <h2>Bienvenue Admin, <?php echo $_SESSION['user_nom']; ?> 👋</h2>

    <div class="dashboard-grid">
        <a href="reservations.php" class="dashboard-box box1">
            <i class="fas fa-clipboard-list"></i>
            <span>Réservations (<?php echo $nbReservations; ?>)</span>
        </a>
        <a href="voyages.php" class="dashboard-box box2">
            <i class="fas fa-globe"></i>
            <span>Voyages (<?php echo $nbVoyages; ?>)</span>
        </a>
        <a href="utilisateurs.php" class="dashboard-box box3">
            <i class="fas fa-users"></i>
            <span>Utilisateurs (<?php echo $nbUsers; ?>)</span>
        </a>
        <a href="avis.php" class="dashboard-box box4">
            <i class="fas fa-star"></i>
            <span>Avis</span>
        </a>
        <a href="programmes_du_jour.php" class="dashboard-box box5">
            <i class="fas fa-calendar-alt"></i>
            <span>Programmes</span>
        </a>
        <a href="rapports.php" class="dashboard-box box6">
            <i class="fas fa-chart-line"></i>
            <span>Rapports</span>
        </a>
        <a href="message_contact.php" class="dashboard-box box7">
            <i class="fas fa-envelope"></i>
            <span>Messages</span>
        </a>
        <a href="<?php echo $prefix; ?>logout.php" class="dashboard-box box8">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</section>