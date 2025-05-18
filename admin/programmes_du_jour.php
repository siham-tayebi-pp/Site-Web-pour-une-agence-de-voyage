<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

$voyages = $conn->query("SELECT id, titre FROM voyage");

$selected_voyage = isset($_GET['voyage_id']) ? intval($_GET['voyage_id']) : 0;

if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM programme_jour WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: programmes_du_jour.php?voyage_id=$selected_voyage");
  exit;
}

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

<section class="admin-programme container mt-5">
    <h2 class="mb-4">📅 Gestion des Programmes par jour</h2>

    <form method="get" class="mb-4">
        <label class="form-label">Choisir un voyage :</label>
        <select name="voyage_id" class="form-select w-50" onchange="this.form.submit()">
            <option value="">-- Sélectionner --</option>
            <?php while ($v = $voyages->fetch_assoc()): ?>
            <option value="<?php echo $v['id']; ?>" <?php if ($selected_voyage == $v['id']) echo 'selected'; ?>>
                <?php echo $v['titre']; ?>
            </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($selected_voyage): ?>
    <h3 class="mb-3">Ajouter un jour</h3>
    <form method="post" class="mb-5">
        <input type="hidden" class="form-control w-100" name="voyage_id" value="<?php echo $selected_voyage; ?>">

        <div class="mb-3">
            <label class="form-label">Jour :</label>
            <input type="number" name="jour" class="form-control w-100" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Titre :</label>
            <input type="text" name="titre" class="form-control w-100" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description :</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Heure de début :</label>
            <input type="time" name="heure_debut" class="form-control w-100">
        </div>

        <div class="mb-3">
            <label class="form-label">Heure de fin :</label>
            <input type="time" name="heure_fin" class="form-control w-100">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>

    <h3 class="mb-3">Liste des jours programmés</h3>
    <table class="table table-striped table-bordered">
        <thead class="table-light">
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
                <td>
                    <a href="?voyage_id=<?php echo $selected_voyage; ?>&delete=<?php echo $row['id']; ?>"
                        class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce jour ?')">Supprimer</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>