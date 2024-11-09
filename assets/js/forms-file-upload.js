<script>
    !function() {
        var previewTemplate = `
            <div class="dz-preview dz-file-preview">
                <div class="dz-details">
                    <div class="dz-thumbnail">
                        <img data-dz-thumbnail>
                        <span class="dz-nopreview">No preview</span>
                        <div class="dz-success-mark"></div>
                        <div class="dz-error-mark"></div>
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="dz-filename" data-dz-name></div>
                    <div class="dz-size" data-dz-size></div>
                </div>
            </div>`;

        // Initialisation de Dropzone
        var dropzoneMultiElement = document.querySelector("#dropzone-multi");
        if (dropzoneMultiElement && !dropzoneMultiElement.classList.contains("dz-clickable")) {
            var myDropzone = new Dropzone(dropzoneMultiElement, {
                previewTemplate: previewTemplate,
                parallelUploads: 100,
                maxFilesize: 100, // Taille max de fichier en Mo
                addRemoveLinks: true
            });

            // Bouton Enregistrer
            document.getElementById("saveButton").addEventListener("click", function() {
                // Soumettre les fichiers via AJAX
                myDropzone.processQueue(); // Traite les fichiers

                // Attendez que les fichiers soient téléchargés avant de soumettre le formulaire
                myDropzone.on("sending", function() {
                    document.getElementById("saveButton").disabled = true; // Désactive le bouton pendant l'upload
                });

                myDropzone.on("success", function(file, response) {
                    // Si l'upload est réussi, réactiver le bouton
                    document.getElementById("saveButton").disabled = false;
                    alert("Le fichier a été téléchargé avec succès.");
                });

                myDropzone.on("error", function(file, errorMessage) {
                    // Si l'upload échoue
                    document.getElementById("saveButton").disabled = false;
                    alert("Erreur lors de l'upload: " + errorMessage);
                });
            });
        }
    }();
</script>

