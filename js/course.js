document.addEventListener('DOMContentLoaded', function() {
    const previewButton = document.getElementById('preview-button');
    const previewDiv = document.getElementById('preview');
    const closePreview = document.getElementById('close-preview');
    const navbar = document.getElementById('header');

    if (previewButton && previewDiv) {
        previewButton.addEventListener('click', function(e) {
            e.preventDefault();
            previewDiv.style.display = 'block';
            if (navbar) {
                navbar.style.zIndex = '1';
                navbar.style.position = 'fixed';
            }
        });
    }

    if (closePreview && previewDiv) {
        closePreview.addEventListener('click', function(e) {
            e.preventDefault();
            previewDiv.style.display = 'none';
            if (navbar) {
                navbar.style.zIndex = '10';
                navbar.style.position = 'fixed';
            }
        });
    }
});

