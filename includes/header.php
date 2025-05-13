<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } 
 $prefix=isset($admin) ? '../' : '' ;
    $notif_non_lu=0; $notif_count=0; if (isset($_SESSION['user_id'])) { include_once($prefix . 'includes/db.php' ); if
    ($_SESSION['user_role']==='client' ) { $stmtNotif=$conn->prepare("SELECT COUNT(*) as total FROM notification WHERE
    utilisateur_id = ? AND lu = 'non'");
    $stmtNotif->bind_param("i", $_SESSION['user_id']);
    $stmtNotif->execute();
    $resNotif = $stmtNotif->get_result();
    $notif_non_lu = $resNotif->fetch_assoc()['total'];
    } elseif ($_SESSION['user_role'] === 'admin') {
    $res = $conn->query("SELECT COUNT(*) as total FROM message_contact WHERE statut = 'non lu'");
    $notif_count = $res->fetch_assoc()['total'];
    }
    }
    ?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence de Voyages</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- CSS Personnalisé -->
    <link rel="stylesheet" href="<?php echo $prefix; ?>css/style.css">
</head>

<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-body-tertiary shadow-sm sticky-top py-4 ">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center text-primary"
                href="<?php echo $prefix; ?><?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'admin/dashboard.php' : 'index.php'; ?>">
                <i class="fas  me-2"></i>
                <?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? 'Admin Voyages' : '🌍 Voyages Maroc'); ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- Visiteur -->
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>index.php"><i
                                class="fas fa-home me-1"></i>
                            Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>voyages.php"><i
                                class="fas fa-map-marked-alt me-1"></i> Voyages</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="#" id="visitorDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-info-circle me-1"></i> Plus
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="visitorDropdown">
                            <li><a class="dropdown-item  " href="<?php echo $prefix; ?>apropos.php"><i
                                        class="fas fa-question-circle me-2"></i>À propos</a></li>
                            <li><a class="dropdown-item " href="<?php echo $prefix; ?>contact.php"><i
                                        class="fas fa-envelope me-2"></i>Contact</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item  " href="<?php echo $prefix; ?>login.php"><i
                                        class="fas fa-sign-in-alt me-2"></i>Connexion</a></li>
                            <li><a class="dropdown-item " href="<?php echo $prefix; ?>register.php"><i
                                        class="fas fa-user-plus me-2"></i>Inscription</a></li>
                        </ul>
                    </li>

                    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                    <!-- Admin -->
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>admin/voyages.php"><i
                                class="fas fa-map-marked-alt me-1"></i> Voyages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>admin/avis.php"><i
                                class="fas fa-comments me-1"></i> Avis</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative text-primary" href="#" id="adminDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li>
                                <a class="dropdown-item" href="<?php echo $prefix; ?>admin/message_contact.php">
                                    <i class="fas fa-envelope me-2"></i> Messages
                                    <?php if ($notif_count > 0): ?>
                                    <span class="badge bg-danger float-end"><?php echo $notif_count; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo $prefix; ?>logout.php"><i
                                        class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                        </ul>
                    </li>

                    <?php elseif ($_SESSION['user_role'] === 'client'): ?>
                    <!-- Client -->
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>index.php"><i
                                class="fas fa-home me-1"></i>
                            Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>voyages.php"><i
                                class="fas fa-map-marked-alt me-1"></i> Voyages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="<?php echo $prefix; ?>apropos.php"><i
                                class="fas fa-info-circle me-1"></i> À propos</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative text-primary" href="#" id="clientDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Mon compte
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="clientDropdown">
                            <li>
                                <a class="dropdown-item" href="<?php echo $prefix; ?>client/profil.php">
                                    <i class="fas fa-user me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $prefix; ?>client/notifications.php">
                                    <i class="fas fa-bell me-2"></i> Notifications
                                    <?php if ($notif_non_lu > 0): ?>
                                    <span class="badge bg-danger float-end"><?php echo $notif_non_lu; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo $prefix; ?>contact.php"><i
                                        class="fas fa-envelope me-2"></i> Contact</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?php echo $prefix; ?>logout.php"><i
                                        class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>


                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="container my-4">
        <!-- Le contenu de votre page ira ici -->
    </main>


    <!-- Scripts -->
    <!-- Bootstrap JS avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (optionnel) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Scripts personnalisés -->
    <script>
    // Animation au scroll
    $(document).ready(function() {
        // Activer les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Changer la navbar au scroll
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.navbar').addClass('navbar-scrolled shadow');
            } else {
                $('.navbar').removeClass('navbar-scrolled shadow');
            }
        });

        // Notification toast example
        <?php if (isset($_SESSION['notification'])): ?>
        const notificationToast = new bootstrap.Toast(document.getElementById('notificationToast'));
        notificationToast.show();
        <?php unset($_SESSION['notification']); ?>
        <?php endif; ?>
    });

    // Fonction pour basculer entre les thèmes clair/sombre
    function toggleTheme() {
        const htmlEl = document.documentElement;
        const currentTheme = htmlEl.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        htmlEl.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    }

    // Appliquer le thème sauvegardé
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>

    <!-- Exemple de toast pour notifications (peut être utilisé pour afficher des messages) -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="notificationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-primary text-white">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Bienvenue sur notre site!
            </div>
        </div>
    </div>
</body>

</html>