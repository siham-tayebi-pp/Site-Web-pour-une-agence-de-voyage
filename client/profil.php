<?php
session_start();
$admin = false;
include('../includes/db.php');
include('../includes/auth.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Récupération des données utilisateur
$stmt = $conn->prepare("SELECT nom, prenom, email, telephone, dateInscription FROM utilisateur WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    
    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email)) {
        $error = "Les champs nom, prénom et email sont obligatoires";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide";
    } else {
        // Vérification si l'email existe déjà pour un autre utilisateur
        $checkEmail = $conn->prepare("SELECT id FROM utilisateur WHERE email = ? AND id != ?");
        $checkEmail->bind_param("si", $email, $user_id);
        $checkEmail->execute();
        
        if ($checkEmail->get_result()->num_rows > 0) {
            $error = "Cette adresse email est déjà utilisée par un autre compte";
        } else {
            // Mise à jour avec ou sans mot de passe
            if (!empty($_POST['motdepasse'])) {
                if (strlen($_POST['motdepasse']) < 8) {
                    $error = "Le mot de passe doit contenir au moins 8 caractères";
                } else {
                    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, telephone=?, motdepasse=? WHERE id=?");
                    $stmt->bind_param("sssssi", $nom, $prenom, $email, $telephone, $motdepasse, $user_id);
                }
            } else {
                $stmt = $conn->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, telephone=? WHERE id=?");
                $stmt->bind_param("ssssi", $nom, $prenom, $email, $telephone, $user_id);
            }
            
            if (empty($error) && $stmt->execute()) {
                $success = "Profil mis à jour avec succès";
                // Rafraîchir les données utilisateur
                $stmt = $conn->prepare("SELECT nom, prenom, email, telephone FROM utilisateur WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
            } else if (empty($error)) {
                $error = "Erreur lors de la mise à jour du profil: " . $conn->error;
            }
        }
    }
}

include('../includes/header.php');
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.profile-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 30px;
}

.profile-card {
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.profile-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    padding: 30px;
    text-align: center;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: white;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 50px;
    color: #4e73df;
    border: 5px solid white;
}

.profile-body {
    padding: 30px;
    background-color: #f8f9fc;
}

.profile-info {
    margin-bottom: 30px;
}

.info-item {
    display: flex;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e3e6f0;
}

.info-label {
    font-weight: 600;
    color: #5a5c69;
    width: 180px;
}

.info-value {
    flex: 1;
}

.password-toggle {
    cursor: pointer;
    color: #4e73df;
}

.password-toggle:hover {
    text-decoration: underline;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    border-radius: 8px;
    padding: 12px 15px;
}

.btn-save {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border: none;
    padding: 12px 30px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn-save:hover {
    background: linear-gradient(135deg, #3b5fc5 0%, #1a3a9e 100%);
}

.member-since {
    color: #858796;
    font-size: 0.9rem;
    margin-top: 5px;
}
</style>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
            </div>
            <h3><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h3>
            <div class="member-since">
                <i class="far fa-calendar-alt me-2"></i>Membre depuis
                <?= date('d/m/Y', strtotime($user['dateInscription'])) ?>
            </div>
        </div>

        <div class="profile-body">
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form method="POST">
                <h4 class="h5 text-gray-800 mb-4"><i class="fas fa-user-edit me-2"></i>Informations personnelles</h4>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="prenom"
                                value="<?= htmlspecialchars($user['prenom']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom"
                                value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email"
                        value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" name="telephone"
                        value="<?= htmlspecialchars($user['telephone']) ?>">
                </div>

                <h4 class="h5 text-gray-800 mb-4 mt-5"><i class="fas fa-lock me-2"></i>Sécurité</h4>

                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="motdepasse" id="passwordField">
                        <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">Minimum 8 caractères</small>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('passwordField');
    const icon = this.querySelector('i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Fermeture automatique des alertes après 5 secondes
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<?php include('../includes/footer.php'); ?>