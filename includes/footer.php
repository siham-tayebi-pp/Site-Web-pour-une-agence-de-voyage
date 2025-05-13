<?php $prefix = isset($admin) ? '../' : ''; ?>

<style>
/* Style du footer - en harmonie avec le header */
.main-footer {
    background-color: #007BFF;
    color: white;
    padding: 40px 0 20px;
    position: relative;
    margin-top: 50px;
}

.footer-top-border {
    height: 4px;
    background: linear-gradient(90deg, #0056b3, #007BFF, #00a8ff);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 30px;
}

.footer-section {
    flex: 1;
    min-width: 200px;
}

.footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 1.5rem;
    font-weight: bold;
}

.footer-links h4 {
    font-size: 1.2rem;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-links h4::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background: white;
}

.footer-links ul {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.footer-links a:hover {
    color: white;
    padding-left: 5px;
}

.footer-links a i {
    margin-right: 8px;
    font-size: 0.9rem;
}

.social-links {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.social-links a {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
}

.footer-bottom {
    text-align: center;
    padding-top: 30px;
    margin-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
}

.back-to-top {
    position: absolute;
    right: 30px;
    top: -20px;
    background: white;
    color: #007BFF;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        gap: 30px;
    }

    .footer-section {
        text-align: center;
    }

    .footer-links h4::after {
        left: 50%;
        transform: translateX(-50%);
    }

    .social-links {
        justify-content: center;
    }

    .back-to-top {
        right: 15px;
        top: -15px;
        width: 30px;
        height: 30px;
        font-size: 1rem;
    }
}
</style>

<footer class="main-footer">
    <div class="footer-top-border"></div>
    <a href="#" class="back-to-top" title="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </a>

    <div class="footer-container">
        <div class="footer-content">
            <!-- Section Logo et description -->
            <div class="footer-section">
                <div class="footer-logo">
                    <i class="fas fa-plane me-2"></i>
                    Voyages Maroc
                </div>
                <p style="opacity: 0.8; line-height: 1.6;">
                    Découvrez les merveilles du Maroc avec nos voyages exceptionnels et nos services haut de gamme.
                </p>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Liens rapides -->
            <div class="footer-section">
                <div class="footer-links">
                    <h4>Liens rapides</h4>
                    <ul>
                        <li><a href="<?php echo $prefix; ?>index.php"><i class="fas fa-home"></i> Accueil</a></li>
                        <li><a href="<?php echo $prefix; ?>voyages.php"><i class="fas fa-map-marked-alt"></i>
                                Voyages</a></li>
                        <li><a href="<?php echo $prefix; ?>apropos.php"><i class="fas fa-info-circle"></i> À propos</a>
                        </li>
                        <li><a href="<?php echo $prefix; ?>contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                    </ul>
                </div>
            </div>

            <!-- Informations -->
            <div class="footer-section">
                <div class="footer-links">
                    <h4>Informations</h4>
                    <ul>
                        <li><a href="#"><i class="fas fa-file-contract"></i> Mentions légales</a></li>
                        <li><a href="#"><i class="fas fa-shield-alt"></i> Politique de confidentialité</a></li>
                        <li><a href="#"><i class="fas fa-question-circle"></i> FAQ</a></li>
                        <li><a href="#"><i class="fas fa-suitcase"></i> Conditions de voyage</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact -->
            <div class="footer-section">
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Avenue Mohammed V, Casablanca</li>
                        <li><i class="fas fa-phone me-2"></i> +212 6 12 34 56 78</li>
                        <li><i class="fas fa-envelope me-2"></i> contact@voyagesmaroc.com</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Voyages Maroc. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- Script pour le bouton "Retour en haut" -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.querySelector('.back-to-top');

    // Afficher/cacher le bouton selon le scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.style.opacity = '1';
            backToTop.style.visibility = 'visible';
        } else {
            backToTop.style.opacity = '0';
            backToTop.style.visibility = 'hidden';
        }
    });

    // Animation douce pour remonter
    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>