<?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Récupération des voyages pour le carousel
$query = "SELECT * FROM voyage ORDER BY date_depart ASC LIMIT 5";
$result = $conn->query($query);
$voyages = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- Carousel Multimédia Amélioré -->
<section class="mb-5 full-height-carousel">
    <div id="voyageCarousel" class="carousel slide carousel-dark  py-4" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Indicateurs -->
        <div class="carousel-indicators">
            <?php foreach ($voyages as $index => $voyage): ?>
            <button type="button" data-bs-target="#voyageCarousel" data-bs-slide-to="<?= $index ?>"
                class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner rounded-4 overflow-hidden shadow-lg" style="height: 70vh;">
            <?php foreach ($voyages as $index => $voyage): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="6000">
                <div class="position-relative h-100">
                    <img src="images/<?= htmlspecialchars($voyage['image']) ?>"
                        class="d-block w-100 h-100 object-fit-cover"
                        alt="<?= htmlspecialchars($voyage['destination']) ?>">
                    <div class="carousel-overlay"></div>

                    <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 text-center">
                                    <h2 class="display-4 fw-bold mb-3 text-shadow">
                                        <?= htmlspecialchars($voyage['titre']) ?></h2>
                                    <div class="d-flex justify-content-center gap-3 mb-4">
                                        <span class="badge bg-primary bg-opacity-75 fs-6">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <?= date('d/m/Y', strtotime($voyage['date_depart'])) ?>
                                        </span>
                                        <span class="badge bg-success bg-opacity-75 fs-6">
                                            <i class="fas fa-tag me-2"></i>
                                            <?= number_format($voyage['prix'], 0, ',', ' ') ?> DH
                                        </span>
                                    </div>
                                    <p class="lead text-shadow mb-4 d-none d-md-block">
                                        <?= substr(htmlspecialchars($voyage['description']), 0, 150) ?>...
                                    </p>
                                    <a href="details.php?id=<?= $voyage['id'] ?>"
                                        class="btn btn-light btn-lg px-4 py-2 fw-bold">
                                        Découvrir ce voyage <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Contrôles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#voyageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark bg-opacity-50 rounded-circle p-3"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#voyageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark bg-opacity-50 rounded-circle p-3"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
</section>

<style>
/* Styles personnalisés pour le carousel */
.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.7) 100%);
}

.carousel-caption {
    bottom: auto;
    padding-bottom: 5rem;
}

.text-shadow {
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-size: 1.5rem;
    transition: all 0.3s ease;
}

.carousel-control-prev:hover .carousel-control-prev-icon,
.carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.7) !important;
}

.object-fit-cover {
    object-fit: cover;
    object-position: center;
}

/* Animation de transition entre slides */
.carousel-item {
    transition: transform 1.2s ease-in-out;
}
</style>

<script>
// Configuration avancée du carousel
document.addEventListener('DOMContentLoaded', function() {
    const carousel = new bootstrap.Carousel('#voyageCarousel', {
        interval: 6000, // 6 secondes entre chaque slide
        ride: 'carousel', // Défilement automatique
        wrap: true, // Boucler indéfiniment
        pause: 'hover', // Pause au survol
        touch: true // Activer le glissement tactile
    });

    // Animation supplémentaire pour les éléments du carousel
    const animateItems = () => {
        const activeItem = document.querySelector('#voyageCarousel .carousel-item.active');
        if (activeItem) {
            const caption = activeItem.querySelector('.carousel-caption');
            caption.style.opacity = '0';
            caption.style.transform = 'translateY(20px)';

            setTimeout(() => {
                caption.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                caption.style.opacity = '1';
                caption.style.transform = 'translateY(0)';
            }, 100);
        }
    };

    // Animer au chargement initial
    animateItems();

    // Animer à chaque changement de slide
    document.getElementById('voyageCarousel').addEventListener('slid.bs.carousel', animateItems);
});
</script>

<!-- Voyages en vedette - Version améliorée -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-gradient-primary">Nos voyages organisés</h2>
            <p class="lead text-muted">Découvrez nos meilleures offres du moment</p>
            <div class="divider mx-auto bg-primary"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            $query = "SELECT * FROM voyage ORDER BY date_depart ASC LIMIT 3";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6">';
                    echo '<div class="card border-0 shadow-lg h-100 overflow-hidden hover-scale">';
                    
                    // Image avec badge de prix
                    echo '<div class="position-relative">';
                    echo '<img src="images/' . $row['image'] . '" class="card-img-top" alt="' . htmlspecialchars($row['destination']) . '" style="height: 220px; object-fit: cover;">';
                    echo '<span class="position-absolute top-0 end-0 bg-primary text-white p-2 fw-bold">' . number_format($row['prix'], 0, ',', ' ') . ' DH</span>';
                    
                    // Badge de promotion (optionnel)
                    if ($row['promotion']) {
                        echo '<span class="position-absolute top-0 start-0 bg-danger text-white p-2 small">-'.$row['promotion'].'%</span>';
                    }
                    
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
                    echo '<span class="badge bg-light text-dark border"><i class="fas fa-calendar-alt me-1"></i> ' . date('d/m/Y', strtotime($row['date_depart'])) . '</span>';
                    echo '<span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i> ' . $row['duree'] . ' jours</span>';
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
            <a href="voyages.php" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill">
                Explorer toutes nos destinations <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Section CTA - Version améliorée -->
<section class="py-5 bg-gradient-primary text-white position-relative overflow-hidden">
    <div class="bg-pattern"></div>
    <div class="container position-relative z-index-1">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-4 fw-bold mb-4">Prêt à vivre l'aventure ?</h2>
                <p class="lead mb-5">Abonnez-vous pour recevoir nos offres exclusives et inspirations de voyage
                    directement dans votre boîte mail.</p>

                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group input-group-lg shadow">
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

<style>
/* Styles personnalisés */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #00a8ff 100%);
}

.text-gradient-primary {
    background: linear-gradient(90deg, #007bff, #00a8ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: inline-block;
}

.hover-scale {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-scale:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.divider {
    width: 80px;
    height: 3px;
    background: #007bff;
    margin-top: 20px;
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

.z-index-1 {
    position: relative;
    z-index: 1;
}

.rounded-pill {
    border-radius: 50rem !important;
}
</style>

<script>
// Initialisation du carousel avec autoplay
document.addEventListener('DOMContentLoaded', function() {
    const myCarousel = new bootstrap.Carousel('#mainCarousel', {
        interval: 5000,
        ride: 'carousel',
        wrap: true
    });

    // Pause video quand slide inactive
    const video = document.querySelector('#mainCarousel video');
    const carousel = document.getElementById('mainCarousel');

    carousel.addEventListener('slid.bs.carousel', function() {
        const activeItem = this.querySelector('.active');
        const activeVideo = activeItem.querySelector('video');

        // Pause toutes les vidéos
        document.querySelectorAll('#mainCarousel video').forEach(v => {
            v.pause();
        });

        // Play la vidéo active si elle existe
        if (activeVideo) {
            activeVideo.play();
        }
    });

    // Play la première vidéo au chargement
    if (video) {
        video.play();
    }
});
</script>

<?php include('includes/footer.php'); ?>