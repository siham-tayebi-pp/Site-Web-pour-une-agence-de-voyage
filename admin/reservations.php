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
// نتحقق واش كاين شي réservation en attente
$hasPending = false;
$res2 = $conn->query("SELECT id FROM reservation WHERE statut = 'en attente'");
if ($res2->num_rows > 0) {
  $hasPending = true;
}

if (isset($_GET['action'], $_GET['id'], $_GET['user'], $_GET['voyage'])) {
  $id = intval($_GET['id']);
  $userId = intval($_GET['user']);
  $voyageTitre = $_GET['voyage'];
  $action = $_GET['action'];

  if ($action === 'confirmer') {
    $conn->query("UPDATE reservation SET statut = 'confirmé' WHERE id = $id");

    $contenu = "🎉 Votre réservation pour le voyage '$voyageTitre' a été confirmée.";
    $stmt = $conn->prepare("INSERT INTO notification (utilisateur_id, type, contenu) VALUES (?, 'confirmation', ?)");
    $stmt->bind_param("is", $userId, $contenu);
    $stmt->execute();

  } elseif ($action === 'refuser') {
    $conn->query("UPDATE reservation SET statut = 'refusé' WHERE id = $id");

    $contenu = "❌ Votre réservation pour le voyage '$voyageTitre' a été refusée.";
    $stmt = $conn->prepare("INSERT INTO notification (utilisateur_id, type, contenu) VALUES (?, 'refus', ?)");
    $stmt->bind_param("is", $userId, $contenu);
    $stmt->execute();
  }

  header("Location: reservations.php");
  exit;
}

?>

<section class="admin-reservations">
  <h2>Liste des Réservations</h2>

  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom du passager</th>
        <th>Téléphone</th>
        <th>Voyage</th>
        <th>Nombre de personnes</th>
        <th>Date</th>
        <th>Statut</th>
        <?php if ($hasPending): ?>
      <th>Action</th>
    <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT r.*, v.titre FROM reservation r 
      JOIN voyage v ON r.voyage_id = v.id 
      ORDER BY r.id DESC";

      $res = $conn->query($sql);
      while ($row = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['nom_passager']}</td>";
        echo "<td>{$row['telephone']}</td>";
        echo "<td>{$row['titre']}</td>";
        echo "<td>{$row['nombre_personnes']}</td>";
        echo "<td>{$row['date_reservation']}</td>";
      
        // زر التأكيد والرفض
        echo "<td>";
        if ($hasPending) {
          echo "<td>";
          if ($row['statut'] === 'en attente') {
            echo "<a href='reservations.php?action=confirmer&id={$row['id']}&user={$row['client_id']}&voyage=" . urlencode($row['titre']) . "' class='btn'>✔️ Confirmer</a> ";
            echo "<a href='reservations.php?action=refuser&id={$row['id']}&user={$row['client_id']}&voyage=" . urlencode($row['titre']) . "' class='btn' style='background:red;'>❌ Refuser</a>";
          }
          echo "</td>";
        }
         else {
          echo ucfirst($row['statut']);
        }
        echo "</td>";
      
        echo "</tr>";
      }
      
      ?>
    </tbody>
  </table>
</section>


