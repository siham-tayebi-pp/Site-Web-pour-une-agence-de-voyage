<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

// التأكد أن المستخدم هو admin
if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = intval($_GET['id']);

  // حذف الصورة إذا كانت موجودة
  $stmtImg = $conn->prepare("SELECT image FROM voyage WHERE id = ?");
  $stmtImg->bind_param("i", $id);
  $stmtImg->execute();
  $res = $stmtImg->get_result();
  if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $image = $row['image'];
    if (!empty($image) && file_exists("../images/" . $image)) {
      unlink("../images/" . $image);
    }
  }

  // Supprimer les réservations liées
  $stmt = $conn->prepare("DELETE FROM reservation WHERE voyage_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Supprimer les avis liés
  $stmt = $conn->prepare("DELETE FROM avis WHERE voyage_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Supprimer le voyage
  $stmt = $conn->prepare("DELETE FROM voyage WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: voyages.php");
exit;