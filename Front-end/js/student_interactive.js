// Student Interactive JavaScript (Hotspots, Lightbox Modal, Filtering)
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap Popovers for Interactive Map Hotspots
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Gallery Filter Logic
    const filterButtons = document.querySelectorAll('#galleryFilterBtnGroup button');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// Lightbox Modal Function
function openImageModal(title, description) {
    document.getElementById('modalImageTitle').innerText = title;
    document.getElementById('modalImageDesc').innerText = description;
    const modal = new bootstrap.Modal(document.getElementById('imageDetailModal'));
    modal.show();
}
