<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

// جلب لائحة الرحلات
$voyages = $conn->query("SELECT id, titre FROM voyage");

// عند اختيار رحلة
$selected_voyage = isset($_GET['voyage_id']) ? intval($_GET['voyage_id']) : 0;

// حذف يوم
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM programme_jour WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: programmes_du_jour.php?voyage_id=$selected_voyage");
  exit;
}

// إضافة يوم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $voyage_id = intval($_POST['voyage_id']);
  $jour = intval($_POST['jour']);
  $titre = $_POST['titre'];
  $description = $_POST['description'];
  $heure_debut = $_POST['heure_debut'];
  $heure_fin = $_POST['heure_fin'];

  $stmt = $conn->prepare("INSERT INTO programme_jour (voyage_id, jour, titre, description, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iissss", $voyage_id, $jour, $titre, $description, $heure_debut, $heure_fin);
  $stmt->execute();
  header("Location: programmes_du_jour.php?voyage_id=$voyage_id");
  exit;
}

include('../includes/header.php');
?>

<section class="admin-programme">
  <h2>📅 Gestion des Programmes par jour</h2>

  <form method="get">
    <label>Choisir un voyage :</label>
    <select name="voyage_id" onchange="this.form.submit()">
      <option value="">-- Sélectionner --</option>
      <?php while ($v = $voyages->fetch_assoc()): ?>
        <option value="<?php echo $v['id']; ?>" <?php if ($selected_voyage == $v['id']) echo 'selected'; ?>>
          <?php echo $v['titre']; ?>
        </option>
      <?php endwhile; ?>
    </select>
  </form>

  <?php if ($selected_voyage): ?>
    <h3>Ajouter un jour</h3>
    <form method="post" class="admin-form">
      <input type="hidden" name="voyage_id" value="<?php echo $selected_voyage; ?>">
      <label>Jour :</label>
      <input type="number" name="jour" required>
      <label>Titre :</label>
      <input type="text" name="titre" required>
      <label>Description :</label>
      <textarea name="description" required></textarea>
      <label>Heure de début :</label>
      <input type="time" name="heure_debut">
      <label>Heure de fin :</label>
      <input type="time" name="heure_fin">
      <button type="submit" class="btn">Ajouter</button>
    </form>

    <h3>Liste des jours programmés</h3>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Jour</th>
          <th>Titre</th>
          <th>Description</th>
          <th>Heure</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $conn->prepare("SELECT * FROM programme_jour WHERE voyage_id = ? ORDER BY jour ASC");
        $stmt->bind_param("i", $selected_voyage);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()):
        ?>
          <tr>
            <td>Jour <?php echo $row['jour']; ?></td>
            <td><?php echo htmlspecialchars($row['titre']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo $row['heure_debut'] . ' - ' . $row['heure_fin']; ?></td>
            <td><a href="?voyage_id=<?php echo $selected_voyage; ?>&delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ce jour ?')">Supprimer</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>


