<?php

require_once('../includes/db.php');
require_once('../includes/auth.php');
require_once '../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

@session_start();
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type_rapport'])) {
    $type_rapport = $_POST['type_rapport'];
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;

    $html = '<h1>Rapport: ' . htmlspecialchars(ucfirst($type_rapport)) . '</h1>';
    $html .= '<p>Généré le ' . date('d/m/Y') . '</p>';

    // CSS simple
    $html .= '<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #000; padding: 5px; }</style>';

    if ($type_rapport === 'reservations') {
        $query = "SELECT r.id, u.nom, u.prenom, v.titre, r.date_reservation, r.nombre_personnes, r.statut 
                  FROM reservation r
                  JOIN utilisateur u ON r.client_id = u.id
                  JOIN voyage v ON r.voyage_id = v.id";

        if ($date_debut && $date_fin) {
            $query .= " WHERE r.date_reservation BETWEEN ? AND ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $date_debut, $date_fin);
        } else {
            $stmt = $conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $html .= '<table><tr><th>ID</th><th>Client</th><th>Voyage</th><th>Date</th><th>Personnes</th><th>Statut</th></tr>';

        while ($row = $result->fetch_assoc()) {
            $html .= "<tr>
                <td>{$row['id']}</td>
                <td>{$row['prenom']} {$row['nom']}</td>
                <td>{$row['titre']}</td>
                <td>{$row['date_reservation']}</td>
                <td>{$row['nombre_personnes']}</td>
                <td>{$row['statut']}</td>
            </tr>";
        }

        $html .= '</table>';
    }

    elseif ($type_rapport === 'revenus') {
        $query = "SELECT v.titre, COUNT(r.id) AS nb_reservations, 
                         SUM(r.nombre_personnes * v.prix) AS revenu_total
                  FROM voyage v
                  LEFT JOIN reservation r ON v.id = r.voyage_id
                  WHERE r.statut = 'confirmée'";

        if ($date_debut && $date_fin) {
            $query .= " AND r.date_reservation BETWEEN ? AND ?";
        }

        $query .= " GROUP BY v.id ORDER BY revenu_total DESC";

        $stmt = $conn->prepare($query);
        if ($date_debut && $date_fin) {
            $stmt->bind_param("ss", $date_debut, $date_fin);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $html .= '<table><tr><th>Voyage</th><th>Réservations</th><th>Revenu Total (DH)</th></tr>';
        while ($row = $result->fetch_assoc()) {
            $revenu = number_format($row['revenu_total'], 2, ',', ' ');
            $html .= "<tr><td>{$row['titre']}</td><td>{$row['nb_reservations']}</td><td>{$revenu}</td></tr>";
        }
        $html .= '</table>';
    }

    elseif ($type_rapport === 'utilisateurs') {
        $query = "SELECT u.id, u.nom, u.prenom, u.email, u.date_inscription, COUNT(r.id) AS nb_reservations
                  FROM utilisateur u
                  LEFT JOIN reservation r ON u.id = r.client_id
                  GROUP BY u.id ORDER BY nb_reservations DESC";

        $result = $conn->query($query);
        $html .= '<table><tr><th>ID</th><th>Nom</th><th>Email</th><th>Inscription</th><th>Réservations</th></tr>';

        while ($row = $result->fetch_assoc()) {
            $html .= "<tr>
                <td>{$row['id']}</td>
                <td>{$row['prenom']} {$row['nom']}</td>
                <td>{$row['email']}</td>
                <td>{$row['date_inscription']}</td>
                <td>{$row['nb_reservations']}</td>
            </tr>";
        }

        $html .= '</table>';
    }

    // Générer PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $pdf = new Dompdf($options);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream("rapport_" . $type_rapport . ".pdf", ["Attachment" => true]);
    exit;
}
?>
<?php include('../includes/header.php');?>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Flatpickr pour les dates -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.report-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.report-card {
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

.form-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.date-input-group {
    display: flex;
    gap: 15px;
}

.date-input-group .form-control {
    flex: 1;
}

.btn-generate {
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    border: none;
    padding: 10px 25px;
    font-weight: 500;
}

.btn-generate:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
}

@media (max-width: 768px) {
    .date-input-group {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<div class="report-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="fas fa-chart-line me-2"></i>Génération de Rapports
        </h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="report-card">
        <form method="post" id="reportForm">
            <div class="form-section mb-4">
                <h4 class="h5 mb-4"><i class="fas fa-file-alt me-2"></i>Type de Rapport</h4>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="type_rapport" class="form-label">Sélectionnez le type de rapport *</label>
                        <select class="form-select" id="type_rapport" name="type_rapport" required>
                            <option value="">-- Choisir un rapport --</option>
                            <option value="reservations">Réservations</option>
                            <option value="revenus">Revenus</option>
                            <option value="utilisateurs">Utilisateurs</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section mb-4">
                <h4 class="h5 mb-4"><i class="far fa-calendar-alt me-2"></i>Période</h4>

                <div class="row g-3">
                    <div class="col-md-12">
                        <p class="text-muted mb-3">Optionnel - Si non spécifié, toutes les données seront incluses</p>
                    </div>

                    <div class="col-md-6">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="text" class="form-control datepicker" id="date_debut" name="date_debut"
                            placeholder="JJ/MM/AAAA">
                    </div>

                    <div class="col-md-6">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="text" class="form-control datepicker" id="date_fin" name="date_fin"
                            placeholder="JJ/MM/AAAA">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-generate text-white">
                    <i class="fas fa-file-pdf me-2"></i>Générer le PDF
                </button>
            </div>
        </form>
    </div>

    <div class="report-card">
        <h4 class="h5 mb-4"><i class="fas fa-info-circle me-2"></i>Instructions</h4>
        <div class="alert alert-info">
            <p><strong>Types de rapports disponibles :</strong></p>
            <ul>
                <li><strong>Réservations :</strong> Liste détaillée de toutes les réservations avec filtrage par période
                </li>
                <li><strong>Revenus :</strong> Analyse des revenus par voyage avec totaux</li>
                <li><strong>Utilisateurs :</strong> Liste des utilisateurs avec leur nombre de réservations</li>
            </ul>
            <p class="mb-0">Les rapports sont générés au format PDF et téléchargés automatiquement.</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialisation des datepickers
document.addEventListener('DOMContentLoaded', function() {
    flatpickr(".datepicker", {
        dateFormat: "d/m/Y",
        locale: "fr",
        allowInput: true
    });

    // Validation du formulaire
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        const typeRapport = document.getElementById('type_rapport');
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        let isValid = true;

        if (!typeRapport.value) {
            typeRapport.classList.add('is-invalid');
            isValid = false;
        }

        if (dateDebut.value && !dateFin.value) {
            dateFin.classList.add('is-invalid');
            isValid = false;
        }

        if (dateFin.value && !dateDebut.value) {
            dateDebut.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();

            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez remplir correctement le formulaire',
                confirmButtonColor: '#3a7bd5'
            });

            // Scroll vers la première erreur
            const invalidField = document.querySelector('.is-invalid');
            if (invalidField) {
                invalidField.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                invalidField.focus();
            }
        }
    });

    // Retirer la classe is-invalid lors de la saisie
    document.querySelectorAll('#reportForm select, #reportForm input').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>

<?php include('../includes/footer.php'); ?>