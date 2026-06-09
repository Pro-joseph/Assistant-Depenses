@extends('layouts.sidebar')

@section('title', 'Assistant Dépenses - Ajouter un Reçu')
@section('page-title', 'Ajouter un Reçu')
@section('search', false)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg h-full">
        <section class="lg:col-span-7 flex flex-col gap-lg">
            <form method="POST" action="{{ route('recus.store') }}" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col h-full">
                @csrf
                <div class="mb-md">
                    <h3 class="text-lg font-medium text-primary mb-xs">Détails du reçu</h3>
                    <p class="text-sm text-on-surface-variant">Collez le texte brut de votre reçu ci-dessous pour une extraction automatique.</p>
                </div>
                <div class="flex-1 relative group">
                    <textarea class="w-full h-full min-h-[300px] p-md bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-sm resize-none" id="receiptText" name="texte_brut" placeholder="Ex: CARREFOUR CITY
24 RUE DE LA PAIX
TOTAL: 42.50€..."></textarea>
                    <div class="absolute bottom-4 right-4 text-outline text-xs">
                        <span id="charCount">0</span> caractères
                    </div>
                </div>
                <div class="mt-lg flex items-center justify-between">
                    <div class="flex gap-sm">
                        <button class="px-md py-sm border border-primary text-primary rounded-lg text-xs font-medium hover:bg-primary/5 transition-colors" onclick="clearText()" type="button">
                            Effacer
                        </button>
                    </div>
                    <button class="px-xl py-sm bg-primary text-on-primary rounded-lg text-base font-semibold hover:bg-primary-container shadow-md active:scale-95 transition-all flex items-center gap-sm" id="submitBtn" type="submit">
                        Soumettre
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="lg:col-span-5 flex flex-col gap-lg">
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <h3 class="text-lg font-medium text-primary mb-md">Document Numérique</h3>
                <div class="border-2 border-dashed border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center cursor-pointer hover:border-primary hover:bg-primary-fixed/20 transition-all group relative overflow-hidden h-64" id="dropzone">
                    <input accept="image/*,.pdf" class="hidden" id="fileInput" type="file">
                    <div id="dropContent">
                        <span class="material-symbols-outlined text-5xl text-outline group-hover:text-primary transition-colors mb-sm">upload_file</span>
                        <p class="text-lg text-on-surface mb-xs">Glissez votre reçu ici</p>
                        <p class="text-sm text-on-surface-variant">PNG, JPG ou PDF (max 10MB)</p>
                    </div>
                    <div class="hidden absolute inset-0 bg-surface-container-lowest" id="previewContainer">
                        <img class="w-full h-full object-cover rounded-xl" id="filePreview" src="">
                        <button class="absolute top-2 right-2 bg-error text-on-error p-xs rounded-full shadow-lg hover:bg-error/90" onclick="removeFile(event)">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                </div>
                <div class="mt-lg space-y-md">
                    <div class="flex items-center gap-md p-md bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <p class="text-sm text-on-surface-variant">L'ajout d'une image permet une validation plus précise par notre IA.</p>
                    </div>
                </div>
            </div>

            <div class="bg-primary-container text-on-primary-container rounded-xl p-lg border border-primary/20 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10">
                    <span class="material-symbols-outlined text-[120px]">account_balance_wallet</span>
                </div>
                <h4 class="text-lg font-medium mb-sm">Conseils</h4>
                <ul class="space-y-sm text-sm">
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                        Assurez-vous que le montant total est visible.
                    </li>
                    <li class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                        La date et le nom du marchand sont essentiels.
                    </li>
                </ul>
            </div>
        </section>
    </div>

    <div class="fixed inset-0 bg-surface/90 backdrop-blur-sm z-[100] flex flex-col items-center justify-center hidden" id="loadingOverlay">
        <div class="relative w-24 h-24 mb-lg">
            <div class="absolute inset-0 border-4 border-primary-fixed rounded-full opacity-30"></div>
            <div class="absolute inset-0 border-4 border-t-primary rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-3xl">sync</span>
            </div>
        </div>
        <h2 class="text-2xl font-semibold text-primary mb-sm">Reçu en cours de traitement</h2>
        <p class="text-base text-on-surface-variant max-w-md text-center">Notre intelligence artificielle analyse vos données. Cela ne prendra que quelques secondes.</p>
        <div class="w-64 h-1.5 bg-surface-container-high rounded-full mt-xl overflow-hidden">
            <div class="h-full bg-primary transition-all duration-500 w-0" id="progressBar"></div>
        </div>
    </div>

    <div class="fixed inset-0 z-[110] flex items-center justify-center p-lg hidden" id="successModal">
        <div class="absolute inset-0 bg-inverse-surface/40 backdrop-blur-sm" onclick="closeSuccess()"></div>
        <div class="bg-surface-container-lowest rounded-xl p-xxl max-w-md w-full shadow-xl relative">
            <div class="w-20 h-20 bg-primary-fixed/20 text-primary rounded-full flex items-center justify-center mx-auto mb-lg">
                <span class="material-symbols-outlined text-[48px]">check_circle</span>
            </div>
            <h3 class="text-2xl font-semibold text-center mb-md">Traitement terminé !</h3>
            <p class="text-base text-center text-on-surface-variant mb-xl">Le reçu a été analysé avec succès. Vous pouvez maintenant le retrouver dans votre historique.</p>
            <div class="flex flex-col gap-md">
                <button class="w-full py-md bg-primary text-on-primary rounded-lg font-bold" onclick="closeSuccess()">Consulter la dépense</button>
                <button class="w-full py-md border border-outline text-on-surface-variant rounded-lg font-bold" onclick="location.reload()">Ajouter un autre reçu</button>
            </div>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const receiptText = document.getElementById('receiptText');
        const charCount = document.getElementById('charCount');
        const previewContainer = document.getElementById('previewContainer');
        const filePreview = document.getElementById('filePreview');
        const dropContent = document.getElementById('dropContent');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const successModal = document.getElementById('successModal');
        const progressBar = document.getElementById('progressBar');

        receiptText.addEventListener('input', () => {
            charCount.textContent = receiptText.value.length;
        });

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('bg-primary-fixed/30', 'border-primary');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('bg-primary-fixed/30', 'border-primary');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('bg-primary-fixed/30', 'border-primary');
            const file = e.dataTransfer.files[0];
            handleFile(file);
        });

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            handleFile(file);
        });

        function handleFile(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    filePreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    dropContent.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeFile(e) {
            e.stopPropagation();
            fileInput.value = '';
            filePreview.src = '';
            previewContainer.classList.add('hidden');
            dropContent.classList.remove('hidden');
        }

        function clearText() {
            receiptText.value = '';
            charCount.textContent = '0';
        }

        function handleSubmission() {
            if (!receiptText.value.trim() && !fileInput.files[0]) {
                alert("Veuillez saisir du texte ou ajouter une image de reçu.");
                return;
            }

            loadingOverlay.classList.remove('hidden');

            let progress = 0;
            const interval = setInterval(() => {
                progress += 5;
                progressBar.style.width = `${progress}%`;
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        loadingOverlay.classList.add('hidden');
                        successModal.classList.remove('hidden');
                    }, 500);
                }
            }, 100);
        }

        function closeSuccess() {
            successModal.classList.add('hidden');
        }
    </script>
@endsection