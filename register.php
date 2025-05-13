<?php
if(basename($_SERVER['PHP_SELF']) == 'register.php'):
    include('includes/db.php');
    include('includes/header.php');
    
    $erreur = '';
    $success = '';
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $pays = $_POST['pays'] ?? '';
        $motdepasse = password_hash($_POST['motdepasse'] ?? '', PASSWORD_DEFAULT);
        
        $check = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();
        
        if($result->num_rows > 0) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $stmt = $conn->prepare("INSERT INTO utilisateur(nom, prenom, email, telephone, pays, motdepasse) VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nom, $prenom, $email, $telephone, $pays, $motdepasse);
            
            if($stmt->execute()) {
                $success = "Compte créé avec succès. <a href='login.php'>Se connecter</a>";
            } else {
                $erreur = "Erreur lors de la création du compte";
            }
        }
    }
?>
<section class="register-page">
    <div class="container">
        <div class="register-wrapper">
            <div class="register-card">
                <div class="register-header">
                    <h1>Créer un compte</h1>
                    <p>Rejoignez Voyages Maroc</p>
                </div>

                <?php if($erreur): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $erreur; ?>
                </div>
                <?php endif; ?>

                <?php if($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
                <?php else: ?>

                <form action="" method="post" class="register-form" id="registerForm">
                    <!-- Nom et Prénom -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone">
                    </div>

                    <!-- Pays -->
                    <div class="form-group">
                        <label for="pays">Pays</label>
                        <select id="pays" name="pays" required>
                            <option value="" selected disabled>Sélectionnez votre pays</option>
                            <option value="Maroc">Maroc</option>
                            <option value="France">France</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Suisse">Suisse</option>
                            <option value="Canada">Canada</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <!-- Mot de passe -->
                    <!-- ... (le reste du code PHP reste inchangé jusqu'au formulaire) ... -->

                    <div class="form-group">
                        <label for="motdepasse">Mot de passe :</label>
                        <div class="password-input">
                            <input type="password" id="motdepasse" name="motdepasse" required
                                oninput="checkPasswordStrength()">
                            <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Ajout de l'indicateur de force du mot de passe -->
                        <div class="password-strength mt-2">
                            <div class="progress" style="height: 5px;">
                                <div id="password-strength-meter" class="progress-bar" role="progressbar"
                                    style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small id="passwordStrengthText" class="form-text">Force du mot de passe</small>
                        </div>
                        <div class="forgot-password">
                            <a href="forgot-password.php">Mot de passe oublié ?</a>
                        </div>
                    </div>

                    <!-- ... (le reste du formulaire reste inchangé) ... -->

                    <style>
                    /* Ajoutez ces styles à votre section CSS existante */
                    .password-strength {
                        margin-top: 0.5rem;
                    }

                    .progress {
                        background-color: #e9ecef;
                        border-radius: 0.25rem;
                        margin-bottom: 0.25rem;
                    }

                    .progress-bar {
                        transition: width 0.3s ease;
                    }

                    .strength-weak {
                        background-color: #dc3545;
                    }

                    .strength-medium {
                        background-color: #ffc107;
                    }

                    .strength-strong {
                        background-color: #28a745;
                    }

                    .form-text {
                        font-size: 0.85rem;
                        color: #6c757d;
                    }

                    .text-danger {
                        color: #dc3545 !important;
                    }

                    .text-warning {
                        color: #ffc107 !important;
                    }

                    .text-success {
                        color: #28a745 !important;
                    }
                    </style>

                    <script>
                    // Ajoutez cette fonction à votre script existant
                    function checkPasswordStrength() {
                        const password = document.getElementById('motdepasse');
                        const meter = document.getElementById('password-strength-meter');
                        const strengthText = document.getElementById('passwordStrengthText');

                        if (!password || !meter || !strengthText) return;

                        const value = password.value;
                        let strength = 0;

                        // Check length
                        if (value.length >= 8) strength += 25;

                        // Check for lowercase and uppercase letters
                        if (value.match(/[a-z]+/) && value.match(/[A-Z]+/)) strength += 25;

                        // Check for numbers
                        if (value.match(/[0-9]+/)) strength += 25;

                        // Check for special characters
                        if (value.match(/[^a-zA-Z0-9]+/)) strength += 25;

                        // Update the meter
                        meter.style.width = strength + '%';
                        meter.setAttribute('aria-valuenow', strength);

                        // Remove old classes
                        meter.classList.remove('strength-weak', 'strength-medium', 'strength-strong');

                        // Add appropriate class and text
                        if (strength < 50) {
                            meter.classList.add('strength-weak');
                            strengthText.textContent = 'Faible';
                            strengthText.className = 'form-text text-danger';
                        } else if (strength < 75) {
                            meter.classList.add('strength-medium');
                            strengthText.textContent = 'Moyen';
                            strengthText.className = 'form-text text-warning';
                        } else {
                            meter.classList.add('strength-strong');
                            strengthText.textContent = 'Fort';
                            strengthText.className = 'form-text text-success';
                        }
                    }

                    // Modifiez votre event listener DOMContentLoaded pour inclure ceci :
                    document.addEventListener('DOMContentLoaded', function() {
                        // ... (votre code existant)

                        // Ajoutez l'écouteur d'événement pour le champ mot de passe
                        const passwordInput = document.getElementById('motdepasse');
                        if (passwordInput) {
                            passwordInput.addEventListener('input', checkPasswordStrength);
                        }

                        // ... (le reste de votre code existant)
                    });
                    </script>

                    <!-- ... (le reste du code reste inchangé) ... -->

                    <!-- Conditions -->
                    <div class="form-check">
                        <input type="checkbox" id="termsCheck" name="termsCheck" required>
                        <label for="termsCheck">J'accepte les <a href="#">conditions</a> et la <a href="#">politique de
                                confidentialité</a></label>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button type="submit" class="register-button">S'inscrire</button>

                    <!-- Lien de connexion -->
                    <div class="login-link">
                        Déjà un compte? <a href="login.php">Se connecter</a>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.register-page {
    padding: 2rem 0;
    background-color: #f8f9fa;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.register-wrapper {
    display: flex;
    justify-content: center;
}

.register-card {
    background: white;
    padding: 2.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
}

.register-header {
    text-align: center;
    margin-bottom: 2rem;
}

.register-header h1 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.register-header p {
    color: #6c757d;
}

.alert {
    padding: 0.75rem 1.25rem;
    border-radius: 0.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-error {
    background-color: #f8d7da;
    color: #dc3545;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-row .form-group {
    flex: 1;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #495057;
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    font-size: 1rem;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.password-input {
    position: relative;
}

.password-input input {
    padding-right: 2.5rem;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
}

.password-hint {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.form-check {
    margin: 1.5rem 0;
    display: flex;
    align-items: center;
}

.form-check input {
    margin-right: 0.5rem;
}

.form-check label {
    color: #495057;
    font-size: 0.9rem;
}

.form-check a {
    color: #007bff;
    text-decoration: none;
}

.register-button {
    width: 100%;
    padding: 0.75rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 0.25rem;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    margin-top: 1rem;
    transition: background-color 0.15s;
}

.register-button:hover {
    background-color: #0069d9;
}

.login-link {
    text-align: center;
    margin-top: 1.5rem;
    color: #6c757d;
}

.login-link a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('motdepasse');
            if (passwordInput) {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    }

    // Form validation
    const form = document.getElementById('registerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Check required fields
            const requiredFields = ['nom', 'prenom', 'email', 'pays', 'motdepasse'];
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field && !field.value) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else if (field) {
                    field.style.borderColor = '#ced4da';
                }
            });

            // Check terms checkbox
            const termsCheck = document.getElementById('termsCheck');
            if (termsCheck && !termsCheck.checked) {
                isValid = false;
                termsCheck.style.outline = '1px solid #dc3545';
            } else if (termsCheck) {
                termsCheck.style.outline = 'none';
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
</script>

<?php 
include('includes/footer.php');
endif;
?>