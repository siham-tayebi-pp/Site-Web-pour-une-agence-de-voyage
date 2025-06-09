<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

// Traitement suppression de message
if (isset($_GET['supprimer'])) {
  $id = intval($_GET['supprimer']);
  $stmt = $conn->prepare("DELETE FROM message_contact WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $_SESSION['flash_message'] = [
    'type' => 'success',
    'message' => "Message #$id supprimé avec succès"
  ];
  
  header("Location: message_contact.php");
  exit;
}

// Traitement changement de statut
if (isset($_GET['changer_statut'])) {
  $id = intval($_GET['changer_statut']);
  $update = $conn->prepare("UPDATE message_contact SET statut = IF(statut = 'non lu', 'lu', 'non lu') WHERE id = ?");
  $update->bind_param("i", $id);
  $update->execute();
  
  $_SESSION['flash_message'] = [
    'type' => 'success',
    'message' => "Statut du message #$id mis à jour"
  ];
  
  header("Location: message_contact.php");
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
    max-width: 1800px;
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

.badge-non-lu {
    background-color: #dc3545;
}

.badge-lu {
    background-color: #28a745;
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

.message-content {
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.message-content:hover {
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
    position: absolute;
    z-index: 100;
    background: white;
    padding: 10px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    max-width: 500px;
}
</style>

<div class="admin-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="fas fa-envelope me-2"></i>Messages des clients
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
        <table id="messagesTable" class="table table-hover table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM message_contact ORDER BY date_envoi DESC");
                while ($row = $res->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['sujet']) ?></td>
                    <td class="message-content" title="<?= htmlspecialchars($row['contenu']) ?>">
                        <?= nl2br(htmlspecialchars($row['contenu'])) ?>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($row['date_envoi'])) ?></td>
                    <td>
                        <span
                            class="badge rounded-pill <?= $row['statut'] === 'non lu' ? 'badge-non-lu' : 'badge-lu' ?>">
                            <?= $row['statut'] ?>
                        </span>
                    </td>
                    <td class="action-btns">
                        <a href="repondre_message.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success"
                            title="Répondre">
                            <i class="fas fa-reply"></i>
                        </a>
                        <button onclick="changeStatus(<?= $row['id'] ?>)" class="btn btn-sm btn-outline-primary"
                            title="Changer statut">
                            <i class="fas fa-eye<?= $row['statut'] === 'non lu' ? '' : '-slash' ?>"></i>
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

<!-- Bootstrap JS Bundle with Popper <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS 
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->


<script>
// Initialisation DataTable
$(document).ready(function() {
    $('#messagesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        },
        responsive: true,
        columnDefs: [{
                responsivePriority: 1,
                targets: 1
            }, // Nom
            {
                responsivePriority: 2,
                targets: 2
            }, // Email
            {
                responsivePriority: 3,
                targets: -1
            }, // Actions
            {
                orderable: false,
                targets: -1
            }, // Désactiver le tri sur Actions
            {
                width: "300px",
                targets: 4
            } // Largeur fixe pour la colonne Message
        ],
        order: [
            [0, 'desc']
        ] // Tri par ID décroissant par défaut
    });
});

// Fonction pour supprimer un message
function confirmDelete(messageId) {
    Swal.fire({
        title: 'Supprimer le message',
        text: "Cette action est irréversible ! Confirmez-vous la suppression ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `message_contact.php?supprimer=${messageId}`;
        }
    });
}

// Fonction pour changer le statut
function changeStatus(messageId) {
    window.location.href = `message_contact.php?changer_statut=${messageId}`;
}

// Fermer automatiquement les alertes après 5 secondes
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>

<?php include('../includes/footer.php'); ?>