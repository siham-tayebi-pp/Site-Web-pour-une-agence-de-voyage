<?php
session_start();
include('includes/db.php');
include('includes/auth.php');
include('includes/header.php');

$voyage_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$erreur = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom_passager = $_POST['nom_passager'];
  $telephone = $_POST['telephone'];
  $nombre_personnes = $_POST['nombre_personnes'];
  $options = $_POST['options'];
  $client_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO reservation (voyage_id, client_id, nom_passager, telephone, nombre_personnes, options) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iissis", $voyage_id, $client_id, $nom_passager, $telephone, $nombre_personnes, $options);

  if ($stmt->execute()) {
    $success = "Réservation effectuée avec succès.";
  } else {
    $erreur = "Erreur lors de la réservation.";
  }
}

// Get voyage data
$stmt = $conn->prepare("SELECT * FROM voyage WHERE id = ?");
$stmt->bind_param("i", $voyage_id);
$stmt->execute();
$result = $stmt->get_result();
$voyage = $result->fetch_assoc();
?>

<section class="reservation-page">
  <h2>Réserver le voyage : <?php echo $voyage['titre']; ?></h2>

  <?php if ($erreur): ?>
    <p style="color:red;"><?php echo $erreur; ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p style="color:green;"><?php echo $success; ?></p>
  <?php endif; ?>

  <form action="" method="post" class="reservation-form">
    <label>Nom complet :</label>
    <input type="text" name="nom_passager" required>

    <label>Téléphone :</label>
    <input type="text" name="telephone" required>

    <label>Nombre de personnes :</label>
    <input type="number" name="nombre_personnes" min="1" required>

    <label>Options supplémentaires :</label>
    <textarea name="options" rows="3" placeholder="Ex : repas, guide..."></textarea>

    <button type="submit" class="btn">Confirmer la réservation</button>
  </form>
</section>

<?php include('includes/footer.php'); ?>
