/**
 * Main JavaScript file for the Travel Agency website
 * Handles general functionality, animations, and user interactions
 */

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Initialize all components
    initializeScrollEffects();
    initializeFormValidation();
    initializeImageLazyLoading();
    initializeTooltips();
    initializeSearchFilters();
    initializeCarousels();
    initializeModals();

    // Initialize admin specific features if on admin pages
    if (document.body.classList.contains('admin-page') || window.location.pathname.includes('/admin/')) {
        initializeAdminFeatures();
    }
});

/**
 * Scroll effects and animations
 */
function initializeScrollEffects() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Fade in animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.voyage-card, .service-item, .value-card, .team-card').forEach(el => {
        observer.observe(el);
    });

    // Navbar scroll effect
    let lastScrollY = window.scrollY;
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }

            // Hide/show navbar on scroll
            if (window.scrollY > lastScrollY && window.scrollY > 200) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            lastScrollY = window.scrollY;
        }
    });
}

/**
 * Form validation and enhancement
 */
function initializeFormValidation() {
    // Enhanced form validation
    const forms = document.querySelectorAll('form[novalidate]');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                // Focus first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    showFieldError(firstInvalid);
                }
            }
            form.classList.add('was-validated');
        });

        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function () {
                validateField(this);
            });

            input.addEventListener('input', function () {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
    });

    // Custom validation messages
    function validateField(field) {
        const isValid = field.checkValidity();
        field.classList.toggle('is-valid', isValid);
        field.classList.toggle('is-invalid', !isValid);

        if (!isValid) {
            showFieldError(field);
        } else {
            hideFieldError(field);
        }
    }

    function showFieldError(field) {
        const error = getCustomErrorMessage(field);
        let errorElement = field.parentNode.querySelector('.invalid-feedback');

        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            field.parentNode.appendChild(errorElement);
        }

        errorElement.textContent = error;
    }

    function hideFieldError(field) {
        const errorElement = field.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.remove();
        }
    }

    function getCustomErrorMessage(field) {
        if (field.validity.valueMissing) {
            return 'Ce champ est requis.';
        }
        if (field.validity.typeMismatch) {
            if (field.type === 'email') {
                return 'Veuillez saisir une adresse email valide.';
            }
            if (field.type === 'url') {
                return 'Veuillez saisir une URL valide.';
            }
        }
        if (field.validity.tooShort) {
            return `Minimum ${field.minLength} caractères requis.`;
        }
        if (field.validity.tooLong) {
            return `Maximum ${field.maxLength} caractères autorisés.`;
        }
        if (field.validity.rangeUnderflow) {
            return `La valeur doit être supérieure ou égale à ${field.min}.`;
        }
        if (field.validity.rangeOverflow) {
            return `La valeur doit être inférieure ou égale à ${field.max}.`;
        }
        if (field.validity.patternMismatch) {
            return 'Format invalide.';
        }
        return field.validationMessage;
    }
}

/**
 * Image lazy loading
 */
function initializeImageLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

/**
 * Bootstrap tooltips initialization
 */
function initializeTooltips() {
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

/**
 * Search and filter functionality
 */
function initializeSearchFilters() {
    // Price range slider (if exists)
    const priceMin = document.getElementById('prix_min');
    const priceMax = document.getElementById('prix_max');

    if (priceMin && priceMax) {
        priceMin.addEventListener('input', function () {
            if (priceMax.value && parseInt(this.value) > parseInt(priceMax.value)) {
                priceMax.value = this.value;
            }
        });

        priceMax.addEventListener('input', function () {
            if (priceMin.value && parseInt(this.value) < parseInt(priceMin.value)) {
                priceMin.value = this.value;
            }
        });
    }

    // Live search (if search input exists)
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Could implement live search here
                console.log('Searching for:', this.value);
            }, 300);
        });
    }

    // Filter form auto-submit on change
    const filterForm = document.querySelector('.search-form');
    if (filterForm) {
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function () {
                // Auto-submit form when filter changes (optional)
                // filterForm.submit();
            });
        });
    }
}

/**
 * Carousel enhancements
 */
function initializeCarousels() {
    // Add keyboard navigation to carousels
    document.querySelectorAll('.carousel').forEach(carousel => {
        carousel.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                const prevBtn = carousel.querySelector('.carousel-control-prev');
                if (prevBtn) prevBtn.click();
            }
            if (e.key === 'ArrowRight') {
                const nextBtn = carousel.querySelector('.carousel-control-next');
                if (nextBtn) nextBtn.click();
            }
        });

        // Make carousel focusable
        carousel.setAttribute('tabindex', '0');
    });

    // Pause carousel on hover
    document.querySelectorAll('.carousel[data-bs-ride="carousel"]').forEach(carousel => {
        carousel.addEventListener('mouseenter', function () {
            if (typeof bootstrap !== 'undefined') {
                const carouselInstance = bootstrap.Carousel.getInstance(carousel);
                if (carouselInstance) {
                    carouselInstance.pause();
                }
            }
        });

        carousel.addEventListener('mouseleave', function () {
            if (typeof bootstrap !== 'undefined') {
                const carouselInstance = bootstrap.Carousel.getInstance(carousel);
                if (carouselInstance) {
                    carouselInstance.cycle();
                }
            }
        });
    });
}

/**
 * Modal enhancements
 */
function initializeModals() {
    // Auto-focus first input in modals
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            const firstInput = modal.querySelector('input, textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        });
    });
}

/**
 * Admin specific features
 */
function initializeAdminFeatures() {
    // Confirmation dialogs for delete actions
    document.querySelectorAll('[onclick*="confirm"]').forEach(element => {
        element.addEventListener('click', function (e) {
            if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const files = Array.from(this.files);
            const preview = document.querySelector('.file-preview') || createPreviewContainer(input);

            preview.innerHTML = '';

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image';
                        img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; margin: 5px; border-radius: 5px;';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    });

    function createPreviewContainer(input) {
        const container = document.createElement('div');
        container.className = 'file-preview mt-2';
        input.parentNode.appendChild(container);
        return container;
    }

    // Table row selection
    const tables = document.querySelectorAll('.table');
    tables.forEach(table => {
        const checkboxes = table.querySelectorAll('input[type="checkbox"]');
        const selectAllCheckbox = table.querySelector('thead input[type="checkbox"]');

        if (selectAllCheckbox && checkboxes.length > 1) {
            selectAllCheckbox.addEventListener('change', function () {
                checkboxes.forEach(checkbox => {
                    if (checkbox !== selectAllCheckbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                        checkbox.closest('tr').classList.toggle('table-active', checkbox.checked);
                    }
                });
            });

            checkboxes.forEach(checkbox => {
                if (checkbox !== selectAllCheckbox) {
                    checkbox.addEventListener('change', function () {
                        this.closest('tr').classList.toggle('table-active', this.checked);

                        // Update select all checkbox
                        const checkedCount = table.querySelectorAll('tbody input[type="checkbox"]:checked').length;
                        const totalCount = table.querySelectorAll('tbody input[type="checkbox"]').length;
                        selectAllCheckbox.checked = checkedCount === totalCount;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
                    });
                }
            });
        }
    });
}

/**
 * Utility functions
 */

// Format price with French locale
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}

// Format date with French locale
function formatDate(date) {
    return new Intl.DateTimeFormat('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

// Show toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    toastContainer.appendChild(toast);

    if (typeof bootstrap !== 'undefined') {
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '1055';
    document.body.appendChild(container);
    return container;
}

// Debounce function
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Throttle function
function throttle(func, wait) {
    let inThrottle;
    return function () {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, wait);
        }
    };
}

// Local storage helpers
const Storage = {
    set: function (key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (e) {
            console.warn('localStorage not available');
        }
    },

    get: function (key, defaultValue = null) {
        try {
            const value = localStorage.getItem(key);
            return value ? JSON.parse(value) : defaultValue;
        } catch (e) {
            console.warn('localStorage not available');
            return defaultValue;
        }
    },

    remove: function (key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.warn('localStorage not available');
        }
    }
};

// Export for use in other scripts
window.TravelAgency = {
    formatPrice,
    formatDate,
    showToast,
    debounce,
    throttle,
    Storage
};

// Handle errors gracefully
window.addEventListener('error', function (e) {
    console.error('JavaScript error:', e.error);
    // Could send error to logging service here
});

// Service Worker registration (if available)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js')
            .then(function (registration) {
                console.log('SW registered: ', registration);
            })
            .catch(function (registrationError) {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
