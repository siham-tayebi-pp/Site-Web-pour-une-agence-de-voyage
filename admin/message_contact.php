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
?>

<section class="admin-messages">
  <h2>Messages des Clients</h2>

  <table class="admin-table">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Sujet</th>
        <th>Message</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $res = $conn->query("SELECT * FROM message_contact ORDER BY date_envoi DESC");
      while ($row = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['nom']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['sujet']}</td>";
        echo "<td>" . nl2br(htmlspecialchars($row['contenu'])) . "</td>";
        echo "<td>{$row['date_envoi']}</td>";
        echo "<td>{$row['statut']}</td>";
        echo "<td><a href='repondre_message.php?id={$row['id']}' class='btn-repondre'>📩 Répondre</a></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</section>


