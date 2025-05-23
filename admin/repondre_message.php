<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success = '';
$error = '';

// Récupération du message
$stmt = $conn->prepare("SELECT * FROM message_contact WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$message = $res->fetch_assoc();

if (!$message) {
    die("Message introuvable.");
}

// Traitement de la réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reponse = htmlspecialchars(trim($_POST['reponse']));
    
    if (empty($reponse)) {
        $error = "Veuillez saisir une réponse";
    } else {
        try {
            // Début de transaction
            $conn->begin_transaction();
            
            // Mise à jour de la réponse et du statut
            $stmt = $conn->prepare("UPDATE message_contact SET reponse_admin = ?, statut = 'répondu', date_envoi = NOW() WHERE id = ?");
            $stmt->bind_param("si", $reponse, $id);
            $stmt->execute();
            
            // Récupération des infos pour la notification
            $stmtU = $conn->prepare("SELECT utilisateur_id, sujet FROM message_contact WHERE id = ?");
            $stmtU->bind_param("i", $id);
            $stmtU->execute();
            $resU = $stmtU->get_result();
            $data = $resU->fetch_assoc();
            
            if ($data) {
                $user_id = $data['utilisateur_id'];
                $sujet = $data['sujet'];

                // Création de la notification
                $contenuNotif = "Réponse à votre message \"$sujet\"";
                $stmtNotif = $conn->prepare("INSERT INTO notification (utilisateur_id, type, contenu, date_envoi, lu) VALUES (?, 'reponse_message', ?, NOW(), 0)");
                $stmtNotif->bind_param("is", $user_id, $contenuNotif);
                $stmtNotif->execute();
            }
            
            // Validation de la transaction
            $conn->commit();
            
            // Message flash pour la redirection
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'message' => "Réponse envoyée avec succès"
            ];
            header("Location: message_contact.php");
            exit;
            
        } catch (Exception $e) {
            // Annulation en cas d'erreur
            $conn->rollback();
            $error = "Erreur lors de l'envoi de la réponse: " . $e->getMessage();
        }
    }
}
include('../includes/header.php');
?>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/gjlun5rl8jc0bu7p8s6wb9i1m2mi5249d89frue3dcs20wo7/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.reponse-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 30px;
}

.message-card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    border: none;
}

.message-header {
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 15px 20px;
}

.message-body {
    padding: 25px;
    background-color: #f8f9fc;
}

.message-content {
    white-space: pre-line;
    background-color: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #3a7bd5;
}

.reponse-form {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 25px;
}

.reponse-form textarea {
    min-height: 200px;
}

.info-label {
    font-weight: 600;
    color: #5a5c69;
    width: 120px;
}

.badge-statut {
    font-size: 0.9rem;
    padding: 5px 10px;
}

.badge-non-lu {
    background-color: #e74a3b;
}

.badge-lu {
    background-color: #1cc88a;
}

.badge-repondu {
    background-color: #36b9cc;
}

.btn-send {
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    border: none;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-send:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
}
</style>

<div class="reponse-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-gray-800">
            <i class="fas fa-reply me-2"></i>Répondre au message
        </h2>
        <a href="message_contact.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux messages
        </a>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_message']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="message-container">
        <div class="message-card">
            <div class="card-header message-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0">Détails du message</h3>
                    <span class="badge badge-statut badge-<?= 
                    $message['statut'] === 'non lu' ? 'non-lu' : 
                    ($message['statut'] === 'répondu' ? 'repondu' : 'lu') 
                ?>">
                        <?= $message['statut'] ?>
                    </span>
                </div>
            </div>
            <div class="card-body message-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><span class="info-label">Nom :</span> <?= htmlspecialchars($message['nom']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="info-label">Email :</span> <?= htmlspecialchars($message['email']) ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><span class="info-label">Date :</span>
                            <?= date('d/m/Y H:i', strtotime($message['date_envoi'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="info-label">Sujet :</span> <?= htmlspecialchars($message['sujet']) ?></p>
                    </div>
                </div>
                <div class="mb-3">
                    <p class="info-label">Message :</p>
                    <div class="message-content">
                        <?= nl2br(htmlspecialchars($message['contenu'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="reponse-form">
            <h4 class="h5 text-gray-800 mb-4"><i class="fas fa-edit me-2"></i>Réponse</h4>
            <form method="post" id="responseForm">
                <div class="mb-3">
                    <label for="reponse" class="form-label">Votre réponse *</label>
                    <textarea class="form-control" id="reponse" name="reponse" rows="8" required><?= 
    htmlspecialchars($message['reponse_admin'] ?? '') 
?></textarea>
                    <div class="invalid-feedback">Veuillez saisir une réponse</div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-send text-white">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer la réponse
                    </button>
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
// Validation du formulaire
document.getElementById('responseForm').addEventListener('submit', function(e) {
    const reponse = document.getElementById('reponse');
    let isValid = true;

    if (reponse.value.trim() === '') {
        reponse.classList.add('is-invalid');
        isValid = false;
    } else {
        reponse.classList.remove('is-invalid');
    }

    if (!isValid) {
        e.preventDefault();
        reponse.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        reponse.focus();
    }
});

// Fermeture automatique des alertes
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Confirmation avant envoi (optionnel)
document.getElementById('responseForm').addEventListener('submit', function(e) {
    const reponse = document.getElementById('reponse').value.trim();
    if (reponse !== '') {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmer l\'envoi',
            text: "Êtes-vous sûr de vouloir envoyer cette réponse ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3a7bd5',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, envoyer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    }
});
</script>
<script>
// Initialisation de TinyMCE
tinymce.init({
    selector: '#reponse',
    plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
    toolbar_mode: 'floating',
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    menubar: false,
    statusbar: false,
    height: 300,
    setup: function(editor) {
        editor.on('change', function() {
            editor.save();
        });
    }
});

// Validation du formulaire
document.getElementById('responseForm').addEventListener('submit', function(e) {
    const reponse = tinymce.get('reponse').getContent().trim();
    let isValid = true;

    if (reponse === '') {
        document.getElementById('reponse').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('reponse').classList.remove('is-invalid');
    }

    if (!isValid) {
        e.preventDefault();
        document.getElementById('reponse').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        tinymce.get('reponse').focus();
    }
});

// Fermeture automatique des alertes
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Confirmation avant envoi (optionnel)
document.getElementById('responseForm').addEventListener('submit', function(e) {
    const reponse = tinymce.get('reponse').getContent().trim();
    if (reponse !== '') {
        e.preventDefault();
        Swal.fire({
            title: 'Confirmer l\'envoi',
            text: "Êtes-vous sûr de vouloir envoyer cette réponse ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3a7bd5',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, envoyer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    }
});
</script>
<?php include('../includes/footer.php'); ?>