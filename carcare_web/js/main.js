// Main JavaScript file for CarCare website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    // Back to top button
    var backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTop.style.display = "block";
            } else {
                backToTop.style.display = "none";
            }
        };
    }

    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.navbar-toggler');
    const navMenu = document.querySelector('#ftco-nav');
    
    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('show');
        });
    }

    // Form validation
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

    // Alert auto-dismiss
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => {
                alert.remove();
            }, 150);
        }, 3000);
    });

    // Service search functionality
    const searchInput = document.querySelector('#serviceSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const serviceCards = document.querySelectorAll('.service-card');
            
            serviceCards.forEach(card => {
                const title = card.querySelector('.service-title').textContent.toLowerCase();
                const description = card.querySelector('.service-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Appointment form date/time picker initialization
    const appointmentDate = document.querySelector('#appointmentDate');
    if (appointmentDate) {
        flatpickr(appointmentDate, {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: [
                function(date) {
                    // Disable weekends
                    return date.getDay() === 0 || date.getDay() === 6;
                }
            ]
        });
    }

    // Service booking confirmation
    const bookButtons = document.querySelectorAll('.book-service-btn');
    bookButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to book this service?')) {
                e.preventDefault();
            }
        });
    });
});
