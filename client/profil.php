<?php
session_start();
$admin = false;
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// جلب البيانات الحالية
$stmt = $conn->prepare("SELECT nom, prenom, email, telephone FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// تعديل البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $telephone = $_POST['telephone'];
  
  if (!empty($_POST['motdepasse'])) {
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, telephone=?, motdepasse=? WHERE id=?");
    $stmt->bind_param("sssssi", $nom, $prenom, $email, $telephone, $motdepasse, $user_id);
  } else {
    $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, telephone=? WHERE id=?");
    $stmt->bind_param("ssssi", $nom, $prenom, $email, $telephone, $user_id);
  }

  if ($stmt->execute()) {
    header("Location: profil.php?modif=ok");
    exit;
  }
}
?>

<style>
  .profil-page {
    max-width: 600px;
    margin: auto;
    padding: 40px;
  }

  .profil-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  .profil-form input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }

  .profil-form .btn {
    background-color: #007BFF;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }
</style>

<section class="profil-page">
  <h2>👤 Mon Profil</h2>

  <?php if (isset($_GET['modif']) && $_GET['modif'] === 'ok'): ?>
    <p style="color: green;">✅ Modifications enregistrées avec succès.</p>
  <?php endif; ?>

  <form method="POST" class="profil-form">
    <label>Nom:</label>
    <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>

    <label>Prénom:</label>
    <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label>Téléphone:</label>
    <input type="text" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>" required>

    <label>Nouveau mot de passe (optionnel):</label>
    <input type="password" name="motdepasse">

    <button type="submit" class="btn">💾 Enregistrer</button>
  </form>
</section>

<?php include('../includes/footer.php'); ?>
