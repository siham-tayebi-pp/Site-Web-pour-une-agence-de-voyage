<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

// Traitement changement de rôle
if (isset($_GET['changer_role'])) {
  $id = intval($_GET['changer_role']);
  $getUser = $conn->prepare("SELECT role FROM utilisateur WHERE id = ?");
  $getUser->bind_param("i", $id);
  $getUser->execute();
  $res = $getUser->get_result();
  if ($row = $res->fetch_assoc()) {
    $nouveau_role = ($row['role'] === 'admin') ? 'client' : 'admin';
    $update = $conn->prepare("UPDATE utilisateur SET role = ? WHERE id = ?");
    $update->bind_param("si", $nouveau_role, $id);
    $update->execute();
  }
  header("Location: utilisateurs.php");
  exit;
}

// Suppression utilisateur
if (isset($_GET['supprimer'])) {
  $id = intval($_GET['supprimer']);
  $stmt = $conn->prepare("DELETE FROM utilisateur WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: utilisateurs.php");
  exit;
}

include('../includes/header.php');
?>

<section class="admin-users">
  <h2>Gestion des utilisateurs</h2>

  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom complet</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Date inscription</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
     $res = $conn->query("SELECT * FROM utilisateur ORDER BY dateInscription DESC");

      while ($row = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['nom']} {$row['prenom']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['role']}</td>";
        echo "<td>{$row['dateInscription']}</td>";
        echo "<td>
                <a href='utilisateurs.php?changer_role={$row['id']}' class='btn-role'>🔁 Rôle</a>
                <a href='utilisateurs.php?supprimer={$row['id']}' class='btn-delete' onclick=\"return confirm('Confirmer la suppression ?')\">🗑️ Supprimer</a>
              </td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</section>


