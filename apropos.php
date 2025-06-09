<?php
session_start();
include('includes/header.php');
?>

<!-- Hero Section À Propos -->
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-particles"></div>
    <div class="container py-5">
        <div class="row min-vh-50 align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4 hero-title">Notre Histoire</h1>
                <p class="lead mb-5 hero-subtitle">Découvrez la passion qui nous anime depuis 2024</p>
                <a href="#notre-mission" class="btn btn-primary btn-lg px-5 py-3 scroll-link hero-btn">
                    <i class="fas fa-arrow-down me-2"></i> Explorer
                </a>
            </div>
        </div>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-wave">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path
                d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                opacity=".25" fill="currentColor"></path>
            <path
                d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                opacity=".5" fill="currentColor"></path>
            <path
                d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                fill="currentColor"></path>
        </svg>
    </div>
</section>

<!-- Section Mission -->
<section id="notre-mission" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="pe-lg-5">
                    <h2 class="display-5 fw-bold mb-4 text-gradient-blue">Notre Mission</h2>

                    <div class="mission-item mb-4 animate-on-scroll" data-delay="100">
                        <div class="mission-card p-4 rounded-4 h-100">
                            <div class="d-flex align-items-start mb-3">
                                <div class="icon-box bg-primary text-white rounded-circle me-4 icon-hover">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div>
                                    <h3 class="h4 mb-3">Voyages Authentiques</h3>
                                    <p class="mb-0 text-muted">Découvrez le vrai Maroc à travers des expériences locales
                                        uniques et hors des sentiers battus.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mission-item mb-4 animate-on-scroll" data-delay="300">
                        <div class="mission-card p-4 rounded-4 h-100">
                            <div class="d-flex align-items-start mb-3">
                                <div class="icon-box bg-azure text-white rounded-circle me-4 icon-hover">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <h3 class="h4 mb-3">Service Personnalisé</h3>
                                    <p class="mb-0 text-muted">Un accompagnement sur mesure pour chaque voyageur, avant,
                                        pendant et après le voyage.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mission-item mb-4 animate-on-scroll" data-delay="500">
                        <div class="mission-card p-4 rounded-4 h-100">
                            <div class="d-flex align-items-start mb-3">
                                <div class="icon-box bg-sky text-white rounded-circle me-4 icon-hover">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div>
                                    <h3 class="h4 mb-3">Tourisme Responsable</h3>
                                    <p class="mb-0 text-muted">Engagés pour un tourisme durable qui respecte les
                                        populations et l'environnement.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-lg-2 order-1">
                <div class="about-image-container animate-on-scroll">
                    <div class="image-wrapper">
                        <img src="images/team.jpeg" class="img-fluid rounded-4 shadow-lg main-image" alt="Notre mission"
                            loading="lazy">
                        <div class="image-overlay"></div>
                    </div>
                    <div class="image-badge bg-primary text-white">
                        <span class="counter" data-target="500">100K</span>
                        <small>Voyageurs satisfaits</small>
                    </div>
                    <div class="floating-elements">
                        <div class="floating-element floating-element-1"></div>
                        <div class="floating-element floating-element-2"></div>
                        <div class="floating-element floating-element-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Équipe -->
<section class="py-5 bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 text-gradient-blue">Notre Équipe</h2>
            <p class="lead text-muted">Des experts passionnés à votre service</p>
            <div class="divider mx-auto bg-primary"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            $team = [
                ['name' => 'Aya T.', 'role' => 'Fondateur', 'photo' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=80', 'bio' => 'Expert du tourisme depuis 15 ans', 'skills' => ['Leadership', 'Stratégie', 'Innovation']],
                ['name' => 'Karima B.', 'role' => 'Guide', 'photo' => 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=800&q=80', 'bio' => 'Spécialiste des circuits culturels', 'skills' => ['Culture', 'Histoire', 'Langues']],
                ['name' => 'Ali T.', 'role' => 'Logistique', 'photo' => 'https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&w=800', 'bio' => 'Organisateur hors pair', 'skills' => ['Organisation', 'Coordination', 'Efficacité']]
            ];
            
            foreach ($team as $index => $member) {
                echo '<div class="col-lg-4 col-md-6">
                        <div class="team-card card border-0 shadow-sm h-100 overflow-hidden animate-on-scroll" data-delay="'.($index * 200).'">
                            <div class="team-photo position-relative">
                                <img src="'.$member['photo'].'" class="card-img-top" alt="'.$member['name'].'" loading="lazy">
                                <div class="team-overlay">
                                    <div class="team-skills">';
                foreach($member['skills'] as $skill) {
                    echo '<span class="skill-badge">'.$skill.'</span>';
                }
                echo '      </div>
                                </div>
                            </div>
                            <div class="card-body text-center p-4">
                                <h3 class="h5 mb-2">'.$member['name'].'</h3>
                                <p class="text-primary fw-bold mb-2">'.$member['role'].'</p>
                                <p class="text-muted mb-3">'.$member['bio'].'</p>
                                <div class="social-links">
                                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
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
<section class="py-5 bg-white position-relative">
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3 text-gradient-primary">Nos Valeurs</h2>
            <p class="lead text-muted">Ce qui nous guide au quotidien</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 animate-on-scroll" data-delay="100">
                <div class="value-card text-center p-5 h-100 rounded-4">
                    <div class="value-icon bg-success bg-opacity-10 text-primary rounded-circle mx-auto mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="h4 mb-3">Confiance</h3>
                    <p class="text-muted">Transparence et honnêteté dans toutes nos relations</p>
                    <div class="value-progress">
                        <div class="progress-bar" data-width="95"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 animate-on-scroll" data-delay="300">
                <div class="value-card text-center p-5 h-100 rounded-4">
                    <div class="value-icon bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-4">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="h4 mb-3">Innovation</h3>
                    <p class="text-muted">Des expériences uniques et créatives</p>
                    <div class="value-progress">
                        <div class="progress-bar" data-width="90"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 animate-on-scroll" data-delay="500">
                <div class="value-card text-center p-5 h-100 rounded-4">
                    <div class="value-icon bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="h4 mb-3">Passion</h3>
                    <p class="text-muted">Notre moteur pour vous offrir le meilleur</p>
                    <div class="value-progress">
                        <div class="progress-bar" data-width="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="background-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
</section>

<!-- Section Statistiques -->
<section class="py-5 bg-gradient-blue text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-6">
                <div class="stat-item animate-on-scroll" data-delay="100">
                    <div class="stat-number">
                        <span class="counter" data-target="500">0</span>+
                    </div>
                    <div class="stat-label">Voyageurs Heureux</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item animate-on-scroll" data-delay="200">
                    <div class="stat-number">
                        <span class="counter" data-target="50">0</span>+
                    </div>
                    <div class="stat-label">Destinations</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item animate-on-scroll" data-delay="300">
                    <div class="stat-number">
                        <span class="counter" data-target="15">0</span>
                    </div>
                    <div class="stat-label">Années d'Expérience</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item animate-on-scroll" data-delay="400">
                    <div class="stat-number">
                        <span class="counter" data-target="98">0</span>%
                    </div>
                    <div class="stat-label">Satisfaction Client</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section CTA -->
<section class="py-5 bg-gradient-deep-blue text-white position-relative overflow-hidden">
    <div class="container position-relative z-index-1">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-4 cta-title">Prêt à vivre l'aventure ?</h2>
                <p class="lead mb-5 cta-subtitle">Contactez-nous pour créer le voyage de vos rêves</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-light btn-lg px-5 py-3 fw-bold me-3 cta-btn-primary">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                    <a href="voyages.php" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold cta-btn-secondary">
                        <i class="fas fa-map me-2"></i>Voir nos voyages
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="cta-particles"></div>
</section>

<style>
/* Variables CSS - Palette de bleus et couleurs harmonieuses */
:root {
    /* Nuances de bleu principales */
    --primary: #0d6efd;
    --primary-dark: #0a58ca;
    --primary-light: #6ea8fe;
    --primary-lighter: #cfe2ff;

    /* Nuances de bleu complémentaires */
    --azure: #0dcaf0;
    --azure-dark: #0aa2c0;
    --azure-light: #6edff6;

    --sky: #0ea5e9;
    --sky-dark: #0284c7;
    --sky-light: #7dd3fc;

    --navy: #1e40af;
    --navy-dark: #1e3a8a;
    --navy-light: #3b82f6;

    /* Couleurs neutres */
    --light: #f8f9fa;
    --light-blue: #f0f7ff;
    --dark: #212529;
    --muted: #6c757d;

    /* Gradients */
    --gradient-blue: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    --gradient-deep-blue: linear-gradient(135deg, #1e40af 0%, #0d6efd 100%);
    --gradient-light-blue: linear-gradient(135deg, #cfe2ff 0%, #f0f7ff 100%);

    /* Ombres */
    --shadow-sm: 0 2px 8px rgba(13, 110, 253, 0.1);
    --shadow-md: 0 4px 12px rgba(13, 110, 253, 0.15);
    --shadow-lg: 0 8px 24px rgba(13, 110, 253, 0.2);

    /* Transitions */
    --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    --transition-bounce: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Styles généraux améliorés */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    line-height: 1.7;
    color: var(--dark);
}

/* Hero Section améliorée */
.hero-section {
    background: var(--gradient-blue);
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    color: white;
}

.hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.hero-particles::before,
.hero-particles::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.hero-particles::before {
    top: 20%;
    left: 20%;
    animation-delay: 0s;
}

.hero-particles::after {
    top: 60%;
    right: 20%;
    animation-delay: 3s;
}

.hero-title {
    animation: slideInUp 1s ease-out;
    font-size: clamp(2.5rem, 5vw, 4rem);
}

.hero-subtitle {
    animation: slideInUp 1s ease-out 0.3s both;
    opacity: 0.9;
}

.hero-btn {
    animation: slideInUp 1s ease-out 0.6s both;
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    background: white;
    color: var(--primary);
    border: none;
}

.hero-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.2), transparent);
    transition: left 0.5s;
}

.hero-btn:hover {
    background: white;
    color: var(--primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.hero-btn:hover::before {
    left: 100%;
}

.hero-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    color: white;
}

.hero-wave svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 60px;
}

/* Mission Section améliorée */
.mission-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(13, 110, 253, 0.1);
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.mission-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.05), transparent);
    transition: left 0.8s;
}

.mission-card:hover::before {
    left: 100%;
}

.mission-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}

.icon-hover {
    transition: var(--transition-bounce);
}

.icon-hover:hover {
    transform: scale(1.1) rotate(5deg);
}

.bg-primary {
    background-color: var(--primary) !important;
}

.bg-azure {
    background-color: var(--azure) !important;
}

.bg-sky {
    background-color: var(--sky) !important;
}

.bg-navy {
    background-color: var(--navy) !important;
}

.text-primary {
    color: var(--primary) !important;
}

.text-azure {
    color: var(--azure) !important;
}

.text-navy {
    color: var(--navy) !important;
}

/* Image container améliorée */
.about-image-container {
    position: relative;
}

.image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
}

.main-image {
    transition: var(--transition-smooth);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(13, 110, 253, 0.2), rgba(13, 202, 240, 0.2));
    opacity: 0;
    transition: var(--transition-smooth);
}

.image-wrapper:hover .image-overlay {
    opacity: 1;
}

.image-wrapper:hover .main-image {
    transform: scale(1.05);
}

.image-badge {
    position: absolute;
    bottom: -20px;
    right: 30px;
    background: var(--primary);
    color: white;
    padding: 15px 25px;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    z-index: 2;
    text-align: center;
}

.image-badge .counter {
    font-size: 2rem;
    font-weight: 700;
    display: block;
    line-height: 1;
}

.image-badge small {
    font-size: 0.9rem;
    opacity: 0.9;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.floating-element {
    position: absolute;
    background: rgba(13, 110, 253, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.floating-element-1 {
    width: 60px;
    height: 60px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    background: rgba(13, 110, 253, 0.1);
}

.floating-element-2 {
    width: 40px;
    height: 40px;
    top: 70%;
    left: 80%;
    animation-delay: 2s;
    background: rgba(13, 202, 240, 0.1);
}

.floating-element-3 {
    width: 30px;
    height: 30px;
    top: 40%;
    left: 90%;
    animation-delay: 4s;
    background: rgba(30, 64, 175, 0.1);
}

/* Team cards améliorées */
.bg-light-blue {
    background-color: var(--light-blue);
}

.team-card {
    transition: var(--transition-smooth);
    border-radius: 20px !important;
    overflow: hidden;
}

.team-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: var(--shadow-lg);
}

.team-photo {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.team-photo img {
    transition: var(--transition-smooth);
    object-fit: cover;
    width: 100%;
    height: 100%;
}

.team-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 60%, rgba(13, 110, 253, 0.8));
    display: flex;
    align-items: flex-end;
    padding: 20px;
    opacity: 0;
    transition: var(--transition-smooth);
}

.team-card:hover .team-overlay {
    opacity: 1;
}

.team-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.skill-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    backdrop-filter: blur(10px);
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: var(--light);
    color: var(--primary);
    border-radius: 50%;
    text-decoration: none;
    transition: var(--transition-bounce);
}

.social-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-3px) scale(1.1);
}

/* Value cards améliorées */
.value-card {
    background: white;
    transition: var(--transition-smooth);
    border: 1px solid rgba(13, 110, 253, 0.1);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.value-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--gradient-blue);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.value-card:hover::before {
    transform: scaleX(1);
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-md);
}

.value-progress {
    width: 100%;
    height: 4px;
    background: rgba(13, 110, 253, 0.1);
    border-radius: 2px;
    overflow: hidden;
    margin-top: 15px;
}

.progress-bar {
    height: 100%;
    background: var(--gradient-blue);
    border-radius: 2px;
    width: 0;
    transition: width 2s ease-in-out;
}

.progress-bar-azure {
    background: linear-gradient(135deg, var(--azure) 0%, var(--azure-light) 100%);
}

.progress-bar-navy {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
}

/* Statistiques */
.bg-gradient-blue {
    background: var(--gradient-blue);
}

.stat-item {
    padding: 20px;
    transition: var(--transition-smooth);
}

.stat-item:hover {
    transform: scale(1.05);
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: white;
    margin-bottom: 10px;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* CTA Section */
.bg-gradient-deep-blue {
    background: var(--gradient-deep-blue);
}

.cta-title {
    animation: pulse 2s ease-in-out infinite;
}

.cta-btn-primary,
.cta-btn-secondary {
    transition: var(--transition-bounce);
    position: relative;
    overflow: hidden;
}

.cta-btn-primary {
    color: var(--primary);
}

.cta-btn-primary:hover,
.cta-btn-secondary:hover {
    transform: translateY(-3px) scale(1.05);
}

.cta-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 100px 100px, 150px 150px, 80px 80px;
    animation: moveParticles 20s linear infinite;
}

/* Background shapes */
.background-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.shape {
    position: absolute;
    background: rgba(13, 110, 253, 0.05);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.shape-1 {
    width: 200px;
    height: 200px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    background: rgba(13, 110, 253, 0.05);
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 10%;
    animation-delay: 3s;
    background: rgba(13, 202, 240, 0.05);
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 60%;
    animation-delay: 6s;
    background: rgba(30, 64, 175, 0.05);
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
    }

    50% {
        transform: translateY(-20px) rotate(180deg);
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

@keyframes moveParticles {
    0% {
        transform: translateX(0) translateY(0);
    }

    100% {
        transform: translateX(-100px) translateY(-100px);
    }
}

/* Animations au scroll */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.animate-on-scroll.animated {
    opacity: 1;
    transform: translateY(0);
}

/* Utilitaires */
.text-gradient-blue {
    background: var(--gradient-blue);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: inline-block;
}

.divider {
    width: 60px;
    height: 4px;
    border-radius: 2px;
}

.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.5rem;
}

.value-icon {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
}

.z-index-1 {
    z-index: 1;
}

.min-vh-50 {
    min-height: 50vh;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        min-height: 80vh;
    }

    .stat-number {
        font-size: 2rem;
    }

    .cta-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    .floating-element {
        display: none;
    }
}

@media (max-width: 576px) {
    .mission-card {
        margin-bottom: 20px;
    }

    .value-card {
        margin-bottom: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des éléments au scroll avec Intersection Observer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('animated');
                }, delay);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observer tous les éléments à animer
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Animation des compteurs
    const animateCounters = () => {
        document.querySelectorAll('.counter').forEach(counter => {
            const target = parseInt(counter.dataset.target);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            updateCounter();
        });
    };

    // Observer pour les compteurs
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                counterObserver.unobserve(entry.target);
            }
        });
    });

    const statsSection = document.querySelector('.stat-item');
    if (statsSection) {
        counterObserver.observe(statsSection);
    }

    // Animation des barres de progression
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBars = entry.target.querySelectorAll('.progress-bar');
                progressBars.forEach(bar => {
                    const width = bar.dataset.width;
                    setTimeout(() => {
                        bar.style.width = width + '%';
                    }, 500);
                });
                progressObserver.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.value-card').forEach(card => {
        progressObserver.observe(card);
    });

    // Smooth scroll pour les liens
    document.querySelectorAll('.scroll-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Parallax effect pour les éléments flottants
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.floating-element');

        parallaxElements.forEach((element, index) => {
            const speed = 0.5 + (index * 0.2);
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Effet de typing pour le titre hero
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        let i = 0;

        const typeWriter = () => {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        };

        setTimeout(typeWriter, 1000);
    }

    // Ajout d'effets de particules au survol des cartes
    document.querySelectorAll('.team-card, .value-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform += ' scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = this.style.transform.replace(' scale(1.02)', '');
        });
    });

    // Animation de révélation progressive du contenu
    const revealElements = () => {
        const elements = document.querySelectorAll('.mission-item, .team-card, .value-card');
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
    };

    // Déclenchement après le chargement complet
    setTimeout(revealElements, 500);
});

// Gestion du redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    // Recalcul des animations si nécessaire
    const elements = document.querySelectorAll('.animate-on-scroll');
    elements.forEach(el => {
        if (el.classList.contains('animated')) {
            el.style.transform = 'translateY(0)';
            el.style.opacity = '1';
        }
    });
});
</script>

<?php include('includes/footer.php'); ?>