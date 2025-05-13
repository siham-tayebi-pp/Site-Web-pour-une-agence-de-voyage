<?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Vérifier si id existe dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<p>Voyage introuvable.</p>";
  include('includes/footer.php');
  exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM voyage WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
  $voyage = $result->fetch_assoc();
} else {
  echo "<p>Ce voyage n'existe pas.</p>";
  include('includes/footer.php');
  exit;
}
?>

<style>
  .btn {
    display: inline-block;
    background-color: #007BFF;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s ease;
  }

  .btn:hover {
    background-color: #0056b3;
  }
</style>


<section class="voyage-details">
  <h2><?php echo $voyage['titre']; ?></h2>

  <div class="details-container">
    <img src="images/<?php echo $voyage['image']; ?>" alt="<?php echo $voyage['destination']; ?>">

    <div class="infos">
      <p><strong>Destination :</strong> <?php echo $voyage['destination']; ?></p>
      <p><strong>Prix :</strong> <?php echo $voyage['prix']; ?> DH</p>
      <p><strong>Date de départ :</strong> <?php echo $voyage['date_depart']; ?></p>
      <p><strong>Date de retour :</strong> <?php echo $voyage['date_retour']; ?></p>
      <h3>Description :</h3>
      <p><?php echo $voyage['description']; ?></p>
      <!-- Bouton pour ouvrir le modal -->
<button class="btn-programme" onclick="document.getElementById('programmeModal').style.display='block'">🗓️ Voir le programme du jour</button>

<!-- Modal -->
<div id="programmeModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('programmeModal').style.display='none'">&times;</span>
    <h3>Programme du voyage</h3>
    <ul>
    <?php
    $stmt = $conn->prepare("SELECT * FROM programme_jour WHERE voyage_id = ? ORDER BY jour ASC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($p = $res->fetch_assoc()) {
      echo "<li><strong>Jour {$p['jour']}:</strong> " . htmlspecialchars($p['titre']);
      if (!empty($p['heure_debut']) && !empty($p['heure_fin'])) {
        echo " ({$p['heure_debut']} - {$p['heure_fin']})";
      }
      echo "<br><em>" . nl2br(htmlspecialchars($p['description'])) . "</em></li>";
    }
    ?>
    </ul>
  </div>
</div>


<?php if (isset($_SESSION['user_id'])): ?>
  <a href="reservation.php?id=<?php echo $voyage['id']; ?>" class="btn">Réserver maintenant</a>
<?php else: ?>
  <a href="login.php" class="btn" onclick="return confirm('Vous devez d\'abord vous connecter pour réserver.')">Réserver maintenant</a>
<?php endif; ?>

    </div>
  </div>
</section>

<?php if (isset($_SESSION['user_id'])): ?>
  <a href="client/ajouter_avis.php?voyage_id=<?php echo $voyage['id']; ?>" class="btn">📝 Laisser un avis</a>
<?php endif; ?>

<section style="max-width:700px;margin:auto;padding:20px;">
  <h3>🧑‍💬 Avis sur ce voyage</h3>
  <?php
  $avis_stmt = $conn->prepare("SELECT a.commentaire, a.note, a.date_publication, u.nom 
                               FROM avis a 
                               JOIN utilisateur u ON a.utilisateur_id = u.id 
                               WHERE a.voyage_id = ? 
                               ORDER BY a.date_publication DESC");
  $avis_stmt->bind_param("i", $id); // $id = id du voyage
  $avis_stmt->execute();
  $avis_result = $avis_stmt->get_result();

  while ($avis = $avis_result->fetch_assoc()):
  ?>
    <div style="border-bottom:1px solid #ccc;margin-bottom:10px;padding:10px 0;">
      <strong><?php echo htmlspecialchars($avis['nom']); ?></strong> - 
      <?php echo str_repeat("⭐", $avis['note']); ?>
      <br><small><?php echo $avis['date_publication']; ?></small>
      <p><?php echo nl2br(htmlspecialchars($avis['commentaire'])); ?></p>
    </div>
  <?php endwhile; ?>
</section>


<?php include('includes/footer.php'); ?>
