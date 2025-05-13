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

// نجيب جميع الإشعارات للمستخدم
$stmt = $conn->prepare("SELECT * FROM notification WHERE utilisateur_id = ? ORDER BY date_envoi DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

// نخلي جميع الإشعارات مقروءة بعد الدخول للصفحة
$conn->query("UPDATE notification SET lu = 'oui' WHERE utilisateur_id = $user_id");
?>

<style>
.notifications-container {
  max-width: 800px;
  margin: auto;
  padding: 30px;
}
.notification {
  border: 1px solid #ccc;
  padding: 15px;
  margin-bottom: 10px;
  border-left: 5px solid #007BFF;
  background-color: #f9f9f9;
}
.notification .date {
  font-size: 12px;
  color: #777;
}
</style>

<section class="notifications-container">
  <h2>🔔 Mes Notifications</h2>
  <?php if ($res->num_rows === 0): ?>
    <p>📭 Vous n'avez aucune notification.</p>
  <?php else: ?>
    <?php while ($n = $res->fetch_assoc()): ?>
      <div class="notification">
        <div class="date"><?php echo $n['date_envoi']; ?></div>
        <p><?php echo nl2br(htmlspecialchars($n['contenu'])); ?></p>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</section>

<?php include('../includes/footer.php'); ?>
