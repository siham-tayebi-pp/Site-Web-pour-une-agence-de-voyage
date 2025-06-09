<?php
session_start();
$admin = false;
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['voyage_id'])) {
    header("Location: ../login.php");
    exit;
}

$voyage_id = intval($_GET['voyage_id']);
$user_id = $_SESSION['user_id'];
$success = false;

// Récupérer les infos du voyage pour l'affichage
$stmt = $conn->prepare("SELECT titre FROM voyage WHERE id = ?");
$stmt->bind_param("i", $voyage_id);
$stmt->execute();
$result = $stmt->get_result();
$voyage = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentaire = htmlspecialchars(trim($_POST['commentaire']));
    $note = intval($_POST['note']);

    // Validation
    if ($note < 1 || $note > 5) {
        $error = "La note doit être entre 1 et 5 étoiles";
    } elseif (strlen($commentaire) < 10) {
        $error = "Le commentaire doit contenir au moins 10 caractères";
    } else {
        $stmt = $conn->prepare("INSERT INTO avis (utilisateur_id, voyage_id, commentaire, note, date_publication) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iisi", $user_id, $voyage_id, $commentaire, $note);
        if ($stmt->execute()) {
            $success = true;
            $_POST = array(); // Réinitialiser les champs
        } else {
            $error = "Erreur lors de l'ajout de l'avis: " . $conn->error;
        }
    }
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
.avis-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 120px);
    background-color: #f8f9fa;
}

.avis-card {
    width: 100%;
    max-width: 700px;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.avis-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.star-rating {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    cursor: pointer;
}

.star-rating i {
    transition: all 0.2s ease;
}

.star-rating i:hover {
    transform: scale(1.2);
}

.btn-submit {
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    border: none;
    padding: 0.8rem 2rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
}

.voyage-title {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .avis-container {
        padding: 20px;
    }

    .avis-card {
        max-width: 100%;
    }

    .card-header h3 {
        font-size: 1.5rem;
    }
}
</style>

<div class="avis-container">
    <div class="avis-card card animate__animated animate__fadeIn">
        <div class="card-header">
            <h3><i class="far fa-edit me-2"></i>Donner votre avis</h3>
            <div class="voyage-title">Pour le voyage : <?= htmlspecialchars($voyage['titre']) ?></div>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                Votre avis a été ajouté avec succès !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form id="avisForm" method="POST">
                <div class="mb-4">
                    <label for="note" class="form-label fw-bold">Note</label>
                    <div class="star-rating mb-3" id="starRating">
                        <i class="far fa-star" data-value="1"></i>
                        <i class="far fa-star" data-value="2"></i>
                        <i class="far fa-star" data-value="3"></i>
                        <i class="far fa-star" data-value="4"></i>
                        <i class="far fa-star" data-value="5"></i>
                        <input type="hidden" name="note" id="noteInput" value="<?= $_POST['note'] ?? '' ?>">
                    </div>
                    <div class="invalid-feedback d-block">Veuillez sélectionner une note</div>
                </div>

                <div class="mb-4">
                    <label for="commentaire" class="form-label fw-bold">Commentaire</label>
                    <textarea class="form-control" id="commentaire" name="commentaire" rows="6" required minlength="10"
                        placeholder="Décrivez votre expérience... (minimum 10 caractères)"><?= $_POST['commentaire'] ?? '' ?></textarea>
                    <div class="invalid-feedback">Le commentaire doit contenir au moins 10 caractères</div>
                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Votre avis aide les autres
                        voyageurs à faire leur choix</div>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-submit text-white btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Publier mon avis
                    </button>
                    <a href="../details.php?id=<?= $voyage_id ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au voyage
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('avisForm');
    const noteInput = document.getElementById('noteInput');
    const stars = document.querySelectorAll('#starRating i');
    const commentaire = document.getElementById('commentaire');

    // Système de notation par étoiles
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.getAttribute('data-value'));
            noteInput.value = value;

            // Mettre à jour l'affichage des étoiles
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.remove('far');
                    s.classList.add('fas', 'text-warning');
                } else {
                    s.classList.remove('fas', 'text-warning');
                    s.classList.add('far');
                }
            });

            // Cacher l'erreur si présente
            document.querySelector('.invalid-feedback.d-block').style.display = 'none';
        });

        // Effet hover sur les étoiles
        star.addEventListener('mouseover', function() {
            const value = parseInt(this.getAttribute('data-value'));
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.add('text-warning');
                }
            });
        });

        star.addEventListener('mouseout', function() {
            const selectedValue = noteInput.value ? parseInt(noteInput.value) : 0;
            stars.forEach((s, index) => {
                if (index >= selectedValue) {
                    s.classList.remove('text-warning');
                }
            });
        });
    });

    // Pré-sélectionner les étoiles si une note existe déjà
    if (noteInput.value) {
        stars.forEach((star, index) => {
            if (index < noteInput.value) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-warning');
            }
        });
    }

    // Validation en temps réel
    commentaire.addEventListener('input', function() {
        if (this.value.length >= 10) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
        }
    });

    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Valider la note
        if (!noteInput.value) {
            document.querySelector('.invalid-feedback.d-block').style.display = 'block';
            isValid = false;
        }

        // Valider le commentaire
        if (commentaire.value.length < 10) {
            commentaire.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();

            // Animation pour indiquer les erreurs
            if (!noteInput.value) {
                document.getElementById('starRating').classList.add('animate__animated',
                    'animate__headShake');
                setTimeout(() => {
                    document.getElementById('starRating').classList.remove('animate__animated',
                        'animate__headShake');
                }, 1000);
            }

            if (commentaire.value.length < 10) {
                commentaire.classList.add('animate__animated', 'animate__headShake');
                setTimeout(() => {
                    commentaire.classList.remove('animate__animated', 'animate__headShake');
                }, 1000);
            }

            // Scroll vers la première erreur
            const firstError = document.querySelector(
                '.is-invalid, .invalid-feedback.d-block[style="display: block;"]');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
    });

    // Disparition automatique des alertes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });

    <?php if ($success): ?>
    // Redirection après succès
    setTimeout(() => {
        window.location.href = `../details.php?id=<?= $voyage_id ?>`;
    }, 2000);
    <?php endif; ?>
});
</script>

<?php include('../includes/footer.php'); ?>