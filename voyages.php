<?php
session_start();
include('includes/db.php');
include('includes/header.php');
?>

<!-- Hero Section - Amélioré avec fond dynamique -->
<section class="hero-section position-relative overflow-hidden">
    <div class="container py-5">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4 text-white animate__animated animate__fadeInDown">Découvrez nos
                    voyages exceptionnels</h1>
                <p class="lead mb-5 text-white-75 animate__animated animate__fadeIn animate__delay-1s">Des expériences
                    uniques à travers le Maroc, soigneusement sélectionnées pour vous</p>
                <a href="#voyages"
                    class="btn btn-dark btn-lg px-5 py-3 animate__animated animate__fadeInUp animate__delay-1s">
                    Explorer nos offres <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="hero-wave"></div>
</section>

<!-- Voyages Section - Structure maintenue mais améliorée -->
<section id="voyages" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary">Nos Destinations Phares</h2>
            <p class="lead text-muted">Trouvez l'aventure qui vous correspond</p>
            <div class="divider mx-auto bg-primary"></div>
        </div>

        <!-- Filtres - Même structure mais avec améliorations UI -->
        <div class="filter-bar mb-4">
            <select class="form-select shadow-sm" id="filter-destination">
                <option value="">Toutes destinations</option>
                <?php
                $destinations = $conn->query("SELECT DISTINCT destination FROM voyage ORDER BY destination");
                while($dest = $destinations->fetch_assoc()) {
                    echo '<option value="'.htmlspecialchars($dest['destination']).'">'.htmlspecialchars($dest['destination']).'</option>';
                }
                ?>
            </select>

            <select class="form-select shadow-sm" id="filter-price">
                <option value="">Tous les prix</option>
                <option value="0-2000">Moins de 2000 DH</option>
                <option value="2000-5000">2000 à 5000 DH</option>
                <option value="5000-10000">5000 à 10000 DH</option>
                <option value="10000">Plus de 10000 DH</option>
            </select>

            <button class="btn btn-primary shadow-sm" id="filter-btn">
                <i class="fas fa-filter me-2"></i>Filtrer
            </button>
        </div>

        <!-- Liste des voyages - Même structure HTML -->
        <div class="row g-4" id="voyage-container">
            <?php
            $query = "SELECT * FROM voyage ORDER BY date_depart ASC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6 voyage-item" 
                         data-destination="'.htmlspecialchars($row['destination']).'" 
                         data-price="'.$row['prix'].'">';
                    echo '<div class="card border-0 shadow-sm h-100 overflow-hidden hover-scale">';
                    
                    // Image avec badge
                    echo '<div class="position-relative overflow-hidden" style="height: 250px;">';
                    echo '<img src="images/'.htmlspecialchars($row['image']).'" class="img-fluid w-100 h-100 object-cover" alt="'.htmlspecialchars($row['destination']).'" loading="lazy">';
                    echo '<span class="position-absolute top-0 end-0 bg-primary text-white p-2 fw-bold">'.number_format($row['prix'], 0, ',', ' ').' DH</span>';
                    
                    if(!empty($row['promotion'])) {
                        echo '<span class="position-absolute top-0 start-0 bg-danger text-white p-2 fw-bold">-'.$row['promotion'].'%</span>';
                    }
                    
                    echo '</div>';
                    
                    // Corps de la carte
                    echo '<div class="card-body d-flex flex-column">';
                    echo '<div class="mb-3">';
                    echo '<h3 class="h4 card-title">'.htmlspecialchars($row['titre']).'</h3>';
                    echo '<p class="text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>'.htmlspecialchars($row['destination']).'</p>';
                    echo '<p class="card-text text-truncate-3">'.htmlspecialchars($row['description']).'</p>';
                    echo '</div>';
                    
                    // Métadonnées
                    echo '<div class="mt-auto">';
                    echo '<div class="d-flex flex-wrap gap-2 mb-3">';
                    echo '<span class="badge bg-light text-dark border"><i class="fas fa-calendar-day me-1"></i> '.date('d/m/Y', strtotime($row['date_depart'])).'</span>';
                    if(!empty($row['duree'])) {
                        echo '<span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i> '.$row['duree'].' jours</span>';
                    }
                    echo '</div>';
                    
                    // Bouton
                    echo '<a href="details.php?id='.$row['id'].'" class="btn btn-outline-primary w-100 stretched-link">';
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

        <!-- Pagination - Même structure -->
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<!-- Newsletter Section - Même structure mais améliorée -->
<section class="newsletter-section bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-4">Ne manquez pas nos meilleures offres</h2>
                <p class="lead mb-5">Abonnez-vous à notre newsletter pour recevoir les promotions exclusives</p>

                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group input-group-lg shadow">
                            <input type="email" class="form-control" placeholder="Votre adresse email" required>
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-paper-plane me-2"></i>S'abonner
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Styles améliorés -->
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, rgba(58, 123, 213, 0.9), rgba(0, 210, 255, 0.8)),
        url('images/hero-bg.jpg') center/cover no-repeat;
    padding: 100px 0;
    position: relative;
}

.hero-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="%23f8f9fa" opacity=".25"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" fill="%23f8f9fa" opacity=".5"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23f8f9fa"/></svg>');
    background-size: cover;
}

/* Filtres */
.filter-bar {
    max-width: 800px;
    margin: 0 auto 30px;
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.filter-bar select,
.filter-bar button {
    flex: 1;
    min-width: 200px;
}

/* Cartes de voyage */
.hover-scale {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-scale:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
}

.voyage-item {
    opacity: 0;
    animation: fadeInUp 0.6s forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Délais d'animation pour chaque carte */
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

/* Texte tronqué */
.text-truncate-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Divider */
.divider {
    width: 80px;
    height: 3px;
    background: #3a7bd5;
    margin: 15px auto;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 80px 0;
    }

    .filter-bar {
        flex-direction: column;
    }

    .filter-bar select,
    .filter-bar button {
        width: 100%;
    }
}
</style>

<!-- Scripts améliorés -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>

<script>
// Filtrage amélioré avec animations
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filter-btn');
    const destinationFilter = document.getElementById('filter-destination');
    const priceFilter = document.getElementById('filter-price');
    const voyageContainer = document.getElementById('voyage-container');
    const voyageItems = document.querySelectorAll('.voyage-item');

    filterBtn.addEventListener('click', function() {
        const selectedDestination = destinationFilter.value;
        const selectedPrice = priceFilter.value;

        voyageItems.forEach((item, index) => {
            const itemDestination = item.dataset.destination;
            const itemPrice = parseInt(item.dataset.price);

            let destinationMatch = selectedDestination === '' || itemDestination ===
                selectedDestination;
            let priceMatch = true;

            if (selectedPrice !== '') {
                const [min, max] = selectedPrice.split('-').map(Number);
                priceMatch = max ? (itemPrice >= min && itemPrice <= max) : (itemPrice >= min);
            }

            if (destinationMatch && priceMatch) {
                item.style.display = 'block';
                item.style.animation = `fadeInUp 0.5s forwards ${index * 0.1}s`;
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Animation au scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = `fadeInUp 0.6s forwards`;
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.voyage-item').forEach(item => {
        observer.observe(item);
    });
});
</script>

<?php include('includes/footer.php'); ?>