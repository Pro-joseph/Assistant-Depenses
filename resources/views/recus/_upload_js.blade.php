<script>
    const zone = document.getElementById('uploadZone');
    const input = document.getElementById('fileInput');
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('uploadPreview');
    const previewImg = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    const removeBtn = document.getElementById('removeSelection');

    function showPreview(file) {
        if (!file) return;
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
            fileInfo.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        };
        reader.readAsDataURL(file);
    }

    function resetUpload() {
        input.value = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
        previewImg.src = '#';
        fileInfo.textContent = '';
    }

    input.addEventListener('change', function () {
        if (this.files.length) {
            const removeCb = document.getElementById('removeImage');
            if (removeCb) removeCb.checked = false;
            showPreview(this.files[0]);
        }
    });

    zone.addEventListener('click', function () {
        input.click();
    });

    removeBtn.addEventListener('click', resetUpload);

    const removeCb = document.getElementById('removeImage');
    if (removeCb) {
        removeCb.addEventListener('change', function () {
            if (this.checked) resetUpload();
        });
    }

    ['dragover', 'dragleave', 'drop'].forEach(name => {
        zone.addEventListener(name, function (e) { e.preventDefault(); e.stopPropagation(); });
    });

    zone.addEventListener('dragover', function () {
        this.classList.add('border-primary', 'bg-primary-fixed/20');
    });

    zone.addEventListener('dragleave', function () {
        this.classList.remove('border-primary', 'bg-primary-fixed/20');
    });

    zone.addEventListener('drop', function (e) {
        this.classList.remove('border-primary', 'bg-primary-fixed/20');
        const files = e.dataTransfer.files;
        if (files.length) {
            if (files[0].type.startsWith('image/')) {
                if (removeCb) removeCb.checked = false;
                input.files = files;
                showPreview(files[0]);
            } else {
                alert('Format non supporté. Veuillez sélectionner une image (PNG, JPG, WebP).');
            }
        }
    });
</script>
