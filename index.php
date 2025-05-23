<?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Récupération des voyages pour le carousel
$query = "SELECT * FROM voyage ORDER BY date_depart ASC LIMIT 5";
$result = $conn->query($query);
$voyages = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- Hero Carousel - Version améliorée -->
<section class="hero-carousel position-relative overflow-hidden">
    <div id="voyageCarousel" class="carousel slide carousel-dark" data-bs-ride="carousel" data-bs-interval="6000">
        <!-- Indicateurs -->
        <div class="carousel-indicators">
            <?php foreach ($voyages as $index => $voyage): ?>
            <button type="button" data-bs-target="#voyageCarousel" data-bs-slide-to="<?= $index ?>"
                class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($voyages as $index => $voyage): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="carousel-image-wrapper">
                    <img src="images/<?= htmlspecialchars($voyage['image']) ?>" class="d-block w-100"
                        alt="<?= htmlspecialchars($voyage['destination']) ?>" loading="lazy">
                    <div class="carousel-overlay"></div>
                </div>

                <div class="carousel-caption animate__animated animate__fadeInUp">
                    <div class="container">
                        <div class="row justify-content-right">
                            <div class="col-lg-8 text-center">
                                <!-- <h2 class="display-3 fw-bold mb-3"><?= htmlspecialchars($voyage['titre']) ?></h2> -->

                                <!-- <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
                                    <span class="badge bg-primary bg-opacity-90 fs-6 px-3 py-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <?= htmlspecialchars($voyage['destination']) ?>
                                    </span>
                                    <span class="badge bg-success bg-opacity-90 fs-6 px-3 py-2">
                                        <i class="fas fa-calendar-day me-2"></i>
                                        <?= date('d/m/Y', strtotime($voyage['date_depart'])) ?>
                                    </span>
                                    <span class="badge bg-warning text-dark bg-opacity-90 fs-6 px-3 py-2">
                                        <i class="fas fa-tag me-2"></i>
                                        <?= number_format($voyage['prix'], 0, ',', ' ') ?> DH
                                    </span>
                                </div> -->

                                <!-- <p class="lead d-none d-md-block mb-4">
                                    <?= substr(htmlspecialchars($voyage['description']), 0, 150) ?>...
                                </p> -->

                                <!-- <div class="d-flex justify-content-center gap-3">
                                    <a href="details.php?id=<?= $voyage['id'] ?>"
                                        class="btn btn-light btn-lg px-4 py-2 fw-bold">
                                        Découvrir <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                    <a href="reservation.php?id=<?= $voyage['id'] ?>"
                                        class="btn btn-outline-light btn-lg px-4 py-2 fw-bold">
                                        Réserver <i class="fas fa-shopping-cart ms-2"></i>
                                    </a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Contrôles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#voyageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#voyageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
</section>

<!-- Featured Voyages - Version améliorée -->
<section class="featured-voyages py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">Nos voyages organisés</h2>
            <p class="lead text-muted">Découvrez nos meilleures offres du moment</p>
            <div class="divider mx-auto"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            $query = "SELECT * FROM voyage ORDER BY date_depart ASC LIMIT 3";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6">';
                    echo '<div class="card border-0 shadow-sm h-100 overflow-hidden hover-card">';
                    
                    // Image avec badges
                    echo '<div class="card-img-container position-relative">';
                    echo '<img src="images/' . $row['image'] . '" class="card-img-top" alt="' . htmlspecialchars($row['destination']) . '">';
                    echo '<div class="card-badges">';
                    echo '<span class="badge bg-primary">' . number_format($row['prix'], 0, ',', ' ') . ' DH</span>';
                    
                    if ($row['promotion']) {
                        echo '<span class="badge bg-danger">-'.$row['promotion'].'%</span>';
                    }
                    echo '</div>';
                    echo '</div>';
                    
                    // Corps de la carte
                    echo '<div class="card-body d-flex flex-column">';
                    echo '<div class="mb-3">';
                    echo '<h3 class="h4 card-title">' . htmlspecialchars($row['titre']) . '</h3>';
                    echo '<p class="card-text text-muted">' . substr(htmlspecialchars($row['description']), 0, 100) . '...</p>';
                    echo '</div>';
                    
                    // Métadonnées
                    echo '<div class="mt-auto">';
                    echo '<div class="d-flex flex-wrap gap-2 mb-3">';
                    echo '<span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i> ' . $row['duree'] . ' jours</span>';
                    echo '<span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt me-1"></i> ' . htmlspecialchars($row['destination']) . '</span>';
                    echo '</div>';
                    
                    // Bouton
                    echo '<a href="details.php?id=' . $row['id'] . '" class="btn btn-primary w-100 stretched-link">';
                    echo 'Voir ce voyage <i class="fas fa-arrow-right ms-2"></i>';
                    echo '</a>';
                    echo '</div></div></div></div>';
                }
            } else {
                echo '<div class="col-12 text-center py-5">';
                echo '<div class="alert alert-info">Nous préparons de nouvelles aventures pour vous. Revenez bientôt !</div>';
                echo '</div>';
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <a href="voyages.php" class="btn btn-outline-primary btn-lg px-5 py-3">
                Explorer toutes nos destinations <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section - Version améliorée -->
<section class="cta-section py-5 text-white position-relative">
    <div class="container position-relative z-index-1">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-4 fw-bold mb-4">Prêt à vivre l'aventure ?</h2>
                <p class="lead mb-5">Abonnez-vous pour recevoir nos offres exclusives et inspirations de voyage</p>

                <form class="row g-3 justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="input-group input-group-lg shadow-lg">
                            <input type="email" class="form-control border-0 py-3" placeholder="Votre adresse email"
                                required>
                            <button class="btn btn-dark px-4" type="submit">
                                <i class="fas fa-paper-plane me-2"></i> S'abonner
                            </button>
                        </div>
                    </div>
                </form>

                <p class="small mt-4 opacity-75">Nous respectons votre vie privée. Désabonnez-vous à tout moment.</p>
            </div>
        </div>
    </div>
</section>

<!-- Styles CSS améliorés -->
<style>
/* Hero Carousel */
.hero-carousel {
    height: 100vh;
    min-height: 600px;
    max-height: 800px;
}

.carousel-image-wrapper {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 600px;
    max-height: 800px;
    overflow: hidden;
}

.carousel-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.7) 100%);
}

.carousel-caption {
    bottom: 0;
    top: 0;
    display: flex;
    align-items: center;
    padding-bottom: 8rem;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    background-color: transparent;
}

.carousel-indicators .active {
    background-color: white;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
}

/* Featured Voyages */
.featured-voyages {
    position: relative;
}

.card-img-container {
    height: 220px;
    overflow: hidden;
}

.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.hover-card:hover .card-img-container img {
    transform: scale(1.05);
}

.card-badges {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.card-badges .badge {
    font-size: 0.9rem;
    padding: 5px 10px;
    border-radius: 4px;
}

.divider {
    width: 80px;
    height: 3px;
    background: #3a7bd5;
    margin: 15px auto;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
    overflow: hidden;
}

.cta-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="%23ffffff" opacity="0.05"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" fill="%23ffffff" opacity="0.1"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23ffffff" opacity="0.15"/></svg>');
    background-size: cover;
    background-position: bottom;
    opacity: 0.2;
}

/* Responsive */
@media (max-width: 992px) {

    .hero-carousel,
    .carousel-image-wrapper {
        height: 70vh;
        min-height: 500px;
    }

    .carousel-caption {
        padding-bottom: 5rem;
    }
}

@media (max-width: 768px) {

    .hero-carousel,
    .carousel-image-wrapper {
        height: 60vh;
        min-height: 400px;
    }

    .carousel-caption {
        padding-bottom: 3rem;
    }

    .carousel-caption h2 {
        font-size: 2rem !important;
    }

    .card-badges {
        flex-direction: row;
        top: auto;
        bottom: 15px;
        right: 15px;
    }
}
</style>

<!-- Scripts JavaScript améliorés -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation du carousel
    const carousel = document.getElementById('voyageCarousel');

    carousel.addEventListener('slid.bs.carousel', function() {
        const activeItem = this.querySelector('.carousel-item.active');
        const caption = activeItem.querySelector('.carousel-caption');

        // Reset animation
        caption.classList.remove('animate__fadeInUp');
        void caption.offsetWidth; // Trigger reflow
        caption.classList.add('animate__fadeInUp');
    });

    // Activer la première animation
    const firstCaption = carousel.querySelector('.carousel-item.active .carousel-caption');
    firstCaption.classList.add('animate__fadeInUp');

    // Hover effect for cards
    const cards = document.querySelectorAll('.hover-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
</script>

<?php include('includes/footer.php'); ?>