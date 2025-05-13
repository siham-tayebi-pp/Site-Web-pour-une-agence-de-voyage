<?php
session_start();
include('includes/header.php');
?>

<!-- Hero Section À Propos -->
<section class="about-hero bg-dark text-white position-relative overflow-hidden">
    <div class="container py-5">
        <div class="row min-vh-50 align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Notre Histoire</h1>
                <p class="lead mb-5">Découvrez la passion qui nous anime depuis 2024</p>
                <a href="#notre-mission" class="btn btn-primary btn-lg px-5 py-3 scroll-link">
                    <i class="fas fa-arrow-down me-2"></i> Explorer
                </a>
            </div>
        </div>
    </div>
    <div class="hero-overlay"></div>
</section>

<!-- Section Mission -->
<section id="notre-mission" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="pe-lg-5">
                    <h2 class="display-5 fw-bold mb-4 text-gradient-primary">Notre Mission</h2>
                    <div class="mission-item mb-4 animate-on-scroll">
                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-4">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div>
                                <h3 class="h4">Voyages Authentiques</h3>
                                <p class="mb-0">Découvrez le vrai Maroc à travers des expériences locales uniques et
                                    hors des sentiers battus.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mission-item mb-4 animate-on-scroll">
                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-success text-white rounded-circle me-4">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div>
                                <h3 class="h4">Service Personnalisé</h3>
                                <p class="mb-0">Un accompagnement sur mesure pour chaque voyageur, avant, pendant et
                                    après le voyage.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mission-item mb-4 animate-on-scroll">
                        <div class="d-flex align-items-start mb-3">
                            <div class="icon-box bg-info text-white rounded-circle me-4">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div>
                                <h3 class="h4">Tourisme Responsable</h3>
                                <p class="mb-0">Engagés pour un tourisme durable qui respecte les populations et
                                    l'environnement.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-lg-2 order-1">
                <div class="about-image-container animate-on-scroll">
                    <img src="images/team.jpeg" class="img-fluid rounded-4 shadow-lg" alt="Notre mission"
                        loading="lazy">
                    <div class="image-badge bg-primary text-white">
                        <span class="d-block">+500</span>
                        <small>Voyageurs satisfaits</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Équipe -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 text-gradient-primary">Notre Équipe</h2>
            <p class="lead text-muted">Des experts passionnés à votre service</p>
            <div class="divider mx-auto bg-primary"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
      $team = [
        ['name' => 'Karim B.', 'role' => 'Fondateur', 'photo' => 'team1.jpg', 'bio' => 'Expert du tourisme depuis 15 ans'],
        ['name' => 'Amina L.', 'role' => 'Guide', 'photo' => 'team2.jpg', 'bio' => 'Spécialiste des circuits culturels'],
        ['name' => 'Youssef M.', 'role' => 'Logistique', 'photo' => 'team3.jpg', 'bio' => 'Organisateur hors pair']
      ];
      
      foreach ($team as $member) {
        echo '<div class="col-lg-4 col-md-6">
                <div class="team-card card border-0 shadow-sm h-100 overflow-hidden animate-on-scroll">
                  <div class="team-photo">
                    <img src="images/team/'.$member['photo'].'" class="card-img-top" alt="'.$member['name'].'" loading="lazy">
                  </div>
                  <div class="card-body text-center">
                    <h3 class="h5">'.$member['name'].'</h3>
                    <p class="text-primary fw-bold">'.$member['role'].'</p>
                    <p class="text-muted">'.$member['bio'].'</p>
                    <div class="social-links">
                      <a href="#" class="text-muted me-2"><i class="fab fa-facebook"></i></a>
                      <a href="#" class="text-muted me-2"><i class="fab fa-twitter"></i></a>
                      <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                    </div>
                  </div>
                </div>
              </div>';
      }
      ?>
        </div>
    </div>
</section>

<!-- Section Valeurs -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 text-gradient-primary">Nos Valeurs</h2>
            <p class="lead text-muted">Ce qui nous guide au quotidien</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 animate-on-scroll">
                <div class="value-card text-center p-4 h-100">
                    <div class="value-icon bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="h4">Confiance</h3>
                    <p>Transparence et honnêteté dans toutes nos relations</p>
                </div>
            </div>

            <div class="col-md-4 animate-on-scroll">
                <div class="value-card text-center p-4 h-100">
                    <div class="value-icon bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-4">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="h4">Innovation</h3>
                    <p>Des expériences uniques et créatives</p>
                </div>
            </div>

            <div class="col-md-4 animate-on-scroll">
                <div class="value-card text-center p-4 h-100">
                    <div class="value-icon bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="h4">Passion</h3>
                    <p>Notre moteur pour vous offrir le meilleur</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section CTA -->
<section class="py-5 bg-dark text-white position-relative overflow-hidden">
    <div class="container position-relative z-index-1">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-4">Prêt à vivre l'aventure ?</h2>
                <p class="lead mb-5">Contactez-nous pour créer le voyage de vos rêves</p>
                <a href="contact.php" class="btn btn-light btn-lg px-5 py-3 fw-bold me-3">Nous contacter</a>
                <a href="voyages.php" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold">Voir nos voyages</a>
            </div>
        </div>
    </div>
    <div class="bg-pattern"></div>
</section>

<style>
/* Styles personnalisés */
.about-hero {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/about-hero.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    padding-top: 100px;
}

.hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: linear-gradient(to top, var(--bs-light), transparent);
}

.text-gradient-primary {
    background: linear-gradient(90deg, #007bff, #00a8ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: inline-block;
}

.icon-box {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.about-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
}

.image-badge {
    position: absolute;
    bottom: -20px;
    right: -20px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    z-index: 2;
}

.image-badge span {
    font-size: 2rem;
    line-height: 1;
}

.team-card {
    transition: transform 0.3s ease;
}

.team-card:hover {
    transform: translateY(-10px);
}

.team-photo {
    overflow: hidden;
    height: 300px;
}

.team-photo img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.team-card:hover .team-photo img {
    transform: scale(1.1);
}

.value-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.value-card {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0.05;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Animations */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.animate-on-scroll.animated {
    opacity: 1;
    transform: translateY(0);
}

.mission-item:nth-child(1) {
    transition-delay: 0.1s;
}

.mission-item:nth-child(2) {
    transition-delay: 0.3s;
}

.mission-item:nth-child(3) {
    transition-delay: 0.5s;
}

@media (max-width: 768px) {
    .about-hero {
        background-attachment: scroll;
    }

    .image-badge {
        width: 80px;
        height: 80px;
        right: -10px;
        bottom: -10px;
    }

    .image-badge span {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    // Animation des éléments
    const animateElements = () => {
        const elements = document.querySelectorAll('.animate-on-scroll');
        elements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const isVisible = (rect.top <= window.innerHeight * 0.8);

            if (isVisible) {
                el.classList.add('animated');
            }
        });
    };

    // Initial check
    animateElements();

    // Check on scroll
    window.addEventListener('scroll', animateElements);

    // Smooth scroll pour les liens
    document.querySelectorAll('.scroll-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            window.scrollTo({
                top: target.offsetTop - 100,
                behavior: 'smooth'
            });
        });
    });
});
</script>

<?php include('includes/footer.php'); ?>