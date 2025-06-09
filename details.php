 <?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Vérifier si id existe dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<div class='alert alert-danger text-center'>Voyage introuvable.</div>";
  include('includes/footer.php');
  exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM voyage WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
  $voyage = $result->fetch_assoc();
} else {
  echo "<div class='alert alert-danger text-center'>Ce voyage n'existe pas.</div>";
  include('includes/footer.php');
  exit;
}
?>

 <!-- Bootstrap CSS et JS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Font Awesome pour les icônes -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 <style>
body {
    background-color: #f8f9fa;
}

.voyage-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.voyage-image {
    height: 100%;
    max-height: 500px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.details-card {
    height: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: none;
}

.star-rating {
    color: #FFD700;
    font-size: 1.2rem;
}

.modal-content {
    max-height: 80vh;
    overflow-y: auto;
}

.programme-day {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.programme-day:last-child {
    border-bottom: none;
}

.avis-section {
    background-color: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
}

.btn-reserver {
    background-color: #28a745;
    border-color: #28a745;
    transition: all 0.3s;
}

.btn-reserver:hover {
    background-color: #218838;
    border-color: #1e7e34;
    transform: translateY(-2px);
}

.btn-programme {
    transition: all 0.3s;
}

.btn-programme:hover {
    transform: translateY(-2px);
}
 </style>

 <div class="voyage-container">
     <section class="mb-5">
         <div class="row g-4">
             <div class="col-md-12">
                 <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($voyage['titre']); ?></h1>
                 <hr class="my-4">
             </div>
         </div>

         <div class="row g-4">
             <div class="col-lg-6">
                 <?php
    $imageFolder = 'images/voyages/' . $voyage['image'] . '/';
    $images = glob($imageFolder . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    ?>

                 <?php if (!empty($images)): ?>
                 <div id="carouselVoyage" class="carousel slide" data-bs-ride="carousel">
                     <div class="carousel-inner">
                         <?php foreach ($images as $index => $image): ?>
                         <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                             <img src="<?php echo htmlspecialchars($image); ?>"
                                 class="d-block w-100 img-fluid voyage-image"
                                 alt="<?php echo htmlspecialchars($voyage['destination']); ?>">
                         </div>
                         <?php endforeach; ?>
                     </div>
                     <?php if (count($images) > 1): ?>
                     <button class="carousel-control-prev" type="button" data-bs-target="#carouselVoyage"
                         data-bs-slide="prev">
                         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                         <span class="visually-hidden">Précédent</span>
                     </button>
                     <button class="carousel-control-next" type="button" data-bs-target="#carouselVoyage"
                         data-bs-slide="next">
                         <span class="carousel-control-next-icon" aria-hidden="true"></span>
                         <span class="visually-hidden">Suivant</span>
                     </button>
                     <?php endif; ?>
                 </div>
                 <?php else: ?>
                 <p class="text-muted">Aucune image disponible.</p>
                 <?php endif; ?>
             </div>


             <div class="col-lg-6">
                 <div class=" details-card h-100">
                     <div class="card-body p-4">
                         <h3 class="card-title mb-4">Détails du voyage</h3>
                         <ul class="list-group list-group-flush mb-4">
                             <li class="list-group-item d-flex align-items-center">
                                 <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                                 <div>
                                     <strong>Destination :</strong>
                                     <span class="ms-2"><?php echo htmlspecialchars($voyage['destination']); ?></span>
                                 </div>
                             </li>
                             <li class="list-group-item d-flex align-items-center">
                                 <i class="fas fa-tag me-3 text-primary"></i>
                                 <div>
                                     <strong>Prix :</strong>
                                     <span class="ms-2"><?php echo number_format($voyage['prix'], 2); ?> DH</span>
                                 </div>
                             </li>
                             <li class="list-group-item d-flex align-items-center">
                                 <i class="fas fa-calendar-alt me-3 text-primary"></i>
                                 <div>
                                     <strong>Dates :</strong>
                                     <span class="ms-2">
                                         Du <?php echo date('d/m/Y', strtotime($voyage['date_depart'])); ?>
                                         au <?php echo date('d/m/Y', strtotime($voyage['date_retour'])); ?>
                                     </span>
                                 </div>
                             </li>
                         </ul>

                         <h4 class="mt-4 mb-3">Description :</h4>
                         <p class="card-text"><?php echo nl2br(htmlspecialchars($voyage['description'])); ?></p>

                         <div class="d-flex flex-wrap gap-3 mt-4">
                             <!-- Bouton Programme -->
                             <button type="button" class="btn btn-outline-primary btn-programme" data-bs-toggle="modal"
                                 data-bs-target="#programmeModal">
                                 <i class="far fa-calendar-alt me-2"></i> Programme détaillé
                             </button>

                             <!-- Bouton Réservation -->
                             <?php if (isset($_SESSION['user_id'])): ?>
                             <a href="reservation.php?id=<?php echo $voyage['id']; ?>"
                                 class="btn btn-reserver text-white">
                                 <i class="fas fa-shopping-cart me-2"></i> Réserver maintenant
                             </a>
                             <?php else: ?>
                             <button class="btn btn-reserver text-white" onclick="showLoginAlert()">
                                 <i class="fas fa-shopping-cart me-2"></i> Réserver maintenant
                             </button>
                             <?php endif; ?>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     <!-- Modal Programme -->
     <div class="modal fade" id="programmeModal" tabindex="-1" aria-labelledby="programmeModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
             <div class="modal-content">
                 <div class="modal-header bg-primary text-white">
                     <h5 class="modal-title" id="programmeModalLabel">Programme détaillé du voyage</h5>
                     <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                         aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="accordion" id="programmeAccordion">
                         <?php
            $stmt = $conn->prepare("SELECT * FROM programme_jour WHERE voyage_id = ? ORDER BY jour ASC");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($p = $res->fetch_assoc()):
            ?>
                         <div class="accordion-item mb-2 border">
                             <h2 class="accordion-header" id="heading<?php echo $p['jour']; ?>">
                                 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                     data-bs-target="#collapse<?php echo $p['jour']; ?>" aria-expanded="false"
                                     aria-controls="collapse<?php echo $p['jour']; ?>">
                                     <span class="fw-bold me-2">Jour <?php echo $p['jour']; ?>:</span>
                                     <?php echo htmlspecialchars($p['titre']); ?>
                                     <?php if (!empty($p['heure_debut']) && !empty($p['heure_fin'])): ?>
                                     <span class="badge bg-secondary ms-2"><?php echo $p['heure_debut']; ?> -
                                         <?php echo $p['heure_fin']; ?></span>
                                     <?php endif; ?>
                                 </button>
                             </h2>
                             <div id="collapse<?php echo $p['jour']; ?>" class="accordion-collapse collapse"
                                 aria-labelledby="heading<?php echo $p['jour']; ?>"
                                 data-bs-parent="#programmeAccordion">
                                 <div class="accordion-body bg-light">
                                     <?php echo nl2br(htmlspecialchars($p['description'])); ?>
                                 </div>
                             </div>
                         </div>
                         <?php endwhile; ?>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                 </div>
             </div>
         </div>
     </div>

     <!-- Section Avis -->
     <section class="avis-section">
         <div class="row">
             <div class="col-md-12">
                 <div class="d-flex justify-content-between align-items-center mb-4">
                     <h3 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i>Avis des voyageurs</h3>
                     <?php if (isset($_SESSION['user_id'])): ?>
                     <a href="client/ajouter_avis.php?voyage_id=<?php echo $voyage['id']; ?>"
                         class="btn btn-outline-primary">
                         <i class="fas fa-edit me-2"></i> Laisser un avis
                     </a>
                     <?php endif; ?>
                 </div>

                 <?php
        $avis_stmt = $conn->prepare("SELECT a.commentaire, a.note, a.date_publication, u.nom 
                                   FROM avis a 
                                   JOIN utilisateur u ON a.utilisateur_id = u.id 
                                   WHERE a.voyage_id = ? 
                                   ORDER BY a.date_publication DESC");
        $avis_stmt->bind_param("i", $id);
        $avis_stmt->execute();
        $avis_result = $avis_stmt->get_result();
        
        if ($avis_result->num_rows > 0):
          while ($avis = $avis_result->fetch_assoc()):
        ?>
                 <div class="mb-4 pb-3 border-bottom avis-item">
                     <div class="d-flex justify-content-between align-items-start">
                         <div>
                             <h5 class="mb-1"><?php echo htmlspecialchars($avis['nom']); ?></h5>
                             <div class="star-rating mb-2">
                                 <?php echo str_repeat('<i class="fas fa-star"></i>', $avis['note']); ?>
                                 <?php echo str_repeat('<i class="far fa-star"></i>', 5 - $avis['note']); ?>
                             </div>
                         </div>
                         <small
                             class="text-muted"><?php echo date('d/m/Y', strtotime($avis['date_publication'])); ?></small>
                     </div>
                     <p class="mb-0"><?php echo nl2br(html_entity_decode($avis['commentaire'])); ?>
                     </p>
                 </div>
                 <?php 
          endwhile;
        else:
        ?>
                 <div class="text-center py-4">
                     <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                     <p class="text-muted">Aucun avis pour ce voyage pour le moment.</p>
                 </div>
                 <?php endif; ?>
             </div>
         </div>
     </section>
 </div>

 <script>
function showLoginAlert() {
    Swal.fire({
        title: 'Connexion requise',
        text: "Vous devez vous connecter pour effectuer une réservation.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Se connecter',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'login.php';
        }
    });
}

// Animation pour les avis
document.addEventListener('DOMContentLoaded', function() {
    const avisItems = document.querySelectorAll('.avis-item');
    avisItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'all 0.5s ease';

        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 20 * index);
    });
});
 </script>

 <!-- SweetAlert pour les notifications -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <?php include('includes/footer.php'); ?>