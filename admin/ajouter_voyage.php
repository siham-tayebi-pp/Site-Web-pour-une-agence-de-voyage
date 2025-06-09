<?php
session_start();
$admin = true;
include('../includes/db.php');
include('../includes/auth.php');

if ($_SESSION['user_role'] !== 'admin') {
  header("Location: ../index.php");
  exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titre = $_POST['titre'];
  $destination = $_POST['destination'];
  $description = $_POST['description'];
  $date_depart = $_POST['date_depart'];
  $date_retour = $_POST['date_retour'];
  $prix = floatval($_POST['prix']);
  $places = intval($_POST['places_disponibles']);

  // Création du nom du dossier pour les images
  $nomSrc = preg_replace('/[^a-z0-9]/', '-', strtolower($titre));
$nomDest = preg_replace('/[^a-z0-9]/', '-', strtolower($destination));
$dateDep = preg_replace('/[^0-9]/', '', $date_depart); // format YYYYMMDD

$dossierImages = substr($nomSrc, 0, 20) . '-' . substr($nomDest, 0, 20) . '-' . $dateDep;

  $dossierChemin = "../images/voyages/" . $dossierImages;

  // Création du dossier s'il n'existe pas
  if (!file_exists($dossierChemin)) {
    mkdir($dossierChemin, 0755, true);
  }

  // Gestion des images multiples
  $imagesUploaded = false;
  if (!empty($_FILES['images']['name'][0])) {
    $imagesUploaded = true;
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
      $imageName = basename($_FILES['images']['name'][$key]);
      $imagePath = $dossierChemin . "/" . $imageName;
      
      // Vérification du type de fichier
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      $fileType = mime_content_type($tmp_name);
      
      if (in_array($fileType, $allowedTypes)) {
        move_uploaded_file($tmp_name, $imagePath);
      }
    }
  }

  // Insertion dans la base de données
  $stmt = $conn->prepare("INSERT INTO voyage (titre, destination, description, date_depart, date_retour, prix, places_disponibles, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssdss", $titre, $destination, $description, $date_depart, $date_retour, $prix, $places, $dossierImages);

  if ($stmt->execute()) {
    $success = "Voyage ajouté avec succès.";
    $contenuNotif = "🌍 Nouvelle offre: Le voyage '$titre' vient d'être ajouté !";
    $resUsers = $conn->query("SELECT id FROM utilisateur");
  
    while ($u = $resUsers->fetch_assoc()) {
      $userId = $u['id'];
      $stmtNotif = $conn->prepare("INSERT INTO notification (utilisateur_id, type, contenu) VALUES (?, 'nouveau_voyage', ?)");
      $stmtNotif->bind_param("is", $userId, $contenuNotif);
      $stmtNotif->execute();
    }
  } else {
    $error = "Erreur lors de l'ajout du voyage: " . $conn->error;
    
    // Suppression du dossier si l'insertion a échoué
    if ($imagesUploaded && file_exists($dossierChemin)) {
      array_map('unlink', glob("$dossierChemin/*"));
      rmdir($dossierChemin);
    }
  }
}

include('../includes/header.php');
?>

<!-- Page d'ajout améliorée -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class=" shadow-lg border-0 rounded-3 overflow-hidden">
                <!-- En-tête de carte -->
                <div class="card-header bg-primary text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter un nouveau voyage
                        </h1>
                        <a href="voyages.php" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>

                <!-- Corps de carte -->
                <div class="card-body p-4">
                    <!-- Messages d'état -->
                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-4"></i>
                            <div><?php echo $success; ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2 fs-4"></i>
                            <div><?php echo $error; ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire amélioré -->
                    <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                        <div class="row g-3">
                            <!-- Titre -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="titre" name="titre" required
                                        minlength="3" maxlength="100">
                                    <label for="titre">Titre du voyage</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir un titre valide (3-100 caractères).
                                    </div>
                                </div>
                            </div>

                            <!-- Destination -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="destination" name="destination" required
                                        minlength="3" maxlength="50">
                                    <label for="destination">Destination</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir une destination valide (3-50 caractères).
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="description" name="description"
                                        style="min-height: 120px" required minlength="10" maxlength="1000"></textarea>
                                    <label for="description">Description</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir une description valide (10-1000 caractères).
                                    </div>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" value="2025-09-10" class="form-control" id="date_depart"
                                        name="date_depart" required min="<?php echo date('Y-m-d'); ?>">
                                    <label for="date_depart">Date de départ</label>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner une date valide.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" value="2025-09-16" class="form-control" id="date_retour"
                                        name="date_retour" required min="<?php echo date('Y-m-d'); ?>">
                                    <label for="date_retour">Date de retour</label>
                                    <div class="invalid-feedback">
                                        La date de retour doit être après la date de départ.
                                    </div>
                                </div>
                            </div>

                            <!-- Prix et Places -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="prix" name="prix" step="0.01" required
                                        min="0" max="99999">
                                    <label for="prix">Prix (DH)</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir un prix valide (0-99999 DH).
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="places_disponibles"
                                        name="places_disponibles" required min="0" max="999">
                                    <label for="places_disponibles">Places disponibles</label>
                                    <div class="invalid-feedback">
                                        Veuillez saisir un nombre de places valide (0-999).
                                    </div>
                                </div>
                            </div>

                            <!-- Images multiples -->
                            <div class="col-12">
                                <div class="mb-4">
                                    <label for="images" class="form-label">Images du voyage (plusieurs
                                        possibles)</label>
                                    <input class="form-control" type="file" id="images" name="images[]" accept="image/*"
                                        multiple>
                                    <div class="form-text">Formats acceptés : JPEG, PNG, GIF - max 2MB par image</div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Réinitialiser
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Ajouter le voyage
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style CSS -->
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
}

.form-floating>label {
    color: #6c757d;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.btn-primary {
    background-color: #0d6efd;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
}

.alert {
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #198754;
}

.alert-danger {
    border-left-color: #dc3545;
}

.invalid-feedback {
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.25rem;
    }

    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const dateDepart = document.getElementById('date_depart');
    const dateRetour = document.getElementById('date_retour');

    // Validation personnalisée
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            // Validation des dates
            if (dateDepart && dateRetour) {
                if (new Date(dateRetour.value) <= new Date(dateDepart.value)) {
                    dateRetour.setCustomValidity(
                        'La date de retour doit être après la date de départ');
                } else {
                    dateRetour.setCustomValidity('');
                }
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Mise à jour dynamique de la date minimum de retour
    if (dateDepart && dateRetour) {
        dateDepart.addEventListener('change', function() {
            dateRetour.min = this.value;
            if (new Date(dateRetour.value) < new Date(this.value)) {
                dateRetour.value = this.value;
            }
        });
    }

    // Animation des labels
    const floatLabels = document.querySelectorAll(
        '.form-floating input, .form-floating textarea, .form-floating select');
    floatLabels.forEach(el => {
        el.addEventListener('focus', function() {
            this.parentNode.querySelector('label').style.color = '#0d6efd';
            this.parentNode.querySelector('label').style.transform =
                'scale(0.85) translateY(-0.5rem) translateX(0.15rem)';
        });

        el.addEventListener('blur', function() {
            this.parentNode.querySelector('label').style.color = '#6c757d';
            if (!this.value) {
                this.parentNode.querySelector('label').style.transform = '';
            }
        });
    });
});
</script>

<?php include('../includes/footer.php'); ?>