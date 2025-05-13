<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

// Vérification du rôle admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Suppression d'un avis
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    
    try {
        $stmt = $conn->prepare("DELETE FROM avis WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "L'avis a été supprimé avec succès.";
        } else {
            throw new Exception("Erreur lors de la suppression de l'avis.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    header("Location: avis.php");
    exit;
}

include('../includes/header.php');
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class=" shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">
                            <i class="fas fa-comments me-2 px-4"></i>Gestion des avis
                        </h2>
                        <a href="dashboard.php" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Messages d'alerte -->
                    <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Tableau des avis -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Note</th>
                                    <th width="30%">Commentaire</th>
                                    <th width="20%">Voyage</th>
                                    <th width="15%">Date</th>
                                    <th width="15%">Utilisateur</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT a.*, v.titre AS voyage_titre, u.nom AS utilisateur_nom 
                                        FROM avis a 
                                        JOIN voyage v ON a.voyage_id = v.id
                                        JOIN utilisateur u ON a.utilisateur_id = u.id
                                        ORDER BY a.date_publication DESC";
                                $res = $conn->query($sql);
                                
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . str_repeat('⭐', $row['note']) . ' (' . $row['note'] . '/5)</td>';
                                        echo '<td class="text-truncate" style="max-width: 300px;" title="' . htmlspecialchars($row['commentaire']) . '">' . htmlspecialchars($row['commentaire']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['voyage_titre']) . '</td>';
                                        echo '<td>' . date('d/m/Y H:i', strtotime($row['date_publication'])) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['utilisateur_nom']) . '</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="avis.php?supprimer=' . $row['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet avis ?\')">';
                                        echo '<i class="fas fa-trash-alt"></i> Supprimer';
                                        echo '</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center py-4 text-muted">Aucun avis à afficher</td></tr>';
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

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-header {
    border-bottom: none;
}

.table {
    font-size: 0.9rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.text-truncate {
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-outline-danger {
    transition: all 0.2s;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

.star-rating {
    color: #ffc107;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }

    .table th,
    .table td {
        white-space: nowrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation pour les lignes du tableau
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '1';
        }, index * 50);
    });

    // Tooltip pour les commentaires tronqués
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php include('../includes/footer.php'); ?>