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
    
    // Message de succès
    $_SESSION['flash_message'] = [
      'type' => 'success',
      'message' => "Rôle de l'utilisateur #$id changé en $nouveau_role"
    ];
  }
  header("Location: utilisateurs.php");
  exit;
}

// Suppression utilisateur
if (isset($_GET['supprimer'])) {
  $id = intval($_GET['supprimer']);

  // Supprimer les notifications liées à cet utilisateur
  $stmt = $conn->prepare("DELETE FROM notification WHERE utilisateur_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Puis supprimer l'utilisateur
  $stmt = $conn->prepare("DELETE FROM utilisateur WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $_SESSION['flash_message'] = [
    'type' => 'success',
    'message' => "Utilisateur #$id supprimé avec succès"
  ];

  header("Location: utilisateurs.php");
  exit;
}


include('../includes/header.php');
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<style>
.admin-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.table-responsive {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 20px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge-admin {
    background-color: #6610f2;
}

.badge-client {
    background-color: #20c997;
}

.action-btns .btn {
    margin-right: 5px;
    margin-bottom: 5px;
}

.page-title {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
</style>

<div class="admin-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="fas fa-users-cog me-2"></i>Gestion des utilisateurs
        </h2>
        <div>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_message']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table id="usersTable" class="table table-hover table-striped">
            <thead class="table-light">
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
        while ($row = $res->fetch_assoc()):
        ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom'] . ' ' . htmlspecialchars($row['prenom'])) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <span
                            class="badge rounded-pill <?= $row['role'] === 'admin' ? 'badge-admin' : 'badge-client' ?>">
                            <?= $row['role'] ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($row['dateInscription'])) ?></td>
                    <td class="action-btns">
                        <button onclick="changeRole(<?= $row['id'] ?>)" class="btn btn-sm btn-outline-primary"
                            title="Changer le rôle">
                            <i class="fas fa-user-shield"></i>
                        </button>
                        <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-outline-danger"
                            title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->

<script>
// Initialisation DataTable
$(document).ready(function() {
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        },
        responsive: true,
        columnDefs: [{
                responsivePriority: 1,
                targets: 1
            }, // Nom complet
            {
                responsivePriority: 2,
                targets: -1
            }, // Actions
            {
                orderable: false,
                targets: -1
            } // Désactiver le tri sur la colonne Actions
        ]
    });
});

// Fonction pour changer le rôle
function changeRole(userId) {
    Swal.fire({
        title: 'Changer le rôle',
        text: "Êtes-vous sûr de vouloir modifier le rôle de cet utilisateur ?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, modifier',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `utilisateurs.php?changer_role=${userId}`;
        }
    });
}

// Fonction pour supprimer un utilisateur
function confirmDelete(userId) {
    Swal.fire({
        title: 'Supprimer l\'utilisateur',
        text: "Cette action est irréversible ! Confirmez-vous la suppression ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `utilisateurs.php?supprimer=${userId}`;
        }
    });
}

// Fermer automatiquement les alertes après 5 secondes
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>

<?php include('../includes/footer.php'); ?>