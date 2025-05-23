<?php
session_start();
include('includes/db.php');
include('includes/auth.php');
include('includes/header.php');

$voyage_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$erreur = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_passager = htmlspecialchars(trim($_POST['nom_passager']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $nombre_personnes = intval($_POST['nombre_personnes']);
    $options = htmlspecialchars(trim($_POST['options']));
    $client_id = $_SESSION['user_id'];

    // Validation supplémentaire
    if (empty($nom_passager) || strlen($nom_passager) < 2) {
        $erreur = "Le nom doit contenir au moins 2 caractères.";
    } elseif (!preg_match('/^[0-9\-\+\s\(\)]{10,20}$/', $telephone)) {
        $erreur = "Le numéro de téléphone n'est pas valide.";
    } elseif ($nombre_personnes < 1 || $nombre_personnes > 20) {
        $erreur = "Le nombre de personnes doit être entre 1 et 20.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reservation (voyage_id, client_id, nom_passager, telephone, nombre_personnes, options) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissis", $voyage_id, $client_id, $nom_passager, $telephone, $nombre_personnes, $options);

        if ($stmt->execute()) {
            $success = "Réservation effectuée avec succès!";
            $_POST = array();
        } else {
            $erreur = "Erreur lors de la réservation: " . $conn->error;
        }
    }
}

// Get voyage data
$stmt = $conn->prepare("SELECT * FROM voyage WHERE id = ?");
$stmt->bind_param("i", $voyage_id);
$stmt->execute();
$result = $stmt->get_result();
$voyage = $result->fetch_assoc();

if (!$voyage) {
    header("Location: voyages.php");
    exit();
}
?>

<!-- Bootstrap CSS et JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.reservation-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 15px;
}

.reservation-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    color: white;
    padding: 1.5rem;
}

.card-body {
    padding: 2rem;
}

.form-control:focus {
    border-color: #3a7bd5;
    box-shadow: 0 0 0 0.25rem rgba(58, 123, 213, 0.25);
}

.btn-primary {
    background-color: #3a7bd5;
    border-color: #3a7bd5;
    padding: 0.75rem;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #2c5fb3;
    border-color: #2c5fb3;
}

.btn-outline-secondary {
    padding: 0.75rem;
}

.voyage-badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    background-color: rgba(255, 255, 255, 0.2);
}

.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875em;
}

.is-invalid~.invalid-feedback {
    display: block;
}

.card-footer {
    background-color: #f8f9fa;
    padding: 1rem 2rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
}
</style>

<div class="reservation-container">
    <div class="reservation-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0"><i class="fas fa-calendar-check me-2"></i>Réserver le voyage</h2>
                <span class="voyage-badge rounded-pill">
                    <?php echo htmlspecialchars($voyage['titre']); ?>
                </span>
            </div>
        </div>

        <div class="card-body">
            <?php if ($erreur): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $erreur; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form id="reservationForm" action="" method="post" novalidate>
                <div class="mb-4">
                    <label for="nom_passager" class="form-label fw-bold">Nom complet <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text"
                            class="form-control <?php echo isset($erreur) && empty($nom_passager) ? 'is-invalid' : ''; ?>"
                            id="nom_passager" name="nom_passager" placeholder="Votre nom complet"
                            value="<?php echo isset($_POST['nom_passager']) ? htmlspecialchars($_POST['nom_passager']) : ''; ?>"
                            required minlength="2">
                    </div>
                    <div class="invalid-feedback">Veuillez entrer un nom valide (au moins 2 caractères).</div>
                </div>

                <div class="mb-4">
                    <label for="telephone" class="form-label fw-bold">Téléphone <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="tel"
                            class="form-control <?php echo isset($erreur) && !preg_match('/^[0-9\-\+\s\(\)]{10,20}$/', $_POST['telephone'] ?? '') ? 'is-invalid' : ''; ?>"
                            id="telephone" name="telephone" placeholder="0612345678"
                            value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>"
                            required pattern="[0-9\-\+\s\(\)]{10,20}">
                    </div>
                    <div class="invalid-feedback">Veuillez entrer un numéro de téléphone valide (10-20 chiffres).</div>
                    <small class="text-muted">Format: 0612345678 ou +33612345678</small>
                </div>

                <div class="mb-4">
                    <label for="nombre_personnes" class="form-label fw-bold">Nombre de personnes <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <input type="number"
                            class="form-control <?php echo (isset($erreur) && isset($_POST['nombre_personnes'])) && ($_POST['nombre_personnes'] < 1 || $_POST['nombre_personnes'] > 20) ? 'is-invalid' : ''; ?>"
                            id="nombre_personnes" name="nombre_personnes"
                            value="<?php echo isset($_POST['nombre_personnes']) ? (int)$_POST['nombre_personnes'] : 1; ?>"
                            min="1" max="20" required>
                    </div>
                    <div class="invalid-feedback">Le nombre doit être entre 1 et 20.</div>
                </div>

                <div class="mb-4">
                    <label for="options" class="form-label fw-bold">Options supplémentaires</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-edit"></i></span>
                        <textarea class="form-control" id="options" name="options" rows="3"
                            placeholder="Ex: Repas végétarien, guide francophone, chambre single..."><?php echo isset($_POST['options']) ? htmlspecialchars($_POST['options']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="d-grid gap-3 mt-5">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        <i class="fas fa-check-circle me-2"></i> Confirmer la réservation
                    </button>
                    <a href="details.php?id=<?php echo $voyage_id; ?>" class="btn btn-outline-secondary py-3">
                        <i class="fas fa-arrow-left me-2"></i> Retour aux détails
                    </a>
                </div>
            </form>
        </div>

        <div class="card-footer text-center">
            <small class="text-muted">Les champs marqués d'un <span class="text-danger">*</span> sont
                obligatoires.</small>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation en temps réel
    const form = document.getElementById('reservationForm');
    const fields = {
        nom_passager: {
            regex: /^.{2,}$/,
            message: "Le nom doit contenir au moins 2 caractères"
        },
        telephone: {
            regex: /^[0-9\-\+\s\(\)]{10,20}$/,
            message: "Numéro de téléphone invalide (10-20 chiffres)"
        },
        nombre_personnes: {
            validate: function(value) {
                const num = parseInt(value);
                return num >= 1 && num <= 20;
            },
            message: "Doit être entre 1 et 20"
        }
    };

    // Validation pour chaque champ
    Object.keys(fields).forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                validateField(this);
            });

            field.addEventListener('blur', function() {
                validateField(this);
            });
        }
    });

    function validateField(field) {
        const config = fields[field.id];
        let isValid;

        if (config.regex) {
            isValid = config.regex.test(field.value);
        } else if (config.validate) {
            isValid = config.validate(field.value);
        }

        if (field.value.trim() === '' && field.required) {
            field.classList.add('is-invalid');
            field.nextElementSibling.textContent = "Ce champ est obligatoire";
        } else if (!isValid) {
            field.classList.add('is-invalid');
            field.nextElementSibling.textContent = config.message;
        } else {
            field.classList.remove('is-invalid');
        }
    }

    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        Object.keys(fields).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.classList.contains('is-invalid')) {
                validateField(field);
                if (field.classList.contains('is-invalid')) {
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            const invalidField = form.querySelector('.is-invalid');
            invalidField.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            invalidField.focus();
        }
    });

    // Disparition progressive des alertes
    <?php if ($success || $erreur): ?>
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    <?php endif; ?>
});
</script>

<?php include('includes/footer.php'); ?>