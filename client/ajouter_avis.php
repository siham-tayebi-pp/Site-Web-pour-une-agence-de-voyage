<?php
session_start();
$admin = false;
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['voyage_id'])) {
  header("Location: ../login.php");
  exit;
}

$voyage_id = intval($_GET['voyage_id']);
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $commentaire = $_POST['commentaire'];
  $note = intval($_POST['note']);

  $stmt = $conn->prepare("INSERT INTO avis (utilisateur_id, voyage_id, commentaire, note, date_publication) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("iisi", $user_id, $voyage_id, $commentaire, $note);
  if ($stmt->execute()) {
    echo "<p style='color:green;'>✅ Votre avis a été ajouté !</p>";
  }
}
?>

<form method="POST" style="max-width:500px;margin:auto;">
  <h3>📝 Donner votre avis</h3>
  <label>Note (1 à 5):</label>
  <select name="note" required>
    <option value="">-- Sélectionnez --</option>
    <option value="1">⭐</option>
    <option value="2">⭐⭐</option>
    <option value="3">⭐⭐⭐</option>
    <option value="4">⭐⭐⭐⭐</option>
    <option value="5">⭐⭐⭐⭐⭐</option>
  </select>

  <label>Commentaire:</label>
  <textarea name="commentaire" rows="5" required style="width:100%;padding:10px;"></textarea>
  <button type="submit" class="btn">Envoyer</button>
</form>

<?php include('../includes/footer.php'); ?>
