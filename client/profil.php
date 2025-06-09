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

// Récupération des statistiques utilisateur (réservations, etc.)
$statsStmt = $conn->prepare("SELECT COUNT(*) as total_reservations FROM reservation WHERE id = ?");
$statsStmt->bind_param("i", $user_id);
$statsStmt->execute();
$stats = $statsStmt->get_result()->fetch_assoc();

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
    } elseif (!empty($telephone) && !preg_match('/^[0-9+\-\s()]+$/', $telephone)) {
        $error = "Le numéro de téléphone n'est pas valide";
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
                } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $_POST['motdepasse'])) {
                    $error = "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre";
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Variables CSS */
:root {
    --primary-color: #3a7bd5;
    --primary-gradient: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
    --secondary-color: #6c5ce7;
    --success-color: #00b894;
    --danger-color: #e17055;
    --warning-color: #fdcb6e;
    --text-dark: #2d3436;
    --text-medium: #636e72;
    --text-light: #b2bec3;
    --bg-light: #f8f9fa;
    --bg-white: #ffffff;
    --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 5px 20px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
    --shadow-xl: 0 20px 60px rgba(0, 0, 0, 0.15);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 20px;
    --radius-xl: 30px;
    --transition-fast: 0.2s ease;
    --transition-normal: 0.3s ease;
    --transition-slow: 0.5s ease;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.02);
    }
}

@keyframes shake {

    0%,
    100% {
        transform: translateX(0);
    }

    25% {
        transform: translateX(-5px);
    }

    75% {
        transform: translateX(5px);
    }
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-5px);
    }
}

/* Layout principal */
body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.profile-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 20px;
    animation: fadeIn 0.8s ease;
}

/* Carte principale */
.profile-card {
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    background: var(--bg-white);
    position: relative;
}

.profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(58, 123, 213, 0.02), rgba(0, 210, 255, 0.02));
    pointer-events: none;
    z-index: 1;
}

/* En-tête du profil */
.profile-header {
    background: var(--primary-gradient);
    color: white;
    padding: 40px 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    animation: float 20s linear infinite;
}

.profile-avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    margin: 0 auto 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    font-weight: 700;
    color: white;
    border: 4px solid rgba(255, 255, 255, 0.3);
    position: relative;
    z-index: 2;
    transition: all var(--transition-normal);
    animation: pulse 3s infinite;
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.profile-name {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    z-index: 2;
}

.member-since {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    margin-bottom: 20px;
    position: relative;
    z-index: 2;
}

/* Statistiques utilisateur */
.user-stats {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 20px;
    position: relative;
    z-index: 2;
}

.stat-item {
    text-align: center;
    padding: 15px 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-md);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all var(--transition-normal);
}

.stat-item:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.2);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-top: 5px;
}

/* Corps du profil */
.profile-body {
    padding: 40px;
    background: var(--bg-white);
    position: relative;
    z-index: 2;
}

/* Alertes améliorées */
.alert {
    border: none;
    border-radius: var(--radius-md);
    padding: 20px 25px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
    animation: slideInLeft 0.5s ease;
}

.alert::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: currentColor;
}

.alert-success {
    background: linear-gradient(135deg, rgba(0, 184, 148, 0.1), rgba(0, 184, 148, 0.05));
    color: var(--success-color);
    border-left: 4px solid var(--success-color);
}

.alert-danger {
    background: linear-gradient(135deg, rgba(225, 112, 85, 0.1), rgba(225, 112, 85, 0.05));
    color: var(--danger-color);
    border-left: 4px solid var(--danger-color);
    animation: shake 0.5s ease;
}

/* Sections du formulaire */
.form-section {
    margin-bottom: 40px;
    padding: 30px;
    background: var(--bg-light);
    border-radius: var(--radius-lg);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all var(--transition-normal);
}

.form-section:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

/* Groupes de formulaire */
.form-group {
    margin-bottom: 25px;
    position: relative;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: block;
    font-size: 0.95rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: var(--radius-md);
    padding: 15px 20px;
    font-size: 1rem;
    transition: all var(--transition-normal);
    background: var(--bg-white);
    position: relative;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.15);
    outline: none;
    transform: translateY(-1px);
}

.form-control:hover {
    border-color: var(--primary-color);
}

/* Validation visuelle */
.form-control.is-valid {
    border-color: var(--success-color);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300b894' viewBox='0 0 16 16'%3E%3Cpath d='M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px;
}

.form-control.is-invalid {
    border-color: var(--danger-color);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23e17055' viewBox='0 0 16 16'%3E%3Cpath d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px;
}

/* Groupe de mot de passe */
.password-group {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-medium);
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all var(--transition-fast);
    z-index: 3;
}

.password-toggle:hover {
    color: var(--primary-color);
    background: rgba(58, 123, 213, 0.1);
}

.password-strength {
    margin-top: 10px;
    padding: 10px 15px;
    background: var(--bg-light);
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
}

.strength-bar {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin: 8px 0;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    width: 0;
    transition: all var(--transition-normal);
    border-radius: 2px;
}

.strength-weak {
    background: var(--danger-color);
}

.strength-medium {
    background: var(--warning-color);
}

.strength-strong {
    background: var(--success-color);
}

/* Boutons */
.btn-save {
    background: var(--primary-gradient);
    border: none;
    padding: 15px 35px;
    font-weight: 600;
    font-size: 1rem;
    border-radius: var(--radius-md);
    color: white;
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
    min-width: 200px;
}

.btn-save::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.7s;
}

.btn-save:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    color: white;
}

.btn-save:hover::before {
    left: 100%;
}

.btn-save:active {
    transform: translateY(-1px);
}

.btn-cancel {
    background: var(--bg-light);
    border: 2px solid #e9ecef;
    padding: 13px 35px;
    font-weight: 600;
    color: var(--text-medium);
    border-radius: var(--radius-md);
    transition: all var(--transition-normal);
    margin-right: 15px;
}

.btn-cancel:hover {
    background: #e9ecef;
    color: var(--text-dark);
    transform: translateY(-2px);
}

/* Indicateurs de chargement */
.loading {
    position: relative;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .profile-container {
        margin: 20px auto;
        padding: 15px;
    }

    .profile-header {
        padding: 30px 20px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        font-size: 2.5rem;
    }

    .profile-name {
        font-size: 1.5rem;
    }

    .user-stats {
        flex-direction: column;
        gap: 15px;
    }

    .profile-body {
        padding: 25px 20px;
    }

    .form-section {
        padding: 20px;
        margin-bottom: 25px;
    }

    .btn-save,
    .btn-cancel {
        width: 100%;
        margin-bottom: 10px;
    }
}

@media (max-width: 576px) {
    .profile-header {
        padding: 25px 15px;
    }

    .profile-body {
        padding: 20px 15px;
    }

    .section-title {
        font-size: 1.1rem;
    }

    .form-control {
        padding: 12px 15px;
    }
}

/* Animations d'entrée */
.form-section:nth-child(1) {
    animation: fadeIn 0.6s ease 0.1s both;
}

.form-section:nth-child(2) {
    animation: fadeIn 0.6s ease 0.3s both;
}

.form-section:nth-child(3) {
    animation: fadeIn 0.6s ease 0.5s both;
}
</style>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
            </div>
            <h3 class="profile-name"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h3>
            <div class="member-since">
                <i class="far fa-calendar-alt me-2"></i>Membre depuis
                <?= date('d/m/Y', strtotime($user['dateInscription'])) ?>
            </div>

            <div class="user-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= $stats['total_reservations'] ?></span>
                    <div class="stat-label">Réservations</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= date('Y') - date('Y', strtotime($user['dateInscription'])) ?></span>
                    <div class="stat-label">Années</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">
                        <i class="fas fa-star"></i>
                    </span>
                    <div class="stat-label">Membre VIP</div>
                </div>
            </div>
        </div>

        <div class="profile-body">
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form method="POST" id="profileForm" novalidate>
                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-user-edit"></i>
                        Informations personnelles
                    </h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Prénom *</label>
                                <input type="text" class="form-control" name="prenom" id="prenom"
                                    value="<?= htmlspecialchars($user['prenom']) ?>" required>
                                <div class="invalid-feedback">Le prénom est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nom *</label>
                                <input type="text" class="form-control" name="nom" id="nom"
                                    value="<?= htmlspecialchars($user['nom']) ?>" required>
                                <div class="invalid-feedback">Le nom est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                        <div class="invalid-feedback">Veuillez saisir une adresse email valide</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" name="telephone" id="telephone"
                            value="<?= htmlspecialchars($user['telephone']) ?>" placeholder="+212 6 12 34 56 78">
                        <small class="text-muted">Format: +212 6 12 34 56 78</small>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="section-title">
                        <i class="fas fa-shield-alt"></i>
                        Sécurité
                    </h4>

                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe</label>
                        <div class="password-group">
                            <input type="password" class="form-control" name="motdepasse" id="passwordField"
                                placeholder="Laisser vide pour ne pas changer">
                            <button class="password-toggle" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="strength-text">Force du mot de passe: <span id="strengthText">Faible</span>
                            </div>
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <small class="text-muted">
                                Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un
                                chiffre
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end flex-wrap gap-3">
                    <button type="button" class="btn btn-cancel" onclick="resetForm()">
                        <i class="fas fa-undo me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-save" id="saveBtn">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const passwordField = document.getElementById('passwordField');
    const togglePassword = document.getElementById('togglePassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const saveBtn = document.getElementById('saveBtn');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
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

    // Password strength checker
    passwordField.addEventListener('input', function() {
        const password = this.value;

        if (password.length === 0) {
            passwordStrength.style.display = 'none';
            return;
        }

        passwordStrength.style.display = 'block';

        let strength = 0;
        let strengthLabel = 'Très faible';
        let strengthClass = 'strength-weak';

        // Length check
        if (password.length >= 8) strength += 25;

        // Lowercase check
        if (/[a-z]/.test(password)) strength += 25;

        // Uppercase check
        if (/[A-Z]/.test(password)) strength += 25;

        // Number check
        if (/\d/.test(password)) strength += 25;

        // Special character bonus
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 10;

        // Update strength display
        if (strength < 50) {
            strengthLabel = 'Faible';
            strengthClass = 'strength-weak';
        } else if (strength < 75) {
            strengthLabel = 'Moyen';
            strengthClass = 'strength-medium';
        } else {
            strengthLabel = 'Fort';
            strengthClass = 'strength-strong';
        }

        strengthFill.style.width = Math.min(strength, 100) + '%';
        strengthFill.className = 'strength-fill ' + strengthClass;
        strengthText.textContent = strengthLabel;
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Reset validation states
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });

        let isValid = true;

        // Validate required fields
        const requiredFields = ['prenom', 'nom', 'email'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.add('is-valid');
            }
        });

        // Validate email format
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField.value && !emailRegex.test(emailField.value)) {
            emailField.classList.remove('is-valid');
            emailField.classList.add('is-invalid');
            isValid = false;
        }

        // Validate phone format
        const phoneField = document.getElementById('telephone');
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (phoneField.value && !phoneRegex.test(phoneField.value)) {
            phoneField.classList.add('is-invalid');
            isValid = false;
        } else if (phoneField.value) {
            phoneField.classList.add('is-valid');
        }

        // Validate password if provided
        if (passwordField.value) {
            if (passwordField.value.length < 8) {
                passwordField.classList.add('is-invalid');
                isValid = false;
            } else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(passwordField.value)) {
                passwordField.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordField.classList.add('is-valid');
            }
        }

        if (isValid) {
            // Show loading state
            saveBtn.classList.add('loading');
            saveBtn.disabled = true;

            // Submit form
            setTimeout(() => {
                form.submit();
            }, 500);
        } else {
            // Shake form on error
            form.style.animation = 'shake 0.5s ease';
            setTimeout(() => {
                form.style.animation = '';
            }, 500);
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.trim()) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Reset form function
    window.resetForm = function() {
        form.reset();
        inputs.forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });
        passwordStrength.style.display = 'none';

        // Reset to original values
        document.getElementById('prenom').value = '<?= htmlspecialchars($user['prenom']) ?>';
        document.getElementById('nom').value = '<?= htmlspecialchars($user['nom']) ?>';
        document.getElementById('email').value = '<?= htmlspecialchars($user['email']) ?>';
        document.getElementById('telephone').value = '<?= htmlspecialchars($user['telephone']) ?>';
    };

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Animate avatar on page load
    setTimeout(() => {
        const avatar = document.querySelector('.profile-avatar');
        avatar.style.transform = 'scale(1.1)';
        setTimeout(() => {
            avatar.style.transform = 'scale(1)';
        }, 300);
    }, 1000);
});
</script>

<?php include('../includes/footer.php'); ?>