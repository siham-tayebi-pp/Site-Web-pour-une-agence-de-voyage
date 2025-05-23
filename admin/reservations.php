<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

include('../includes/header.php');

// Vérification des réservations en attente
$hasPending = false;
$res2 = $conn->query("SELECT id FROM reservation WHERE statut = 'en attente'");
if ($res2->num_rows > 0) {
  $hasPending = true;
}

if (isset($_GET['action'], $_GET['id'], $_GET['user'], $_GET['voyage'])) {
  $id = intval($_GET['id']);
  $userId = intval($_GET['user']);
  $voyageTitre = $_GET['voyage'];
  $action = $_GET['action'];

  if ($action === 'confirmer') {
    $conn->query("UPDATE reservation SET statut = 'confirmé' WHERE id = $id");

    $contenu = "🎉 Votre réservation pour le voyage '$voyageTitre' a été confirmée.";
    $stmt = $conn->prepare("INSERT INTO notification (utilisateur_id, type, contenu) VALUES (?, 'confirmation', ?)");
    $stmt->bind_param("is", $userId, $contenu);
    $stmt->execute();

  } elseif ($action === 'refuser') {
    $conn->query("UPDATE reservation SET statut = 'refusé' WHERE id = $id");

    $contenu = "❌ Votre réservation pour le voyage '$voyageTitre' a été refusée.";
    $stmt = $conn->prepare("INSERT INTO notification (utilisateur_id, type, contenu) VALUES (?, 'refus', ?)");
    $stmt->bind_param("is", $userId, $contenu);
    $stmt->execute();
  }

  header("Location: reservations.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .status-pending {
        color: #ffc107;
        font-weight: bold;
    }

    .status-confirmed {
        color: #28a745;
        font-weight: bold;
    }

    .status-refused {
        color: #dc3545;
        font-weight: bold;
    }

    .action-buttons .btn {
        margin-right: 5px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .page-title {
        margin-bottom: 20px;
    }

    .search-container {
        max-width: 500px;
        margin: 0 auto 20px;
    }

    .search-box {
        border-radius: 20px;
        padding: 10px 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card2 {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="page-title text-center mb-4">Gestion des Réservations</h2>

                <!-- Nouveau conteneur de recherche centré -->
                <div class="search-container">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control search-box"
                            placeholder="Rechercher une réservation...">
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>

                <div class="card2 ">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mx-4">Liste des Réservations</h5>
                    </div>
                    <div class="card-body shadow-sm px-2 ">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom du passager</th>
                                        <th>Téléphone</th>
                                        <th>Voyage</th>
                                        <th>Nombre de personnes</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <?php if ($hasPending): ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT r.*, v.titre FROM reservation r 
                                            JOIN voyage v ON r.voyage_id = v.id 
                                            ORDER BY r.id DESC";
                                    $res = $conn->query($sql);
                                    
                                    while ($row = $res->fetch_assoc()) {
                                        $statusClass = '';
                                        if ($row['statut'] === 'en attente') $statusClass = 'status-pending';
                                        elseif ($row['statut'] === 'confirmé') $statusClass = 'status-confirmed';
                                        elseif ($row['statut'] === 'refusé') $statusClass = 'status-refused';
                                        
                                        echo "<tr>";
                                        echo "<td>{$row['id']}</td>";
                                        echo "<td>{$row['nom_passager']}</td>";
                                        echo "<td>{$row['telephone']}</td>";
                                        echo "<td>{$row['titre']}</td>";
                                        echo "<td>{$row['nombre_personnes']}</td>";
                                        echo "<td>{$row['date_reservation']}</td>";
                                        echo "<td class='$statusClass'>" . ucfirst($row['statut']) . "</td>";
                                        
                                        if ($hasPending) {
                                            echo "<td class='action-buttons'>";
                                            if ($row['statut'] === 'en attente') {
                                                echo "<a href='reservations.php?action=confirmer&id={$row['id']}&user={$row['client_id']}&voyage=" . urlencode($row['titre']) . "' class='btn btn-success btn-sm confirm-btn'><i class='fas fa-check'></i> Confirmer</a> ";
                                                echo "<a href='reservations.php?action=refuser&id={$row['id']}&user={$row['client_id']}&voyage=" . urlencode($row['titre']) . "' class='btn btn-danger btn-sm reject-btn'><i class='fas fa-times'></i> Refuser</a>";
                                            } else {
                                                echo "<span class='text-muted'>Aucune action</span>";
                                            }
                                            echo "</td>";
                                        }
                                        
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 for confirmation dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation for approve/reject actions
        const confirmButtons = document.querySelectorAll('.confirm-btn');
        const rejectButtons = document.querySelectorAll('.reject-btn');

        confirmButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Confirmer la réservation',
                    text: "Êtes-vous sûr de vouloir confirmer cette réservation?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, confirmer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = e.target.href;
                    }
                });
            });
        });

        rejectButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Refuser la réservation',
                    text: "Êtes-vous sûr de vouloir refuser cette réservation?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, refuser',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = e.target.href;
                    }
                });
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
    </script>
</body>

</html>