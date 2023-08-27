document.addEventListener("DOMContentLoaded", function() {
    const modals = document.querySelectorAll('.modal');

    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const carousel = this.querySelector('.carousel');
            if (!carousel) return; // If no carousel, exit the function

            function checkArrows() {
                const totalItems = carousel.querySelectorAll('.carousel-item').length;
                const activeItem = carousel.querySelector('.carousel-inner .active');
                const currentIndex = Array.from(activeItem.parentNode.children).indexOf(activeItem);

                // Show all arrows initially
                carousel.querySelectorAll('.carousel-control-prev, .carousel-control-next').forEach(arrow => arrow.style.display = 'block');

                if (currentIndex === 0) {
                    carousel.querySelector('.carousel-control-prev').style.display = 'none';
                } 

                if (currentIndex === totalItems - 1) {
                    carousel.querySelector('.carousel-control-next').style.display = 'none';
                }
            }

            // Check arrows on initial modal shown
            checkArrows();

            carousel.addEventListener('slid.bs.carousel', checkArrows);
        });
    });
});