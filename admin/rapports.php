<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

include('../includes/header.php');

// إحصائيات سريعة
$nbUsers = $conn->query("SELECT COUNT(*) AS total FROM utilisateur")->fetch_assoc()['total'];
$nbVoyages = $conn->query("SELECT COUNT(*) AS total FROM voyage")->fetch_assoc()['total'];
$nbReservations = $conn->query("SELECT COUNT(*) AS total FROM reservation")->fetch_assoc()['total'];
$nbMessages = $conn->query("SELECT COUNT(*) AS total FROM message_contact")->fetch_assoc()['total'];
$nbAvis = $conn->query("SELECT COUNT(*) AS total FROM avis")->fetch_assoc()['total'];
?>

<section class="admin-rapport">
  <h2>📊 Rapport d'activité</h2>

  <div class="rapport-stats">
    <div class="rapport-card"><strong>👥 Utilisateurs :</strong> <?php echo $nbUsers; ?></div>
    <div class="rapport-card"><strong>🌍 Voyages :</strong> <?php echo $nbVoyages; ?></div>
    <div class="rapport-card"><strong>📋 Réservations :</strong> <?php echo $nbReservations; ?></div>
    <div class="rapport-card"><strong>📨 Messages reçus :</strong> <?php echo $nbMessages; ?></div>
    <div class="rapport-card"><strong>⭐ Avis reçus :</strong> <?php echo $nbAvis; ?></div>
  </div>

  <div style="text-align:center; margin-top:30px;">
    <a href="export_rapport.php" class="btn-export">📄 Télécharger le rapport PDF</a>
  </div>
</section>


