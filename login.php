<?php
// Start output buffering
ob_start();

session_start();
include('includes/db.php');
include('includes/header.php');

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si c'est une connexion sociale
    if (isset($_POST['social_login'])) {
        $social_id = $_POST['social_id'];
        $social_type = $_POST['social_type'];
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        
        // Vérifier si l'utilisateur existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE social_id = ? AND social_type = ?");
        $stmt->bind_param("ss", $social_id, $social_type);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            // Créer un nouvel utilisateur si nécessaire
            $stmt = $conn->prepare("INSERT INTO utilisateur (nom, email, social_id, social_type, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssss", $nom, $email, $social_id, $social_type);
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                
                // Récupérer le nouvel utilisateur
                $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
            } else {
                $erreur = "Erreur lors de la création du compte.";
            }
        } else {
            $user = $result->fetch_assoc();
        }
        
        if (!$erreur) {
            // Connecter l'utilisateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
            exit;
        }
    } else {
        // Connexion normale
        $email = $_POST['email'];
        $motdepasse = $_POST['motdepasse'];
        $rememberMe = isset($_POST['rememberMe']) ? 1 : 0;

        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($motdepasse, $user['motdepasse'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];

                // Si "Se souvenir de moi" est coché
                if ($rememberMe) {
                    setcookie('user_email', $email, time() + 30 * 24 * 3600, '/', '', true, true);
                    setcookie('user_token', password_hash($user['motdepasse'], PASSWORD_DEFAULT), time() + 30 * 24 * 3600, '/', '', true, true);
                }

                if ($user['role'] == 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $erreur = "Mot de passe incorrect.";
            }
        } else {
            $erreur = "Email non trouvé.";
        }
    }
}
?>

<!-- Login Page - Clean Design -->
<section class="login-section">
    <div class="container">
        <div class="login-wrapper">
            <div class="login-card">
                <h1 class="login-title">Connexion</h1>
                <p class="login-subtitle">Accédez à votre compte</p>

                <?php if ($erreur): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $erreur; ?>
                </div>
                <?php endif; ?>

                <form class="login-form" method="post">
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="motdepasse">Mot de passe :</label>
                        <div class="password-input">
                            <input type="password" id="motdepasse" name="motdepasse" required>
                            <button type="button" class="toggle-password" aria-label="Afficher le mot de passe">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="forgot-password">
                            <a href="forgot-password.php">Mot de passe oublié ?</a>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe"
                            <?php echo isset($_COOKIE['user_email']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="login-button">Se connecter</button>

                    <div class="divider">
                        <div class="divider-line"></div>
                        <div class="divider-text">OU</div>
                        <div class="divider-line"></div>
                    </div>

                    <div class="social-login">
                        <div class="social-buttons">
                            <button type="button" class="social-btn google" id="googleLogin">
                                <i class="fab fa-google"></i> Google
                            </button>
                            <button type="button" class="social-btn facebook" id="facebookLogin">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </button>
                            <button type="button" class="social-btn twitter" id="twitterLogin">
                                <i class="fab fa-twitter"></i> Twitter
                            </button>
                        </div>
                    </div>

                    <div class="register-link">
                        Pas encore de compte ? <a href="register.php">Créer un compte</a>
                    </div>
                </form>

                <!-- Formulaire caché pour la connexion sociale -->
                <form id="socialLoginForm" method="post" style="display:none;">
                    <input type="hidden" name="social_login" value="1">
                    <input type="hidden" name="social_id" id="social_id">
                    <input type="hidden" name="social_type" id="social_type">
                    <input type="hidden" name="email" id="social_email">
                    <input type="hidden" name="nom" id="social_name">
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.login-section {
    display: flex;
    min-height: 80vh;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background-color: #f8f9fa;
}

.container {
    width: 100%;
    max-width: 1200px;
    padding: 0 15px;
    margin: 0 auto;
}

.login-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
}

.login-card {
    background: white;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    width: 100%;
    max-width: 450px;
}

.login-title {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    text-align: center;
    font-weight: 600;
}

.login-subtitle {
    color: #7f8c8d;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 1rem;
}

.alert-error {
    color: #dc3545;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 0.75rem 1.25rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #495057;
}

.form-group input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ced4da;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-group input:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.password-input {
    position: relative;
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
    padding: 0.5rem;
}

.forgot-password {
    text-align: right;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.forgot-password a {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.2s;
}

.forgot-password a:hover {
    color: #0056b3;
    text-decoration: underline;
}

.form-check {
    margin: 1.5rem 0;
    display: flex;
    align-items: center;
}

.form-check-input {
    margin-right: 0.75rem;
    width: 1.1em;
    height: 1.1em;
}

.login-button {
    width: 100%;
    padding: 0.75rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.login-button:hover {
    background-color: #0069d9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

.divider {
    display: flex;
    align-items: center;
    margin: 1.5rem 0;
}

.divider-line {
    flex: 1;
    height: 1px;
    background-color: #dee2e6;
}

.divider-text {
    padding: 0 1rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.social-login {
    margin-bottom: 1.5rem;
}

.social-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.social-btn {
    width: 100%;
    padding: 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: 1px solid transparent;
}

.social-btn.google {
    background-color: #fff;
    color: #db4437;
    border-color: #db4437;
}

.social-btn.facebook {
    background-color: #fff;
    color: #4267B2;
    border-color: #4267B2;
}

.social-btn.twitter {
    background-color: #fff;
    color: #1DA1F2;
    border-color: #1DA1F2;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.register-link {
    text-align: center;
    color: #6c757d;
    font-size: 0.95rem;
}

.register-link a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.register-link a:hover {
    text-decoration: underline;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('motdepasse');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Form validation
    const form = document.querySelector('.login-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Check email
            const email = document.getElementById('email');
            if (!email.value || !email.value.includes('@')) {
                isValid = false;
                email.style.borderColor = '#dc3545';
            } else {
                email.style.borderColor = '#ced4da';
            }

            // Check password
            const password = document.getElementById('motdepasse');
            if (!password.value) {
                isValid = false;
                password.style.borderColor = '#dc3545';
            } else {
                password.style.borderColor = '#ced4da';
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Social login handlers
    const googleLogin = document.getElementById('googleLogin');
    const facebookLogin = document.getElementById('facebookLogin');
    const twitterLogin = document.getElementById('twitterLogin');
    const socialForm = document.getElementById('socialLoginForm');

    // Google Login handler
    if (googleLogin) {
        googleLogin.addEventListener('click', function() {
            // Ici vous devrez implémenter le flux OAuth pour Google
            alert("Connexion Google - À implémenter");
        });
    }

    // Facebook Login handler
    if (facebookLogin) {
        facebookLogin.addEventListener('click', function() {
            // Ici vous devrez implémenter le flux OAuth pour Facebook
            alert("Connexion Facebook - À implémenter");
        });
    }

    // Twitter Login handler
    if (twitterLogin) {
        twitterLogin.addEventListener('click', function() {
            // Ici vous devrez implémenter le flux OAuth pour Twitter
            alert("Connexion Twitter - À implémenter");
        });
    }
});
</script>

<?php include('includes/footer.php'); ?>