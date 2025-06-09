<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

$voyages = $conn->query("SELECT id, titre FROM voyage ORDER BY titre ASC");

$selected_voyage = isset($_GET['voyage_id']) ? intval($_GET['voyage_id']) : 0;

if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM programme_jour WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $_SESSION['flash_message'] = [
    'type' => 'success',
    'message' => 'Le jour a été supprimé avec succès'
  ];
  
  header("Location: programmes_du_jour.php?voyage_id=$selected_voyage");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $voyage_id = intval($_POST['voyage_id']);
  $jour = intval($_POST['jour']);
  $titre = htmlspecialchars(trim($_POST['titre']));
  $description = htmlspecialchars(trim($_POST['description']));
  $heure_debut = $_POST['heure_debut'];
  $heure_fin = $_POST['heure_fin'];

  $stmt = $conn->prepare("INSERT INTO programme_jour (voyage_id, jour, titre, description, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iissss", $voyage_id, $jour, $titre, $description, $heure_debut, $heure_fin);
  $stmt->execute();
  
  $_SESSION['flash_message'] = [
    'type' => 'success',
    'message' => 'Le nouveau jour a été ajouté avec succès'
  ];
  
  header("Location: programmes_du_jour.php?voyage_id=$voyage_id");
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
<!-- Flatpickr pour les heures -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.programme-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 25px;
    margin-bottom: 30px;
}

.page-title {
    color: #2c3e50;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f1f1f1;
}

.voyage-selector {
    max-width: 500px;
}

.form-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.time-input-group {
    display: flex;
    gap: 15px;
}

.time-input-group .form-control {
    flex: 1;
}

.action-btns .btn {
    margin-right: 5px;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

@media (max-width: 768px) {
    .time-input-group {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<div class="admin-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="fas fa-calendar-alt me-2"></i>Gestion des Programmes par jour
        </h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_message']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <div class="programme-card">
        <form method="get" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Sélectionnez un voyage :</label>
                    <select name="voyage_id" class="form-select voyage-selector" onchange="this.form.submit()">
                        <option value="">-- Choisir un voyage --</option>
                        <?php while ($v = $voyages->fetch_assoc()): ?>
                        <option value="<?= $v['id'] ?>" <?= $selected_voyage == $v['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['titre']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <?php if ($selected_voyage): ?>
                    <a href="../details.php?id=<?= $selected_voyage ?>" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>Voir le voyage
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <?php if ($selected_voyage): ?>
        <div class="form-section">
            <h4 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Ajouter un jour</h4>
            <form method="post" id="programmeForm">
                <input type="hidden" name="voyage_id" value="<?= $selected_voyage ?>">

                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="jour" class="form-label">Jour *</label>
                        <input type="number" id="jour" name="jour" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-10">
                        <label for="titre" class="form-label">Titre *</label>
                        <input type="text" id="titre" name="titre" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Horaires</label>
                        <div class="time-input-group">
                            <input type="text" name="heure_debut" class="form-control timepicker" placeholder="Début">
                            <input type="text" name="heure_fin" class="form-control timepicker" placeholder="Fin">
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-5">
            <h4 class="mb-4"><i class="fas fa-list me-2"></i>Programme existant</h4>
            <div class="table-responsive">
                <table id="programmesTable" class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Jour</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Horaires</th>
                            <th>Actions</th>
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
                            <td>Jour <?= $row['jour'] ?></td>
                            <td><?= htmlspecialchars($row['titre']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                            <td>
                                <?php if ($row['heure_debut'] || $row['heure_fin']): ?>
                                <?= $row['heure_debut'] ? htmlspecialchars($row['heure_debut']) : '--' ?>
                                à
                                <?= $row['heure_fin'] ? htmlspecialchars($row['heure_fin']) : '--' ?>
                                <?php else: ?>
                                Non spécifié
                                <?php endif; ?>
                            </td>
                            <td class="action-btns">
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
        <?php else: ?>
        <div class="alert alert-info text-center py-4">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <h5>Veuillez sélectionner un voyage pour gérer son programme</h5>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialisation DataTable
$(document).ready(function() {
    $('#programmesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        },
        responsive: true,
        columnDefs: [{
                responsivePriority: 1,
                targets: 1
            }, // Titre
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

    // Initialisation des timepickers
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        locale: "fr"
    });
});

// Confirmation de suppression
function confirmDelete(programmeId) {
    Swal.fire({
        title: 'Confirmer la suppression',
        text: "Êtes-vous sûr de vouloir supprimer ce jour du programme ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                `programmes_du_jour.php?voyage_id=<?= $selected_voyage ?>&delete=${programmeId}`;
        }
    });
}

// Validation du formulaire
document.getElementById('programmeForm').addEventListener('submit', function(e) {
    const jour = document.getElementById('jour');
    const titre = document.getElementById('titre');
    const description = document.getElementById('description');
    let isValid = true;

    if (!jour.value || jour.value < 1) {
        jour.classList.add('is-invalid');
        isValid = false;
    }

    if (!titre.value.trim()) {
        titre.classList.add('is-invalid');
        isValid = false;
    }

    if (!description.value.trim()) {
        description.classList.add('is-invalid');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();

        // Animation pour indiquer les erreurs
        const invalidFields = document.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.classList.add('animate__animated', 'animate__headShake');
            setTimeout(() => {
                field.classList.remove('animate__animated', 'animate__headShake');
            }, 1000);
        });

        // Scroll vers la première erreur
        if (invalidFields.length > 0) {
            invalidFields[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }
});

// Retirer la classe is-invalid lors de la saisie
document.querySelectorAll('#programmeForm input, #programmeForm textarea').forEach(element => {
    element.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
});

// Fermer automatiquement les alertes après 5 secondes
setTimeout(function() {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.transition = 'opacity 0.5s ease-out';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);
</script>

<?php include('../includes/footer.php'); ?>