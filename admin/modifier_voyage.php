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

// Récupération et validation de l'ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID de voyage invalide.");
}

// Initialisation des variables
$success = '';
$error = '';
$voyage = [];

// Récupération des données du voyage
try {
    $stmt = $conn->prepare("SELECT * FROM voyage WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $voyage = $result->fetch_assoc();

    if (!$voyage) {
        throw new Exception("Voyage introuvable.");
    }
} catch (Exception $e) {
    die($e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Nettoyage des données
        $titre = htmlspecialchars($_POST['titre'] ?? '');
        $destination = htmlspecialchars($_POST['destination'] ?? '');
        $description = htmlspecialchars($_POST['description'] ?? '');
        $date_depart = $_POST['date_depart'] ?? '';
        $date_retour = $_POST['date_retour'] ?? '';
        $prix = floatval($_POST['prix'] ?? 0);
        $places = intval($_POST['places_disponibles'] ?? 0);

        // Validation des données
        if (empty($titre) || empty($destination) || empty($description)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Mise à jour en base de données
        $stmt = $conn->prepare("UPDATE voyage SET titre=?, destination=?, description=?, date_depart=?, date_retour=?, prix=?, places_disponibles=? WHERE id=?");
        $stmt->bind_param("sssssdii", $titre, $destination, $description, $date_depart, $date_retour, $prix, $places, $id);

        if ($stmt->execute()) {
            $success = "Voyage mis à jour avec succès.";
            // Recharger les données après mise à jour
            $stmt = $conn->prepare("SELECT * FROM voyage WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $voyage = $result->fetch_assoc();
        } else {
            throw new Exception("Erreur lors de la mise à jour.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include('../includes/header.php');
?>

<!-- Page de modification améliorée -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class=" shadow-lg border-0 rounded-3 overflow-hidden">
                <!-- En-tête de carte -->
                <div class="card-header bg-primary text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-edit me-2"></i>Modifier le voyage
                        </h1>
                        <a href="voyages.php" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <!-- Corps de carte -->
                <div class="card-body p-4">
                    <!-- Messages d'état -->
                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-4"></i>
                            <div><?php echo $success; ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2 fs-4"></i>
                            <div><?php echo $error; ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire amélioré -->
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <!-- Titre -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="titre" name="titre"
                                        value="<?php echo htmlspecialchars($voyage['titre'] ?? ''); ?>" required
                                        minlength="3" maxlength="100">
                                    <label for="titre">Titre du voyage</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir un titre valide (3-100 caractères).
                                    </div>
                                </div>
                            </div>

                            <!-- Destination -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="destination" name="destination"
                                        value="<?php echo htmlspecialchars($voyage['destination'] ?? ''); ?>" required
                                        minlength="3" maxlength="50">
                                    <label for="destination">Destination</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir une destination valide (3-50 caractères).
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="description" name="description"
                                        style="min-height: 120px" required minlength="10"
                                        maxlength="1000"><?php echo htmlspecialchars($voyage['description'] ?? ''); ?></textarea>
                                    <label for="description">Description</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir une description valide (10-1000 caractères).
                                    </div>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="date_depart" name="date_depart"
                                        value="<?php echo $voyage['date_depart'] ?? ''; ?>" required
                                        min="<?php echo date('Y-m-d'); ?>">
                                    <label for="date_depart">Date de départ</label>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner une date valide.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="date_retour" name="date_retour"
                                        value="<?php echo $voyage['date_retour'] ?? ''; ?>" required
                                        min="<?php echo $voyage['date_depart'] ?? date('Y-m-d'); ?>">
                                    <label for="date_retour">Date de retour</label>
                                    <div class="invalid-feedback">
                                        La date de retour doit être après la date de départ.
                                    </div>
                                </div>
                            </div>

                            <!-- Prix et Places -->

                            <div class="col-md-6">
                                <div class="form-floating mb-4">
                                    <input type="number" class="form-control" id="prix" name="prix"
                                        value="<?php echo $voyage['places_disponibles'] ?? 0; ?>" min="0" max="999"
                                        required>
                                    <label for="prix">prix(DH)</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir un prix valide (0-99999 DH).
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-4">
                                    <input type="number" class="form-control" id="places_disponibles"
                                        name="places_disponibles"
                                        value="<?php echo $voyage['places_disponibles'] ?? 0; ?>" min="0" max="999"
                                        required>
                                    <label for="places_disponibles">Places disponibles</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir un nombre de places valide (0-999).
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Réinitialiser
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style CSS amélioré -->
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
}

.form-floating>label {
    color: #6c757d;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    background-color: #f8f9fa;
}

.btn-primary {
    background-color: #0d6efd;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
}

.alert {
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #198754;
}

.alert-danger {
    border-left-color: #dc3545;
}

.invalid-feedback {
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.25rem;
    }

    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
    }
}
</style>

<!-- JavaScript amélioré -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formatage des dates pour l'affichage
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // Appliquer le formatage aux dates
    const dateDepart = document.getElementById('date_depart');
    const dateRetour = document.getElementById('date_retour');

    if (dateDepart) dateDepart.value = formatDateForInput('<?php echo $voyage['date_depart'] ?? ''; ?>');
    if (dateRetour) dateRetour.value = formatDateForInput('<?php echo $voyage['date_retour'] ?? ''; ?>');

    // Validation personnalisée
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            // Validation des dates
            if (dateDepart && dateRetour) {
                if (new Date(dateRetour.value) <= new Date(dateDepart.value)) {
                    dateRetour.setCustomValidity(
                        'La date de retour doit être après la date de départ');
                } else {
                    dateRetour.setCustomValidity('');
                }
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Mise à jour dynamique de la date minimum de retour
    if (dateDepart && dateRetour) {
        dateDepart.addEventListener('change', function() {
            dateRetour.min = this.value;
            if (new Date(dateRetour.value) < new Date(this.value)) {
                dateRetour.value = this.value;
            }
        });
    }

    // Animation des labels
    const floatLabels = document.querySelectorAll(
        '.form-floating input, .form-floating textarea, .form-floating select');
    floatLabels.forEach(el => {
        el.addEventListener('focus', function() {
            this.parentNode.querySelector('label').style.color = '#0d6efd';
            this.parentNode.querySelector('label').style.transform =
                'scale(0.85) translateY(-0.5rem) translateX(0.15rem)';
        });

        el.addEventListener('blur', function() {
            this.parentNode.querySelector('label').style.color = '#6c757d';
            if (!this.value) {
                this.parentNode.querySelector('label').style.transform = '';
            }
        });

        // Initialisation pour les champs pré-remplis
        if (el.value) {
            el.parentNode.querySelector('label').style.transform =
                'scale(0.85) translateY(-0.5rem) translateX(0.15rem)';
        }
    });
});
</script>

<?php include('../includes/footer.php'); ?>