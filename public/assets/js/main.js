document.addEventListener('DOMContentLoaded', function() {
    // Activer les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Activer les popovers Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Fonction pour animer les compteurs
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 secondes
            const step = target / (duration / 16); // 60 FPS
            
            let count = 0;
            const updateCount = () => {
                count += step;
                
                if (count < target) {
                    counter.innerText = Math.floor(count);
                    requestAnimationFrame(updateCount);
                } else {
                    counter.innerText = target;
                }
            };
            
            updateCount();
        });
    }

    // Vérifier si des éléments avec la classe .counter existent
    if (document.querySelector('.counter')) {
        // Observer les éléments pour les animer lorsqu'ils sont visibles
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        });

        const counterSection = document.querySelector('.counter-section');
        if (counterSection) {
            observer.observe(counterSection);
        }
    }

    // Gestion des filtres de recherche
    const searchInputs = document.querySelectorAll('.search-filter');
    
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const targetClass = this.getAttribute('data-target');
            const items = document.querySelectorAll('.' + targetClass);
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Gestion des formulaires avec validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
});