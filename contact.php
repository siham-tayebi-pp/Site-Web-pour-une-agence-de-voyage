<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

include('includes/db.php');
include('includes/header.php');

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    $contenu = $_POST['contenu'];

    $stmt = $conn->prepare("INSERT INTO message_contact (nom, email, sujet, contenu, date_envoi, statut, utilisateur_id) 
                           VALUES (?, ?, ?, ?, NOW(), 'non lu', ?)");
    $stmt->bind_param("ssssi", $nom, $email, $sujet, $contenu, $user_id);

    if ($stmt->execute()) {
        $success = "Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.";
    } else {
        $error = "Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer.";
    }
}
?>

<!-- Page de Contact Modernisée -->
<section class="contact-section bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-header text-center mb-5">
                    <h2 class="display-5 fw-bold text-primary">Contactez Notre Équipe</h2>
                    <p class="lead text-muted">Nous sommes à votre écoute pour toute question ou demande d'information
                    </p>
                    <div class="divider mx-auto bg-primary"></div>
                </div>
            </div>
        </div>

        <!-- Messages d'alerte -->
        <?php if ($success): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php elseif ($error): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Informations de contact -->
            <div class="col-lg-4">
                <div class="contact-info-card bg-white p-4 rounded-3 shadow-sm h-100">
                    <div class="contact-item mb-4">
                        <div class="icon-box bg-primary text-white rounded-circle mb-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Notre Adresse</h5>
                        <p class="text-muted mb-0">123 Avenue Mohammed VI<br>Casablanca, Maroc</p>
                    </div>

                    <div class="contact-item mb-4">
                        <div class="icon-box bg-primary text-white rounded-circle mb-3">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Téléphone</h5>
                        <p class="text-muted mb-0">+212 522 123 456</p>
                        <p class="text-muted mb-0">+212 600 123 456</p>
                    </div>

                    <div class="contact-item">
                        <div class="icon-box bg-primary text-white rounded-circle mb-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Email</h5>
                        <p class="text-muted mb-0">contact@voyagesmaroc.com</p>
                        <p class="text-muted mb-0">support@voyagesmaroc.com</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="col-lg-8">
                <div class="contact-form-card bg-white p-4 rounded-3 shadow-sm">
                    <form id="contactForm" method="post" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom"
                                        required>
                                    <label for="nom"><i class="fas fa-user me-2"></i>Nom complet</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir votre nom.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Votre email" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Adresse email</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir une adresse email valide.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Sujet"
                                        required>
                                    <label for="sujet"><i class="fas fa-tag me-2"></i>Sujet du message</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir le sujet de votre message.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="contenu" name="contenu"
                                        placeholder="Votre message" style="height: 150px" required></textarea>
                                    <label for="contenu"><i class="fas fa-comment-dots me-2"></i>Votre message</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir votre message.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg py-3">
                                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Carte de localisation -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="location-card bg-white rounded-3 overflow-hidden shadow-sm">
                    <div class="map-container ratio ratio-16x9">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3323.349834678535!2d-7.632468924025269!3d33.59624297333642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7d3731f922c01%3A0x5e46c1e1fc83142d!2sCasablanca%2C%20Morocco!5e0!3m2!1sen!2sus!4v1712345678901!5m2!1sen!2sus"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Styles personnalisés */
.contact-section {
    background-color: #f8f9fa;
}

.section-header .divider {
    width: 80px;
    height: 3px;
    margin: 20px auto;
}

.contact-info-card {
    border-left: 4px solid #0d6efd;
}

.icon-box {
    width: 50px;
    height: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.contact-form-card {
    border-top: 4px solid #0d6efd;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background-color: #0d6efd;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}

.location-card {
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.map-container iframe {
    filter: grayscale(20%) contrast(90%);
    transition: filter 0.3s ease;
}

.map-container:hover iframe {
    filter: grayscale(0%) contrast(100%);
}
</style>

<script>
// Validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    }, false);

    // Animation des labels
    const floatingLabels = document.querySelectorAll('.form-floating label');
    floatingLabels.forEach(label => {
        label.style.transition = 'all 0.3s ease';
    });
});
</script>

<?php include('includes/footer.php'); ?>