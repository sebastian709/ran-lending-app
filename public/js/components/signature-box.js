if (!window._signatureBoxInitialized) {
    window._signatureBoxInitialized = true;

    let signaturePad;

    $(document).on('click', '.signature-target', function () {
        const $target = $(this);

        $.confirm({
            title: 'Sign Here',
            content: `
                <ul class="nav nav-tabs mb-3" id="signatureTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="draw-tab" data-bs-toggle="tab" data-bs-target="#draw" type="button">Draw</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button">Upload</button>
                    </li>
                </ul>
                <div class="tab-content" id="signatureTabContent">
                    <div class="tab-pane fade show active" id="draw">
                        <canvas id="signatureCanvas" class="border rounded w-100 bg-transparent" height="200"></canvas>
                    </div>
                    <div class="tab-pane fade" id="upload">
                        <div id="uploadDropzone" class="border rounded p-3 text-center bg-light" style="cursor: pointer;">
                            <p class="text-muted">Click or drag your signature image here</p>
                            <input type="file" id="uploadSignatureInput" accept="image/*" class="form-control d-none">
                        </div>
                        <div id="uploadedPreview" class="mt-3 text-center d-none">
                            <canvas id="previewCanvas" class="shadow rounded bg-transparent" style="width: 300px; height: 100px;"></canvas>
                        </div>
                    </div>
                </div>
            `,
            buttons: {
                clear: {
                    text: 'Clear',
                    btnClass: 'btn-outline-warning',
                    action: function () {
                        if ($('#draw-tab').hasClass('active')) {
                            signaturePad.clear();
                        } else {
                            $('#uploadSignatureInput').val('');
                            $('#uploadedPreview').addClass('d-none');
                        }
                        return false;
                    }
                },
                confirm: {
                    text: 'Confirm',
                    btnClass: 'btn-primary',
                    action: function () {
                        let img;
                        if ($('#draw-tab').hasClass('active')) {
                            if (!signaturePad.isEmpty()) {
                                img = signaturePad.toDataURL('image/png');
                            }
                        } else {
                            const canvasPreview = document.getElementById('previewCanvas');
                            img = canvasPreview.toDataURL('image/png');
                        }

                        if (img) {
                            $target.html(`<img src="${img}" alt="Signature" style="width: 300px; height: 100px; object-fit: contain;">`);
                            $target.removeClass('signature-empty').addClass('signature-filled filled');
                        }
                    }
                },
                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-outline-secondary'
                }
            },
            onOpenBefore: function () {
                // DRAW TAB
                const canvas = document.getElementById("signatureCanvas");
                canvas.width = canvas.offsetWidth;
                signaturePad = new SignaturePad(canvas);

                // UPLOAD TAB
                const uploadInput = document.getElementById('uploadSignatureInput');
                const dropzone = document.getElementById('uploadDropzone');
                const canvasPreview = document.getElementById('previewCanvas');
                const uploadedPreview = document.getElementById('uploadedPreview');
                const ctxPreview = canvasPreview.getContext('2d');

                dropzone.addEventListener('click', () => uploadInput.click());

                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('bg-secondary', 'text-white');
                });

                dropzone.addEventListener('dragleave', () => {
                    dropzone.classList.remove('bg-secondary', 'text-white');
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('bg-secondary', 'text-white');
                    const file = e.dataTransfer.files[0];
                    if (file) handleFileUpload(file);
                });

                uploadInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) handleFileUpload(file);
                });

                function handleFileUpload(file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = new Image();
                        img.onload = function () {
                            const fixedWidth = 300;
                            const fixedHeight = 100;

                            canvasPreview.width = fixedWidth;
                            canvasPreview.height = fixedHeight;

                            ctxPreview.clearRect(0, 0, fixedWidth, fixedHeight);

                            // Stretch image to fill canvas exactly
                            ctxPreview.drawImage(img, 0, 0, fixedWidth, fixedHeight);

                            // Remove white background
                            const imageData = ctxPreview.getImageData(0, 0, fixedWidth, fixedHeight);
                            const data = imageData.data;

                            for (let i = 0; i < data.length; i += 4) {
                                if (data[i] > 150 && data[i + 1] > 150 && data[i + 2] > 150) {
                                    data[i + 3] = 0; // transparent
                                }
                            }

                            ctxPreview.putImageData(imageData, 0, 0);
                            uploadedPreview.classList.remove('d-none');
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }

            }
        });
    });
}
