<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
?><!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-compact layout-menu-fixed "
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />


    <title>Coupe Interne | Wip</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />


    <!-- Icons -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />


    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/typeahead-js/typeahead.css" /> 
    <link rel="stylesheet" href="../assets/vendor/libs/jkanban/jkanban.css" />
<link rel="stylesheet" href="../assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="../assets/vendor/libs/flatpickr/flatpickr.css" />
<link rel="stylesheet" href="../assets/vendor/libs/quill/typography.css" />
<link rel="stylesheet" href="../assets/vendor/libs/quill/katex.css" />
<link rel="stylesheet" href="../assets/vendor/libs/quill/editor.css" />

    <!-- Page CSS -->


<link rel="stylesheet" href="../assets/vendor/css/pages/app-kanban.css" />
    <style>
    /* Style pour les alertes empil\u00e9es en haut à droite */
    .fixed-top-right {
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
    }

    </style>
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    
</head>

<body>

  

  
  <!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
  <div class="layout-container">

   <?php include 'menu.php'; ?>
 
    <!-- Layout container -->
    <div class="layout-page">
      
    <?php include 'navbar.php'; ?>  




      

      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->
        
          <div class="flex-grow-1 container-p-y container-fluid">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Production /</span> KanBan</h4>
            
<div class="app-kanban">



  <!-- Kanban Wrapper -->
  <div class="kanban-wrapper"></div>

  <!-- Bloquer une commande -->
<!-- Modal -->
<div class="modal fade" id="kanbanUpdateModal" tabindex="-1" aria-labelledby="kanbanUpdateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="kanbanUpdateModalLabel">Bloquer Commande</h5>
        <button
    type="button"
    class="btn-close"
    data-bs-dismiss="modal"
    aria-label="Close">
   
      </div>
      <div class="modal-body">
        <form>
          <!-- Article Field -->
		  <div class="my-1">
                    <strong>Article:</strong> <span id="article"></span>
                </div>
          

          <!-- Commande Field -->
		  <div class="my-1">
		  <strong>Commande:</strong> <span id="title"></span>
          </div>

          <!-- Quantité Field -->
		  
          <div class="my-1">
		  <strong>Quantité:</strong> <span id="quantite"></span>
          </div>

          <!-- Phase Field -->
		  
          <div class="my-1">
		  <strong>Date Chargement:</strong> <span id="date"></span>
          </div>

          <!-- Problème Field -->
          <div class="my-1">
		  <strong>Problème:</strong>
            
            <input type="text" id="problem" class="form-control" />
          </div>

          <!-- Département Field -->
          <div class="my-1">
		  <strong>Département:</strong>
            
            <input type="text" id="departement" class="form-control" />
          </div>

          <!-- Date Bloquage Field -->
          <div class="my-1">
		  <strong>Date Bloquage:</strong>
            
            <input type="datetime-local" id="dateBloquage" class="form-control" />
          </div>

          <!-- Attachments Field -->
          <div class="my-1">
		  <strong>Attachments:</strong>
            
            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple />
          </div>

          <!-- Note Field -->
          <div class="my-1">
		  <strong>Note:</strong>
            
            <textarea id="note" class="form-control"></textarea>
          </div>

          <!-- Buttons -->
          <div class="modal-footer my-1">
		  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            <button type="button" class="btn btn-danger" id="bloque" data-bs-dismiss="modal">
              Bloquer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- debloquer une commande -->
<div class="modal fade" id="debloquerCommande" tabindex="-1" aria-labelledby="debloquerCommandeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="debloquerCommandeLabel">Débloquer Commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="my-1">
                    <strong>Article:</strong> <span id="articleDebloquer"></span>
                </div>
                <div class="my-1">
                    <strong>Commande:</strong> <span id="commandeDebloquer"></span>
                </div>
                <div class="my-1">
                    <strong>Problème:</strong> <span id="problemeDebloquer"></span>
                </div>
                <div class="my-1">
                    <strong>Département:</strong> <span id="departementDebloquer"></span>
                </div>
                <div class="my-1">
                    <strong>Note:</strong> <span id="noteDebloquer"></span>
                </div>
                <div class="my-1">
                    <label class="form-label" for="attachments">Attachments</label>
                    <input type="file" class="form-control" id="attachmentsDebloquage" name="attachmentsDebloquage[]" multiple />
                </div>
                <div class="my-1">
                    <label for="date"><strong>Date Débloquage:</strong></label>
                    <input type="datetime-local" id="dateDebloquage" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="debloquer">Débloquer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Erreur</h5>
        <button
    type="button"
    class="btn-close"
    data-bs-dismiss="modal"
    aria-label="Close">
    <img src="../assets/img/icons/x.svg" alt="Close Icon" style="width: 24px; height: 24px; position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); " />
</button>
      </div>
      <div class="modal-body">
        Le déplacement vers "Terminé" n'est pas autorisé pour une tâche bloquée.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- debloquer une commande -->
<div class="modal fade" id="TermineModal" tabindex="-1" aria-labelledby="debloquerCommandeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="debloquerCommandeLabel">Débloquer Commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="my-1">
                    <strong>Article:</strong> <span id="articleTermine"></span>
                </div>
                <div class="my-1">
                    <strong>Commande:</strong> <span id="commandeTermine"></span>
                </div>
                <div class="my-1">
                    <strong>Quantité Demandée:</strong> <span id="quantiteDemandee"></span>
                </div>
                <div class="my-1">
                    <label for="quantiteReelle"><strong>Quantité Réelle:</strong> </label>
					<input type="number"  class="form-control" id="quantiteReelle"/>
                </div>
                
                
                <div class="my-1">
                    <label for="date"><strong>Date Fin:</strong></label>
                    <input type="date" id="dateFin" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-label-success" id="terminer">Enregistrer</button>
            </div>
        </div>
    </div>
</div>



          
          <!-- / Content -->

          </div>
          



          
          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    
    
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    
    
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    
  </div>
  <!-- / Layout wrapper -->
<div class="alert alert-success fixed-top-right" role="alert" style="display: none;">
    <span id="alertMessage"></span>
</div>
<script>
    function showAlert(message, alertClass = 'alert-success') {
        const alertBox = document.querySelector('.alert.fixed-top-right');
        alertBox.className = `alert ${alertClass} fixed-top-right`; // Modify alert class
        document.getElementById('alertMessage').innerText = message; // Add message

        // Show alert with a 200ms delay
        setTimeout(() => {
            alertBox.style.display = 'block';
        }, 200);

        // Hide alert after 20 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 20000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        let taskToBlock = null; // Variable to temporarily store the task to block
        let taskToUnBlock = null; // Variable to temporarily store the task to unblock

        // Fetch task data
        fetch('donneeKanban.php')
            .then(response => response.json())
            .then(data => {
                // Define Kanban boards
                const boards = {
                    '_enattente': { id: '_enattente', title: 'En Attente', item: [] },
                    '_encours': { id: '_encours', title: 'En Cours', item: [] },
                    '_termine': { id: '_termine', title: 'Terminé', item: [] },
                    '_bloque': { id: '_bloque', title: 'Bloqué', item: [] }
                };

                // Iterate through tasks and add to corresponding columns
                data.forEach(task => {
                    const statusKey = getStatusKey(task.etat);
                    const badgeClass = getBadgeClass(task.phase);
                    const taskDiv = createTaskElement(task, badgeClass);

                    // Add task element to the appropriate board
                    boards[statusKey].item.push({ title: taskDiv.outerHTML });
                });

                // Initialize Kanban board
                const kanban = new jKanban({
                    element: '.kanban-wrapper',
                    boards: Object.values(boards),
                    click: handleTaskClick,
                    dropEl: handleTaskDrop
                });
            });

        function getStatusKey(state) {
            switch (state) {
                case 'En attente': return '_enattente';
                case 'En cours': return '_encours';
                case 'Termine': return '_termine';
                case 'Bloque': return '_bloque';
                default: return '_enattente';
            }
        }

        function getBadgeClass(phase) {
            switch (phase) {
                case 'Production': return 'badge bg-label-primary';
                case 'Campionario': return 'badge bg-label-success';
                default: return 'badge bg-label-warning';
            }
        }

        function createTaskElement(task, badgeClass) {
            const taskDiv = document.createElement('div');
            taskDiv.setAttribute('data-id', task.id);
            taskDiv.setAttribute('data-commande', task.commande);
            taskDiv.setAttribute('data-phase', task.phase);
            taskDiv.setAttribute('data-article', task.article);
            taskDiv.setAttribute('data-date', task.datechargement);
            taskDiv.setAttribute('data-quantite', task.quantitedemandee);
            taskDiv.setAttribute('data-etat', task.etat);

            const badge = document.createElement('span');
            badge.className = badgeClass;
            badge.textContent = task.phase;

            const articleStrong = document.createElement('strong');
            articleStrong.textContent = task.article;

            const commandeDiv = document.createElement('div');
            commandeDiv.className = 'my-2';
            commandeDiv.appendChild(articleStrong);
            commandeDiv.appendChild(document.createElement('br'));
            commandeDiv.appendChild(document.createTextNode(task.commande));
            commandeDiv.appendChild(document.createElement('br'));

            taskDiv.appendChild(badge);
            taskDiv.appendChild(document.createElement('br'));
            taskDiv.appendChild(commandeDiv);

            return taskDiv;
        }

        function handleTaskClick(el) {
            const taskElement = el.querySelector('div');
            const taskDetails = getTaskDetails(taskElement);

            // Show the update sidebar with task details
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('kanbanUpdateSidebar'));
            offcanvas.show();

            // Populate fields with task details
            populateTaskFields(taskDetails);
        }

        function getTaskDetails(taskElement) {
            return {
                article: taskElement.getAttribute('data-article'),
                commande: taskElement.getAttribute('data-commande'),
                quantite: taskElement.getAttribute('data-quantite'),
                date: taskElement.getAttribute('data-date')
            };
        }

        function populateTaskFields({ article, commande, quantite, date }) {
            document.getElementById('article').value = article;
            document.getElementById('title').value = commande;
            document.getElementById('date').value = date;
            document.getElementById('quantite').value = quantite;
        }

        function handleTaskDrop(el, target, source, sibling) {
            const taskElement = el.querySelector('div');
            const taskId = taskElement.getAttribute('data-id');
            const taskArticle = taskElement.getAttribute('data-article');
            const taskCommande = taskElement.getAttribute('data-commande');
            const taskQuantite = taskElement.getAttribute('data-quantite');
            const taskDate = taskElement.getAttribute('data-date');

            let newStatus = getStatusFromTarget(target);

            if (taskElement.getAttribute('data-etat') === 'Bloque' && newStatus === 'Termine') {
                handleBlockedToCompleted(taskElement, el, source);
                return;
            }

            // Other status transitions
            if (newStatus === 'Termine') {
                showCompletionModal(taskId, taskArticle, taskCommande, taskQuantite);
            } else if (newStatus === 'Bloque') {
                showBlockingModal(taskId, taskArticle, taskCommande, taskQuantite,taskDate);
            } else {
                updateTaskImmediately(taskElement, taskId, taskArticle, taskCommande, newStatus);
            }
        }

        function getStatusFromTarget(target) {
            const statusMapping = {
                '_enattente': 'En attente',
                '_encours': 'En cours',
                '_termine': 'Termine',
                '_bloque': 'Bloque'
            };
            return statusMapping[target.closest('.kanban-board').getAttribute('data-id')];
        }

        function handleBlockedToCompleted(taskElement, el, source) {
            // Open error modal
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();

            // Restore task to original column
            kanban.removeElement(el);
            source.appendChild(el);
        }

        function showCompletionModal(taskId, taskArticle, taskCommande, taskQuantite) {
            taskToTermine = { id: taskId, article: taskArticle, commande: taskCommande, etat: 'Termine' };

            // Update modal fields
            document.getElementById('articleTermine').textContent = taskArticle;
            document.getElementById('commandeTermine').textContent = taskCommande;
            document.getElementById('quantiteDemandee').textContent = taskQuantite;

            // Show the modal
            const myModal = new bootstrap.Modal(document.getElementById('TermineModal'));
            myModal.show();
        }

        function showBlockingModal(taskId, taskArticle, taskCommande, taskQuantite,taskDate) {
            taskToBlock = { id: taskId, article: taskArticle, commande: taskCommande, etat: 'Bloque' };

            // Update modal fields
            document.getElementById('article').textContent = taskArticle;
            document.getElementById('title').textContent = taskCommande;
            document.getElementById('date').textContent = taskDate;
            
            document.getElementById('quantite').textContent = taskQuantite;

            // Show the modal
            const myModal = new bootstrap.Modal(document.getElementById('kanbanUpdateModal'));
            myModal.show();
        }

        function updateTaskImmediately(taskElement, taskId, taskArticle, taskCommande, newStatus) {
            taskElement.setAttribute('data-etat', newStatus);

            // Send AJAX request to update status in database
            updateTaskStatus(taskId, taskArticle, taskCommande, newStatus);
        }

        // Add event listener for the "Bloquer" button
        document.getElementById('bloque').addEventListener('click', function() {
            if (taskToBlock) {
                handleBlocking();
            }
        });

        function handleBlocking() {
            // Récupérer les valeurs des champs supplémentaires
                const problem = document.getElementById('problem').value;
                const departement = document.getElementById('departement').value;
                const note = document.getElementById('note').value;
                const dateBloquage = document.getElementById('dateBloquage').value;
                const attachments = document.getElementById('attachments').files;

                // Créer un objet FormData pour l'envoi des fichiers et des autres données
                let formData = new FormData();
                formData.append('id', taskToBlock.id);
                formData.append('article', taskToBlock.article);
                formData.append('commande', taskToBlock.commande);
                formData.append('etat', taskToBlock.etat);
                formData.append('problem', problem); // Nouvelle information
                formData.append('departement', departement);
                formData.append('dateBloquage', dateBloquage); // Nouvelle information
                formData.append('note', note); // Nouvelle information

                // Ajouter les fichiers joints à l'objet FormData
                Array.from(attachments).forEach((attachment) => {
                    formData.append('attachments[]', attachment);
                });

                // Envoyer les données via AJAX au serveur
                fetch('../php/bloquerEtat.php', {
                        method: 'POST',
                        body: formData // Envoi avec FormData
                    }).then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            console.log('Tâche bloquée avec succès');

                            taskToBlock = null; // Réinitialiser la tâche à bloquer
                        } else {
                            console.error('Erreur lors de la mise à jour de l\'état de la tâche:', result.message);
                        }
                    }).catch(error => console.error('Erreur réseau:', error));

            // Reset taskToBlock
            taskToBlock = null;
        }

        // Add event listener for the "Terminer" button
        document.getElementById('terminer').addEventListener('click', function() {
            if (taskToTermine) {
                updateTaskToComplete();
            }
        });

function updateTaskToComplete() {
    if (taskToTermine) {
        // Récupérer les valeurs des champs supplémentaires
        const quantiteReelle = document.getElementById('quantiteReelle').value;
        const dateFin = document.getElementById('dateFin').value;

        // Validation : vérifier que les champs ne sont pas vides
        if (!quantiteReelle || !dateFin) {
            showAlert('Veuillez remplir tous les champs obligatoires.', 'alert-danger');
            return;
        }

        // Créer un objet FormData pour l'envoi des données
        let formData = new FormData();
        formData.append('id', taskToTermine.id); 
        formData.append('article', taskToTermine.article);
        formData.append('commande', taskToTermine.commande);
        formData.append('etat', taskToTermine.etat);
        formData.append('quantiteReelle', quantiteReelle);
        formData.append('dateFin', dateFin);

        console.log('FormData:', Object.fromEntries(formData.entries())); // Afficher les données au format lisible

        // Envoyer les données via AJAX au serveur
        fetch('../php/terminerEtat.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            showAlert(result.message);
            if (result.success) {
                console.log('Tâche terminée avec succès');
                taskToTermine = null; // Réinitialiser la tâche

                // Fermer le modal (Bootstrap example)
                const modal = bootstrap.Modal.getInstance(document.getElementById('TermineModal'));
                modal.hide();

                // Optional: Remove the task element from Kanban
                if (kanban && taskElement) {
                    kanban.removeElement(taskElement);
                }
            } else {
                console.error('Erreur lors de la mise à jour:', result.message);
            }
        })
        .catch(error => console.error('Erreur réseau:', error));
    }
}

		
		
		function updateTaskStatus(taskId, taskArticle, taskCommande, newStatus) {
    fetch('../php/updateEtat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: taskId,
            article: taskArticle,
            commande: taskCommande,
            etat: newStatus
        })
    }).then(response => response.json())
      .then(result => {
          if (result.success) {
              console.log('Statut de la tâche mis à jour avec succès');
          } else {
              console.error('Erreur lors de la mise à jour du statut de la tâche:', result.message);
          }
      }).catch(error => console.error('Erreur réseau:', error));
}
    });
	
</script>











<!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>



  

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/libs/hammer/hammer.js"></script>
  <script src="../assets/vendor/libs/i18n/i18n.js"></script>
  <script src="../assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="../assets/vendor/libs/moment/moment.js"></script>
<script src="../assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="../assets/vendor/libs/select2/select2.js"></script>
<script src="../assets/vendor/libs/jkanban/jkanban.js"></script>
<script src="../assets/vendor/libs/quill/katex.js"></script>
<script src="../assets/vendor/libs/quill/quill.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>
  

  <!-- Page JS -->
  <script src="../assets/js/app-kanban.js"></script>
  
</body>

</html>




<!-- beautify ignore:end -->

