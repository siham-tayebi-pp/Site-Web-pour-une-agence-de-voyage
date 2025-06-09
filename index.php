<?php
session_start();
include('includes/db.php');
include('includes/header.php');
?>

<!-- Hero Section avec Carrousel (conservé avec améliorations subtiles) -->
<section class="hero-carousel position-relative overflow-hidden">
    <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators modern-indicators">
            <?php
            $query = "SELECT * FROM voyage ORDER BY date_depart ASC LIMIT 5";
            $result = $conn->query($query);
            $count = 0;
            while($row = $result->fetch_assoc()):
            ?>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="<?= $count ?>"
                <?= $count === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $count + 1 ?>">
                <span class="indicator-progress"></span>
            </button>
            <?php 
                $count++;
            endwhile; 
            ?>
        </div>

        <div class="carousel-inner">
            <?php
            $result->data_seek(0); // Reset pointer
            $first = true;
            while($row = $result->fetch_assoc()):
                $images = glob("images/voyages/" . $row['image'] . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                $firstImage = count($images) > 0 ? $images[0] : 'images/default-voyage.jpg';
            ?>
            <div class="carousel-item <?= $first ? 'active' : '' ?>">
                <div class="carousel-image-container">
                    <!-- Image avec effet subtil -->
                    <img src="<?= htmlspecialchars($firstImage) ?>" class="d-block w-100 clean-image"
                        alt="<?= htmlspecialchars($row['titre']) ?>">
                    <div class="hero-overlay"></div>

                    <!-- Contenu en bas à gauche (conservé) -->
                    <div class="carousel-content-overlay">
                        <div class="content-card">
                            <div class="content-header">
                                <span class="content-category">Voyage Premium</span>
                                <h3 class="content-title"><?= htmlspecialchars($row['titre']) ?></h3>
                            </div>

                            <div class="content-badges mb-3">
                                <span class="mini-badge badge-location">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= htmlspecialchars($row['destination']) ?>
                                </span>
                                <span class="mini-badge badge-date">
                                    <i class="fas fa-calendar-day me-1"></i>
                                    <?= date('d M Y', strtotime($row['date_depart'])) ?>
                                </span>
                                <span class="mini-badge badge-price">
                                    <i class="fas fa-tag me-1"></i>
                                    <?= number_format($row['prix'], 0, ',', ' ') ?> DH
                                </span>
                            </div>

                            <p class="content-description">
                                <?= substr(htmlspecialchars($row['description']), 0, 110) ?>...
                            </p>

                            <div class="content-buttons">
                                <a href="details.php?id=<?= $row['id'] ?>" class="btn-mini btn-primary-mini">
                                    <span>Découvrir</span>
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                                <a href="reservation.php?id=<?= $row['id'] ?>" class="btn-mini btn-outline-mini">
                                    <span>Réserver</span>
                                    <i class="fas fa-heart ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                $first = false;
            endwhile; 
            ?>
        </div>

        <button class="carousel-control-prev modern-control" type="button" data-bs-target="#mainCarousel"
            data-bs-slide="prev">
            <span class="modern-control-icon">
                <i class="fas fa-chevron-left"></i>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next modern-control" type="button" data-bs-target="#mainCarousel"
            data-bs-slide="next">
            <span class="modern-control-icon">
                <i class="fas fa-chevron-right"></i>
            </span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Section Voyages améliorée -->
<section id="voyages" class="py-5 voyages-section">
    <div class="container">
        <div class="text-center mb-5 section-header">
            <span class="section-badge">Explorez le Monde</span>
            <h2 class="display-5 fw-bold section-title">Nos Destinations Phares</h2>
            <p class="lead section-subtitle">Découvrez des expériences uniques et créez des souvenirs inoubliables</p>
            <div class="divider mx-auto"></div>
        </div>

        <!-- Filtres améliorés -->
        <div class="filter-section mb-5">
            <div class="filter-container">
                <div class="filter-header">
                    <h4 class="filter-title">
                        <i class="fas fa-search me-2"></i>
                        Trouvez votre voyage idéal
                    </h4>
                    <p class="filter-subtitle">Utilisez les filtres pour affiner votre recherche</p>
                </div>

                <div class="filter-controls">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-globe-americas"></i>
                            Destination
                        </label>
                        <select class="form-select custom-select" id="filter-destination">
                            <option value="">Toutes destinations</option>
                            <?php
                            $destinations = $conn->query("SELECT DISTINCT destination FROM voyage ORDER BY destination");
                            while($dest = $destinations->fetch_assoc()) {
                                echo '<option value="'.htmlspecialchars($dest['destination']).'">'.htmlspecialchars($dest['destination']).'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-wallet"></i>
                            Budget
                        </label>
                        <select class="form-select custom-select" id="filter-price">
                            <option value="">Tous les prix</option>
                            <option value="0-2000">Moins de 2000 DH</option>
                            <option value="2000-5000">2000 à 5000 DH</option>
                            <option value="5000-10000">5000 à 10000 DH</option>
                            <option value="10000">Plus de 10000 DH</option>
                        </select>
                    </div>

                    <button class="btn btn-filter" id="filter-btn">
                        <i class="fas fa-filter me-2"></i>
                        <span>Filtrer</span>
                        <div class="btn-shine"></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Grille des voyages -->
        <div class="row g-4" id="voyage-container">
            <?php
            $query = "SELECT * FROM voyage ORDER BY date_depart ASC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imageFolder = 'images/voyages/' . $row['image'] . '/';
                    $images = glob($imageFolder . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    $carouselId = "carouselVoyage" . $row['id'];
                    
                    echo '<div class="col-lg-4 col-md-6 voyage-item" 
                            data-destination="'.htmlspecialchars($row['destination']).'" 
                            data-price="'.$row['prix'].'">
                            <div class="travel-card">';
                    
                    // Badge promo amélioré
                    if (!empty($row['promotion'])) {
                        echo '<div class="promo-badge">
                                <div class="promo-icon">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <span class="promo-text">-'.$row['promotion'].'%</span>
                              </div>';
                    }
                    
                    // Carrousel d'images amélioré
                    if (!empty($images)) {
                        echo '<div id="'.$carouselId.'" class="carousel slide card-carousel" data-bs-ride="carousel">
                                <div class="carousel-inner">';
                        foreach ($images as $index => $image) {
                            $active = $index === 0 ? 'active' : '';
                            echo '<div class="carousel-item '.$active.'">
                                    <div class="card-img-wrapper">
                                        <img src="'.htmlspecialchars($image).'" class="card-img" alt="'.htmlspecialchars($row['titre']).'">
                                        <div class="image-gradient"></div>
                                    </div>
                                  </div>';
                        }
                        echo '</div>';
                        
                        if (count($images) > 1) {
                            echo '<div class="carousel-controls">
                                    <button class="control-btn prev" type="button" data-bs-target="#'.$carouselId.'" data-bs-slide="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="control-btn next" type="button" data-bs-target="#'.$carouselId.'" data-bs-slide="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                  </div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="card-img-wrapper">
                                <img src="images/default-voyage.jpg" class="card-img" alt="Default image">
                                <div class="image-gradient"></div>
                              </div>';
                    }
                    
                    // Badge destination amélioré
                    echo '<div class="destination-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>'.htmlspecialchars($row['destination']).'</span>
                          </div>';
                    
                    // Contenu de la carte amélioré
                    echo '<div class="card-content">
                            <div class="card-header">
                                <h3 class="card-title">'.htmlspecialchars($row['titre']).'</h3>
                                <div class="card-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="rating-value">4.8</span>
                                </div>
                            </div>
                            
                            <div class="card-description">
                                <p>'.substr(htmlspecialchars($row['description']), 0, 130).'...</p>
                            </div>
                            
                            <div class="card-features">
                                <div class="feature-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>'.date('d M Y', strtotime($row['date_depart'])).'</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-clock"></i>
                                    <span>7 jours</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-users"></i>
                                    <span>12 max</span>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="price-section">
                                    <span class="price-label">À partir de</span>
                                    <div class="price-display">
                                        <span class="amount">'.number_format($row['prix'], 0, ',', ' ').'</span>
                                        <span class="currency">DH</span>
                                    </div>
                                    <span class="price-note">par personne</span>
                                </div>
                                
                                <a href="details.php?id='.$row['id'].'" class="btn-card">
                                    <span>Explorer</span>
                                    <i class="fas fa-arrow-right"></i>
                                    <div class="btn-ripple"></div>
                                </a>
                            </div>
                          </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-compass"></i>
                                <div class="icon-pulse"></div>
                            </div>
                            <h4 class="empty-title">Nouvelles aventures en préparation</h4>
                            <p class="empty-text">Nos équipes travaillent sur de nouvelles destinations extraordinaires. Revenez bientôt !</p>
                            <button class="btn btn-notify">
                                <i class="fas fa-bell me-2"></i>
                                Me notifier
                            </button>
                        </div>
                      </div>';
            }
            ?>
        </div>

        <!-- Pagination améliorée -->
        <div class="pagination-wrapper">
            <nav class="custom-pagination">
                <a href="#" class="page-nav prev disabled">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <div class="page-numbers">
                    <a href="#" class="page-number active">1</a>
                    <a href="#" class="page-number">2</a>
                    <a href="#" class="page-number">3</a>
                    <span class="page-dots">...</span>
                    <a href="#" class="page-number">8</a>
                </div>
                <a href="#" class="page-nav next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </nav>
        </div>
    </div>
</section>

<!-- Newsletter Section améliorée avec effets d'étoiles -->
<section class="newsletter-section position-relative overflow-hidden">
    <!-- Arrière-plan avec dégradé et effets -->
    <div class="newsletter-bg">
        <!-- Étoiles animées -->
        <div class="stars-container">
            <div class="stars stars-small"></div>
            <div class="stars stars-medium"></div>
            <div class="stars stars-large"></div>
        </div>

        <!-- Particules flottantes -->
        <div class="floating-particles">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
            <div class="particle particle-4"></div>
            <div class="particle particle-5"></div>
            <div class="particle particle-6"></div>
        </div>

        <!-- Formes géométriques animées -->
        <div class="geometric-shapes">
            <div class="shape shape-circle"></div>
            <div class="shape shape-triangle"></div>
            <div class="shape shape-square"></div>
        </div>

        <!-- Vagues animées -->
        <div class="animated-waves">
            <div class="wave wave-1"></div>
            <div class="wave wave-2"></div>
            <div class="wave wave-3"></div>
        </div>
    </div>

    <div class="container position-relative" style="z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="newsletter-content">
                    <!-- Icône avec effet de lueur -->
                    <div class="newsletter-icon-wrapper">
                        <div class="newsletter-icon">
                            <i class="fas fa-paper-plane"></i>
                            <div class="icon-glow"></div>
                            <div class="icon-pulse"></div>
                        </div>
                    </div>

                    <!-- Titre avec effet de typing -->
                    <h2 class="newsletter-title" id="newsletter-title">
                        <span class="title-word" data-text="Restez">Restez</span>
                        <span class="title-word" data-text="connecté">connecté</span>
                        <span class="title-word" data-text="à">à</span>
                        <span class="title-word" data-text="l'aventure">l'aventure</span>
                    </h2>

                    <p class="newsletter-subtitle animate-fade-in">
                        Recevez nos meilleures offres et découvrez en avant-première nos nouvelles destinations
                    </p>

                    <!-- Formulaire avec effets améliorés -->
                    <form class="newsletter-form animate-slide-up">
                        <div class="input-group-enhanced">
                            <div class="input-wrapper-enhanced">
                                <div class="input-bg-effect"></div>
                                <i class="fas fa-envelope input-icon-enhanced"></i>
                                <input type="email" class="form-control newsletter-input-enhanced"
                                    placeholder="Votre adresse email" required>
                                <div class="input-focus-line"></div>
                            </div>
                            <button class="btn btn-newsletter-enhanced" type="submit">
                                <span class="btn-text">S'abonner</span>
                                <i class="fas fa-arrow-right btn-icon"></i>
                                <div class="btn-ripple"></div>
                                <div class="btn-glow"></div>
                            </button>
                        </div>
                    </form>

                    <!-- Fonctionnalités avec animations -->
                    <div class="newsletter-features-enhanced">
                        <div class="feature-enhanced" data-delay="100">
                            <div class="feature-icon-wrapper">
                                <i class="fas fa-gift"></i>
                                <div class="feature-glow"></div>
                            </div>
                            <span>Offres exclusives</span>
                        </div>
                        <div class="feature-enhanced" data-delay="200">
                            <div class="feature-icon-wrapper">
                                <i class="fas fa-bell"></i>
                                <div class="feature-glow"></div>
                            </div>
                            <span>Alertes voyage</span>
                        </div>
                        <div class="feature-enhanced" data-delay="300">
                            <div class="feature-icon-wrapper">
                                <i class="fas fa-shield-alt"></i>
                                <div class="feature-glow"></div>
                            </div>
                            <span>0 spam garanti</span>
                        </div>
                    </div>

                    <!-- Indicateur de succès -->
                    <div class="success-message" id="success-message">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Merci ! Vous êtes maintenant abonné à nos aventures !</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Variables CSS - Palette bleue raffinée et unifiée */
:root {
    /* Nuances de bleu principales */
    --primary-50: #eff6ff;
    --primary-100: #dbeafe;
    --primary-200: #bfdbfe;
    --primary-300: #93c5fd;
    --primary-400: #60a5fa;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
    --primary-700: #1d4ed8;
    --primary-800: #1e40af;
    --primary-900: #1e3a8a;

    /* Couleurs complémentaires harmonieuses avec le bleu */
    --accent-cyan: #06b6d4;
    --accent-cyan-light: #67e8f9;
    --accent-indigo: #6366f1;
    --accent-purple: #8b5cf6;
    --accent-teal: #14b8a6;
    --accent-sky: #0ea5e9;

    /* Couleurs neutres */
    --slate-50: #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;
    --slate-400: #94a3b8;
    --slate-500: #64748b;
    --slate-600: #475569;
    --slate-700: #334155;
    --slate-800: #1e293b;
    --slate-900: #0f172a;

    /* Couleurs d'accent pour prix et promotions */
    --amber-400: #fbbf24;
    --amber-500: #f59e0b;
    --emerald-500: #10b981;
    --orange-500: #f97316;

    /* Gradients */
    --gradient-primary: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
    --gradient-accent: linear-gradient(135deg, var(--accent-cyan) 0%, var(--primary-500) 100%);
    --gradient-purple: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-indigo) 100%);
    --gradient-teal: linear-gradient(135deg, var(--accent-teal) 0%, var(--accent-cyan) 100%);
    --gradient-light: linear-gradient(135deg, var(--primary-50) 0%, var(--slate-50) 100%);
    --gradient-dark: linear-gradient(135deg, var(--slate-800) 0%, var(--primary-900) 100%);
    --gradient-sky: linear-gradient(135deg, var(--accent-sky) 0%, var(--primary-400) 100%);
    --gradient-newsletter: linear-gradient(135deg, var(--primary-600) 0%, var(--accent-cyan) 50%, var(--accent-indigo) 100%);

    /* Ombres */
    --shadow-sm: 0 1px 2px 0 rgba(59, 130, 246, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(59, 130, 246, 0.1), 0 4px 6px -2px rgba(59, 130, 246, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
    --shadow-glow: 0 0 20px rgba(59, 130, 246, 0.3);

    /* Rayons */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --radius-2xl: 24px;
    --radius-full: 9999px;

    /* Transitions */
    --transition-fast: 0.15s ease;
    --transition-normal: 0.3s ease;
    --transition-slow: 0.5s ease;
}

/* Animations améliorées */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
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
        transform: scale(1.05);
    }
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(100%);
    }
}

@keyframes glow {

    0%,
    100% {
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
    }

    50% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
    }
}

@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 1;
    }

    100% {
        transform: scale(4);
        opacity: 0;
    }
}

@keyframes sparkle {

    0%,
    100% {
        opacity: 0.3;
        transform: translateY(0) scale(1);
    }

    50% {
        opacity: 1;
        transform: translateY(-10px) scale(1.1);
    }
}

@keyframes float-particle {

    0%,
    100% {
        transform: translateY(0) translateX(0) rotate(0deg);
        opacity: 0.3;
    }

    25% {
        transform: translateY(-30px) translateX(10px) rotate(90deg);
        opacity: 0.8;
    }

    50% {
        transform: translateY(-60px) translateX(-10px) rotate(180deg);
        opacity: 1;
    }

    75% {
        transform: translateY(-30px) translateX(15px) rotate(270deg);
        opacity: 0.6;
    }
}

@keyframes rotate-shape {
    0% {
        transform: rotate(0deg) scale(1);
        opacity: 0.1;
    }

    50% {
        transform: rotate(180deg) scale(1.2);
        opacity: 0.3;
    }

    100% {
        transform: rotate(360deg) scale(1);
        opacity: 0.1;
    }
}

@keyframes wave-move {

    0%,
    100% {
        transform: translateX(-50%) translateY(0);
    }

    50% {
        transform: translateX(-25%) translateY(-20px);
    }
}

@keyframes icon-float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

@keyframes glow-pulse {

    0%,
    100% {
        opacity: 0.5;
        transform: scale(1);
    }

    50% {
        opacity: 1;
        transform: scale(1.1);
    }
}

@keyframes pulse-ring {
    0% {
        transform: scale(1);
        opacity: 1;
    }

    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

@keyframes word-appear {
    0% {
        opacity: 0;
        transform: translateY(30px) rotateX(90deg);
    }

    100% {
        opacity: 1;
        transform: translateY(0) rotateX(0deg);
    }
}

@keyframes fade-in {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }

    100% {
        opacity: 0.9;
        transform: translateY(0);
    }
}

@keyframes slide-up {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes ripple-effect {
    0% {
        transform: scale(0);
        opacity: 1;
    }

    100% {
        transform: scale(4);
        opacity: 0;
    }
}

@keyframes feature-appear {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }

    100% {
        opacity: 0.9;
        transform: translateY(0);
    }
}

@keyframes twinkle {

    0%,
    100% {
        opacity: 0.3;
        transform: scale(1);
    }

    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

/* Hero Carousel amélioré */
.hero-carousel {
    height: 100vh;
    min-height: 600px;
    max-height: 900px;
    position: relative;
}

.carousel-image-container {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 600px;
    max-height: 900px;
    overflow: hidden;
}

.clean-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    filter: brightness(0.85) contrast(1.1);
    transition: transform 8s ease;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(30, 58, 138, 0.3) 0%, transparent 50%, rgba(59, 130, 246, 0.2) 100%);
    z-index: 1;
}

.carousel-content-overlay {
    position: absolute;
    bottom: 40px;
    left: 40px;
    z-index: 10;
    max-width: 480px;
}

.content-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: var(--radius-2xl);
    padding: 2rem;
    box-shadow: var(--shadow-xl);
    border: 1px solid rgba(255, 255, 255, 0.3);
    animation: slideInLeft 0.8s ease-out;
    position: relative;
    overflow: hidden;
}

.content-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--gradient-primary);
}

.content-header {
    margin-bottom: 1.5rem;
}

.content-category {
    display: inline-block;
    background: var(--gradient-accent);
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.content-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--slate-800);
    line-height: 1.3;
    margin: 0;
}

.content-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.mini-badge {
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.mini-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.mini-badge:hover::before {
    left: 100%;
}

.badge-location {
    background: var(--gradient-primary);
}

.badge-date {
    background: var(--gradient-teal);
}

.badge-price {
    background: var(--gradient-purple);
}

.content-description {
    color: var(--slate-600);
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.content-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-mini {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-full);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all var(--transition-normal);
    display: inline-flex;
    align-items: center;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-primary-mini {
    background: var(--gradient-primary);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-primary-mini:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-outline-mini {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary-600);
    border: 2px solid var(--primary-600);
}

.btn-outline-mini:hover {
    background: var(--primary-600);
    color: white;
    transform: translateY(-3px);
}

.modern-indicators {
    bottom: 30px;
    z-index: 15;
}

.modern-indicators button {
    width: 80px;
    height: 4px;
    border-radius: var(--radius-sm);
    border: none;
    background: rgba(255, 255, 255, 0.4);
    margin: 0 8px;
    position: relative;
    overflow: hidden;
}

.indicator-progress {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: white;
    width: 0;
    transition: width 6s linear;
    border-radius: var(--radius-sm);
}

.modern-indicators .active .indicator-progress {
    width: 100%;
}

.modern-control {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-normal);
    z-index: 15;
}

.modern-control:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.1);
    box-shadow: var(--shadow-md);
}

.modern-control-icon {
    color: var(--primary-600);
    font-size: 1.2rem;
}

/* Section Voyages améliorée */
.voyages-section {
    background: var(--gradient-light);
    position: relative;
}

.section-header {
    position: relative;
    z-index: 2;
}

.section-badge {
    display: inline-block;
    background: var(--gradient-accent);
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: var(--radius-full);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.section-title {
    color: var(--slate-800);
    font-weight: 800;
    margin-bottom: 1rem;
}

.section-subtitle {
    color: var(--slate-600);
    font-size: 1.2rem;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.divider {
    width: 100px;
    height: 4px;
    background: var(--gradient-primary);
    margin: 20px auto;
    border-radius: var(--radius-full);
    position: relative;
}

.divider::before,
.divider::after {
    content: '';
    position: absolute;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--gradient-accent);
    top: 50%;
    transform: translateY(-50%);
}

.divider::before {
    left: -20px;
}

.divider::after {
    right: -20px;
}

/* Filtres améliorés */
.filter-section {
    max-width: 1000px;
    margin: 0 auto;
}

.filter-container {
    background: white;
    border-radius: var(--radius-2xl);
    padding: 2rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--slate-200);
}

.filter-header {
    text-align: center;
    margin-bottom: 2rem;
}

.filter-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--slate-800);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.filter-title i {
    color: var(--primary-500);
}

.filter-subtitle {
    color: var(--slate-600);
    font-size: 1rem;
    margin: 0;
}

.filter-controls {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 2rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-label {
    font-weight: 600;
    color: var(--slate-700);
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-label i {
    color: var(--primary-500);
    font-size: 1rem;
}

.custom-select {
    border: 2px solid var(--slate-200);
    border-radius: var(--radius-md);
    padding: 1rem 1.25rem;
    font-weight: 500;
    color: var(--slate-700);
    background-color: white;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px;
    appearance: none;
    transition: all var(--transition-normal);
}

.custom-select:focus {
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.btn-filter {
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 150px;
    justify-content: center;
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.7s;
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-filter:hover .btn-shine {
    left: 100%;
}

/* Cartes de voyage améliorées */
.travel-card {
    position: relative;
    background: white;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all var(--transition-normal);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--slate-200);
}

.travel-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}

.card-img-wrapper {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.travel-card:hover .card-img {
    transform: scale(1.1);
}

.image-gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
    opacity: 0;
    transition: opacity var(--transition-normal);
}

.travel-card:hover .image-gradient {
    opacity: 1;
}

.promo-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--gradient-purple);
    color: white;
    border-radius: var(--radius-full);
    padding: 0.5rem 1rem;
    z-index: 3;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    animation: pulse 2s infinite;
}

.promo-icon {
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.destination-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(255, 255, 255, 0.95);
    color: var(--primary-600);
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    z-index: 3;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.carousel-controls {
    position: absolute;
    bottom: 15px;
    right: 15px;
    display: flex;
    gap: 0.5rem;
    z-index: 3;
}

.control-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-600);
    font-size: 0.9rem;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
    backdrop-filter: blur(10px);
}

.control-btn:hover {
    background: var(--primary-600);
    color: white;
    transform: scale(1.1);
}

.card-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--slate-800);
    line-height: 1.3;
    flex: 1;
    margin-right: 1rem;
}

.card-rating {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.stars {
    display: flex;
    gap: 0.125rem;
    color: var(--amber-400);
    font-size: 0.8rem;
}

.rating-value {
    color: var(--slate-600);
    font-weight: 600;
    font-size: 0.85rem;
}

.card-description {
    color: var(--slate-600);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.25rem;
    flex-grow: 1;
}

.card-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--slate-50);
    border-radius: var(--radius-md);
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    color: var(--slate-600);
    font-size: 0.8rem;
    font-weight: 500;
    text-align: center;
}

.feature-item i {
    color: var(--primary-500);
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--slate-200);
}

.price-section {
    display: flex;
    flex-direction: column;
}

.price-label {
    font-size: 0.75rem;
    color: var(--slate-500);
    margin-bottom: 0.25rem;
}

.price-display {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
    margin-bottom: 0.25rem;
}

.amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-600);
}

.currency {
    font-size: 1rem;
    color: var(--slate-600);
    font-weight: 600;
}

.price-note {
    font-size: 0.7rem;
    color: var(--slate-400);
}

.btn-card {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--gradient-primary);
    color: white;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-full);
    font-weight: 600;
    font-size: 0.9rem;
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
}

.btn-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

.btn-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

.btn-card i {
    transition: transform var(--transition-fast);
}

.btn-card:hover i {
    transform: translateX(3px);
}

/* État vide amélioré */
.empty-state {
    background: white;
    border-radius: var(--radius-xl);
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--slate-200);
    position: relative;
}

.empty-icon {
    position: relative;
    width: 80px;
    height: 80px;
    background: var(--gradient-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    border: 2px solid var(--primary-200);
}

.empty-icon i {
    font-size: 2rem;
    color: var(--primary-500);
    animation: float 3s ease-in-out infinite;
}

.icon-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid var(--primary-300);
    animation: pulse 2s infinite;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--slate-800);
    margin-bottom: 0.75rem;
}

.empty-text {
    color: var(--slate-600);
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.btn-notify {
    background: var(--gradient-accent);
    color: white;
    border: none;
    border-radius: var(--radius-full);
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all var(--transition-normal);
}

.btn-notify:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

/* Pagination améliorée */
.pagination-wrapper {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

.custom-pagination {
    display: flex;
    align-items: center;
    background: white;
    border-radius: var(--radius-full);
    padding: 0.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--slate-200);
}

.page-numbers {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin: 0 0.5rem;
}

.page-number {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: var(--slate-600);
    font-weight: 600;
    text-decoration: none;
    transition: all var(--transition-fast);
    font-size: 0.95rem;
}

.page-number:hover {
    background: var(--primary-50);
    color: var(--primary-600);
    transform: scale(1.1);
}

.page-number.active {
    background: var(--gradient-primary);
    color: white;
    box-shadow: var(--shadow-sm);
}

.page-dots {
    color: var(--slate-400);
    font-weight: 600;
    padding: 0 0.5rem;
}

.page-nav {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: var(--slate-600);
    text-decoration: none;
    transition: all var(--transition-fast);
    font-size: 0.9rem;
}

.page-nav:hover {
    background: var(--primary-50);
    color: var(--primary-600);
    transform: scale(1.1);
}

.page-nav.disabled {
    color: var(--slate-300);
    pointer-events: none;
}

/* Newsletter Section améliorée avec effets d'étoiles */
.newsletter-section {
    padding: 5rem 0;
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.newsletter-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--gradient-newsletter);
    overflow: hidden;
}

/* Étoiles animées */
.stars-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.stars {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        radial-gradient(2px 2px at 20px 30px, rgba(255, 255, 255, 0.8), transparent),
        radial-gradient(2px 2px at 40px 70px, rgba(255, 255, 255, 0.6), transparent),
        radial-gradient(1px 1px at 90px 40px, rgba(255, 255, 255, 0.9), transparent),
        radial-gradient(1px 1px at 130px 80px, rgba(255, 255, 255, 0.7), transparent),
        radial-gradient(2px 2px at 160px 30px, rgba(255, 255, 255, 0.8), transparent);
    background-repeat: repeat;
    background-size: 200px 100px;
    animation: sparkle 3s linear infinite;
}

.stars-small {
    background-size: 200px 100px;
    animation-duration: 3s;
}

.stars-medium {
    background-size: 300px 150px;
    animation-duration: 4s;
    animation-delay: -1s;
}

.stars-large {
    background-size: 400px 200px;
    animation-duration: 5s;
    animation-delay: -2s;
}

/* Particules flottantes */
.floating-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float-particle 8s ease-in-out infinite;
}

.particle-1 {
    width: 20px;
    height: 20px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
}

.particle-2 {
    width: 15px;
    height: 15px;
    top: 20%;
    right: 20%;
    animation-delay: 2s;
    background: radial-gradient(circle, rgba(13, 202, 240, 0.4) 0%, transparent 70%);
}

.particle-3 {
    width: 25px;
    height: 25px;
    top: 60%;
    left: 15%;
    animation-delay: 4s;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, transparent 70%);
}

.particle-4 {
    width: 18px;
    height: 18px;
    top: 70%;
    right: 15%;
    animation-delay: 1s;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
}

.particle-5 {
    width: 12px;
    height: 12px;
    top: 40%;
    left: 80%;
    animation-delay: 3s;
    background: radial-gradient(circle, rgba(13, 110, 253, 0.4) 0%, transparent 70%);
}

.particle-6 {
    width: 22px;
    height: 22px;
    top: 80%;
    left: 60%;
    animation-delay: 5s;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, transparent 70%);
}

/* Formes géométriques */
.geometric-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    opacity: 0.1;
    animation: rotate-shape 20s linear infinite;
}

.shape-circle {
    width: 100px;
    height: 100px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    top: 15%;
    right: 10%;
    animation-delay: 0s;
}

.shape-triangle {
    width: 0;
    height: 0;
    border-left: 40px solid transparent;
    border-right: 40px solid transparent;
    border-bottom: 70px solid rgba(255, 255, 255, 0.2);
    top: 60%;
    left: 5%;
    animation-delay: 7s;
}

.shape-square {
    width: 60px;
    height: 60px;
    border: 2px solid rgba(255, 255, 255, 0.25);
    top: 30%;
    left: 85%;
    animation-delay: 14s;
}

/* Vagues animées */
.animated-waves {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
}

.wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200%;
    height: 100px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    animation: wave-move 8s ease-in-out infinite;
}

.wave-1 {
    animation-delay: 0s;
    opacity: 0.3;
}

.wave-2 {
    animation-delay: 2s;
    opacity: 0.2;
}

.wave-3 {
    animation-delay: 4s;
    opacity: 0.1;
}

/* Contenu de la newsletter */
.newsletter-content {
    color: white;
    position: relative;
    z-index: 10;
}

/* Icône améliorée */
.newsletter-icon-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 2rem;
}

.newsletter-icon {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    position: relative;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    animation: icon-float 3s ease-in-out infinite;
}

.icon-glow {
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    border-radius: 50%;
    animation: glow-pulse 2s ease-in-out infinite;
}

.icon-pulse {
    position: absolute;
    top: -20px;
    left: -20px;
    right: -20px;
    bottom: -20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: pulse-ring 3s ease-out infinite;
}

/* Titre avec effet de typing */
.newsletter-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.title-word {
    display: inline-block;
    opacity: 0;
    animation: word-appear 0.8s ease-out forwards;
}

.title-word:nth-child(1) {
    animation-delay: 0.2s;
}

.title-word:nth-child(2) {
    animation-delay: 0.4s;
}

.title-word:nth-child(3) {
    animation-delay: 0.6s;
}

.title-word:nth-child(4) {
    animation-delay: 0.8s;
}

.newsletter-subtitle {
    font-size: 1.3rem;
    margin-bottom: 3rem;
    opacity: 0.9;
    line-height: 1.6;
}

.animate-fade-in {
    animation: fade-in 1s ease-out 1s forwards;
    opacity: 0;
}

/* Formulaire amélioré */
.newsletter-form {
    max-width: 600px;
    margin: 0 auto 3rem;
}

.animate-slide-up {
    animation: slide-up 1s ease-out 1.2s forwards;
    opacity: 0;
    transform: translateY(30px);
}

.input-group-enhanced {
    display: flex;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    padding: 8px;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.input-group-enhanced:hover {
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.input-wrapper-enhanced {
    position: relative;
    flex: 1;
    display: flex;
    align-items: center;
}

.input-bg-effect {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    border-radius: 50px;
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.input-wrapper-enhanced:hover .input-bg-effect {
    transform: translateX(100%);
}

.input-icon-enhanced {
    position: absolute;
    left: 20px;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.2rem;
    z-index: 2;
    transition: all 0.3s ease;
}

.newsletter-input-enhanced {
    border: none;
    background: transparent;
    padding: 18px 20px 18px 55px;
    font-size: 1.1rem;
    color: white;
    flex: 1;
    border-radius: 50px;
    position: relative;
    z-index: 1;
}

.newsletter-input-enhanced::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.newsletter-input-enhanced:focus {
    outline: none;
    box-shadow: none;
}

.newsletter-input-enhanced:focus+.input-focus-line {
    transform: scaleX(1);
}

.newsletter-input-enhanced:focus~.input-icon-enhanced {
    color: white;
    transform: scale(1.1);
}

.input-focus-line {
    position: absolute;
    bottom: 0;
    left: 20px;
    right: 20px;
    height: 2px;
    background: linear-gradient(90deg, transparent, white, transparent);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

/* Bouton amélioré */
.btn-newsletter-enhanced {
    background: white;
    color: var(--primary-600);
    border: none;
    border-radius: 50px;
    padding: 18px 35px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

.btn-newsletter-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
    color: var(--primary-600);
}

.btn-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.3);
    transform: scale(0);
    animation: ripple-effect 0.6s linear;
    pointer-events: none;
}

.btn-glow {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    border-radius: 50px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-newsletter-enhanced:hover .btn-glow {
    opacity: 1;
}

.btn-icon {
    transition: transform 0.3s ease;
}

.btn-newsletter-enhanced:hover .btn-icon {
    transform: translateX(5px);
}

/* Fonctionnalités améliorées */
.newsletter-features-enhanced {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.feature-enhanced {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1rem;
    opacity: 0;
    transform: translateY(20px);
    animation: feature-appear 0.8s ease-out forwards;
}

.feature-enhanced:nth-child(1) {
    animation-delay: 1.4s;
}

.feature-enhanced:nth-child(2) {
    animation-delay: 1.6s;
}

.feature-enhanced:nth-child(3) {
    animation-delay: 1.8s;
}

.feature-icon-wrapper {
    position: relative;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.feature-icon-wrapper:hover {
    transform: scale(1.1);
    background: rgba(255, 255, 255, 0.2);
}

.feature-icon-wrapper i {
    font-size: 1.2rem;
    color: white;
}

.feature-glow {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.feature-enhanced:hover .feature-glow {
    opacity: 1;
}

/* Message de succès */
.success-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    background: white;
    color: var(--primary-600);
    padding: 30px 40px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    gap: 15px;
    font-weight: 600;
    font-size: 1.1rem;
    z-index: 1000;
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.success-message.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.success-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--emerald-500), #34d399);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

/* Responsive */
@media (max-width: 992px) {

    .hero-carousel,
    .carousel-image-container {
        height: 70vh;
        min-height: 500px;
    }

    .carousel-content-overlay {
        max-width: 400px;
        bottom: 30px;
        left: 30px;
    }

    .content-card {
        padding: 1.5rem;
    }

    .content-title {
        font-size: 1.5rem;
    }

    .filter-controls {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .card-img-wrapper {
        height: 220px;
    }

    .card-features {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }

    .feature-item {
        flex-direction: row;
        justify-content: center;
    }
}

@media (max-width: 768px) {

    .hero-carousel,
    .carousel-image-container {
        height: 60vh;
        min-height: 400px;
    }

    .carousel-content-overlay {
        max-width: 350px;
        bottom: 20px;
        left: 20px;
    }

    .content-card {
        padding: 1.25rem;
    }

    .content-title {
        font-size: 1.3rem;
    }

    .content-buttons {
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn-mini {
        justify-content: center;
    }

    .filter-container {
        padding: 1.5rem;
    }

    .card-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .newsletter-section {
        padding: 3rem 0;
        min-height: 80vh;
    }

    .newsletter-title {
        font-size: 2.2rem;
    }

    .newsletter-subtitle {
        font-size: 1.1rem;
    }

    .input-group-enhanced {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }

    .btn-newsletter-enhanced {
        width: 100%;
        justify-content: center;
    }

    .newsletter-features-enhanced {
        gap: 1.5rem;
        flex-direction: column;
        align-items: center;
    }

    .particle {
        display: none;
    }

    .geometric-shapes {
        display: none;
    }
}

@media (max-width: 576px) {
    .carousel-content-overlay {
        max-width: 300px;
        bottom: 15px;
        left: 15px;
    }

    .content-card {
        padding: 1rem;
    }

    .content-title {
        font-size: 1.2rem;
    }

    .mini-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }

    .card-img-wrapper {
        height: 200px;
    }

    .destination-badge,
    .promo-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }

    .newsletter-title {
        font-size: 1.8rem;
    }

    .newsletter-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }

    .success-message {
        margin: 0 20px;
        padding: 20px 25px;
        font-size: 1rem;
    }
}

/* Animation pour les cartes */
.voyage-item {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeIn 0.6s ease forwards;
}

.voyage-item:nth-child(1) {
    animation-delay: 0.1s;
}

.voyage-item:nth-child(2) {
    animation-delay: 0.2s;
}

.voyage-item:nth-child(3) {
    animation-delay: 0.3s;
}

.voyage-item:nth-child(4) {
    animation-delay: 0.4s;
}

.voyage-item:nth-child(5) {
    animation-delay: 0.5s;
}

.voyage-item:nth-child(6) {
    animation-delay: 0.6s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des étoiles scintillantes
    function createShootingStar() {
        const star = document.createElement('div');
        star.className = 'shooting-star';
        star.style.cssText = `
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 10px white;
            top: ${Math.random() * 50}%;
            left: -10px;
            animation: shoot 3s linear forwards;
        `;

        const style = document.createElement('style');
        style.textContent = `
            @keyframes shoot {
                0% {
                    transform: translateX(0) translateY(0);
                    opacity: 1;
                }
                100% {
                    transform: translateX(${window.innerWidth + 100}px) translateY(${Math.random() * 200 - 100}px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        const starsContainer = document.querySelector('.stars-container');
        if (starsContainer) {
            starsContainer.appendChild(star);
        }

        setTimeout(() => {
            if (star.parentNode) {
                star.remove();
            }
            if (style.parentNode) {
                style.remove();
            }
        }, 3000);
    }

    // Créer des étoiles filantes périodiquement
    setInterval(createShootingStar, 3000);

    // Animation du formulaire
    const form = document.querySelector('.newsletter-form');
    const successMessage = document.getElementById('success-message');
    const button = document.querySelector('.btn-newsletter-enhanced');

    if (form && button) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Animation du bouton
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Inscription...</span>';
            button.disabled = true;

            // Effet ripple
            const ripple = document.createElement('span');
            ripple.classList.add('btn-ripple');

            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            button.appendChild(ripple);

            // Simulation d'envoi
            setTimeout(() => {
                // Afficher le message de succès
                if (successMessage) {
                    successMessage.classList.add('show');
                }

                // Réinitialiser le bouton
                button.innerHTML = '<i class="fas fa-check"></i> <span>Inscrit !</span>';

                // Masquer le message après 3 secondes
                setTimeout(() => {
                    if (successMessage) {
                        successMessage.classList.remove('show');
                    }
                    button.innerHTML = originalText;
                    button.disabled = false;
                    form.reset();
                    if (ripple.parentNode) {
                        ripple.remove();
                    }
                }, 3000);
            }, 2000);
        });
    }

    // Filtrage des voyages amélioré
    const filterBtn = document.getElementById('filter-btn');
    const destinationFilter = document.getElementById('filter-destination');
    const priceFilter = document.getElementById('filter-price');
    const voyageItems = document.querySelectorAll('.voyage-item');

    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const selectedDestination = destinationFilter ? destinationFilter.value : '';
            const selectedPrice = priceFilter ? priceFilter.value : '';

            // Animation de chargement du bouton
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span>Recherche...</span>';
            this.disabled = true;

            setTimeout(() => {
                // Réinitialiser les animations
                voyageItems.forEach(item => {
                    item.style.animation = 'none';
                    item.offsetHeight; // Force reflow
                });

                let visibleCount = 0;
                voyageItems.forEach((item, index) => {
                    const itemDestination = item.dataset.destination;
                    const itemPrice = parseInt(item.dataset.price);

                    let destinationMatch = selectedDestination === '' ||
                        itemDestination === selectedDestination;
                    let priceMatch = true;

                    if (selectedPrice !== '') {
                        const [min, max] = selectedPrice.split('-').map(Number);
                        priceMatch = max ? (itemPrice >= min && itemPrice <= max) : (
                            itemPrice >= min);
                    }

                    if (destinationMatch && priceMatch) {
                        item.style.display = 'block';
                        item.style.animation =
                            `fadeIn 0.6s ease ${visibleCount * 0.1}s forwards`;
                        visibleCount++;
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(30px)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });

                // Restaurer le bouton
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        });
    }

    // Initialiser les carrousels
    document.querySelectorAll('.carousel').forEach(carousel => {
        if (carousel.querySelectorAll('.carousel-item').length > 1) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                new bootstrap.Carousel(carousel, {
                    interval: carousel.id === 'mainCarousel' ? 6000 : 4000,
                    ride: 'carousel'
                });
            }
        }
    });

    // Animation des indicateurs du carrousel principal
    const mainCarousel = document.getElementById('mainCarousel');
    if (mainCarousel) {
        mainCarousel.addEventListener('slide.bs.carousel', function(e) {
            // Reset toutes les barres de progression
            document.querySelectorAll('.indicator-progress').forEach(progress => {
                progress.style.width = '0';
            });

            // Animer la barre active
            setTimeout(() => {
                const activeIndicator = document.querySelector(
                    '.modern-indicators .active .indicator-progress');
                if (activeIndicator) {
                    activeIndicator.style.width = '100%';
                }
            }, 100);
        });

        // Démarrer l'animation de la première barre
        setTimeout(() => {
            const firstProgress = document.querySelector(
                '.modern-indicators .active .indicator-progress');
            if (firstProgress) {
                firstProgress.style.width = '100%';
            }
        }, 500);
    }

    // Effet parallax léger sur le hero
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroImages = document.querySelectorAll('.clean-image');
        heroImages.forEach(img => {
            img.style.transform = `translateY(${scrolled * 0.3}px) scale(1.05)`;
        });

        // Animation des particules au scroll
        const particles = document.querySelectorAll('.particle');
        particles.forEach((particle, index) => {
            const speed = 0.5 + (index * 0.1);
            const yPos = scrolled * speed;
            particle.style.transform = `translateY(${yPos}px) rotate(${scrolled * 0.1}deg)`;
        });

        // Effet de parallax sur les vagues
        const waves = document.querySelectorAll('.wave');
        waves.forEach((wave, index) => {
            const speed = 0.3 + (index * 0.1);
            wave.style.transform =
                `translateX(${-50 + scrolled * speed * 0.01}%) translateY(${Math.sin(scrolled * 0.01 + index) * 10}px)`;
        });
    });

    // Animation au scroll pour les éléments
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observer les éléments à animer
    document.querySelectorAll('.travel-card, .filter-container, .newsletter-content').forEach(el => {
        observer.observe(el);
    });

    // Animation des formes géométriques au survol
    const shapes = document.querySelectorAll('.shape');
    shapes.forEach(shape => {
        shape.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
            this.style.transform = 'scale(1.5) rotate(45deg)';
            this.style.opacity = '0.5';
        });

        shape.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
            this.style.transform = '';
            this.style.opacity = '';
        });
    });

    // Animation des fonctionnalités au scroll
    const featuresContainer = document.querySelector('.newsletter-features-enhanced');
    if (featuresContainer) {
        const featuresObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const features = entry.target.querySelectorAll('.feature-enhanced');
                    features.forEach((feature, index) => {
                        setTimeout(() => {
                            feature.style.opacity = '0.9';
                            feature.style.transform = 'translateY(0)';
                        }, index * 200);
                    });
                    featuresObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        featuresObserver.observe(featuresContainer);
    }

    // Effet ripple sur les boutons
    document.querySelectorAll('.btn-card').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('btn-ripple');

            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            this.appendChild(ripple);

            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.remove();
                }
            }, 600);
        });
    });

    // Effet hover amélioré sur les cartes
    document.querySelectorAll('.travel-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Animation des badges au survol
    document.querySelectorAll('.mini-badge').forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.05)';
        });

        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Effet de lueur sur les éléments interactifs
    const interactiveElements = document.querySelectorAll(
        '.feature-enhanced, .btn-newsletter-enhanced, .newsletter-icon');

    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.filter =
                'brightness(1.2) drop-shadow(0 0 20px rgba(255, 255, 255, 0.3))';
        });

        element.addEventListener('mouseleave', function() {
            this.style.filter = '';
        });
    });

    // Création de nouvelles étoiles dynamiques
    function createDynamicStars() {
        const container = document.querySelector('.stars-container');
        if (!container) return;

        for (let i = 0; i < 50; i++) {
            const star = document.createElement('div');
            star.style.cssText = `
                position: absolute;
                width: ${Math.random() * 3 + 1}px;
                height: ${Math.random() * 3 + 1}px;
                background: white;
                border-radius: 50%;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                animation: twinkle ${Math.random() * 3 + 2}s ease-in-out infinite;
                animation-delay: ${Math.random() * 2}s;
                box-shadow: 0 0 ${Math.random() * 10 + 5}px rgba(255, 255, 255, 0.8);
            `;
            container.appendChild(star);
        }
    }

    createDynamicStars();

    // Gestion responsive des contrôles
    function handleResponsiveControls() {
        const controls = document.querySelectorAll('.modern-control');
        if (window.innerWidth <= 768) {
            controls.forEach(control => {
                control.style.width = '50px';
                control.style.height = '50px';
            });
        } else {
            controls.forEach(control => {
                control.style.width = '60px';
                control.style.height = '60px';
            });
        }
    }

    window.addEventListener('resize', handleResponsiveControls);
    handleResponsiveControls();

    // Préchargement des images du carousel
    document.querySelectorAll('.carousel-item img').forEach(img => {
        const preloadImg = new Image();
        preloadImg.src = img.src;
    });
});
</script>

<?php include('includes/footer.php'); ?>