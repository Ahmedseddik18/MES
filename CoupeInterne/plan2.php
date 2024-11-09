<?php
include("../php/db.php");
include("../php/fonction.php");

$delete = "DELETE FROM planification WHERE etat <> 'Termine'";
$operateurs = $conn->query($delete)->fetchAll(PDO::FETCH_ASSOC);



// Récupération des commandes
$sql = "SELECT c.id, c.commande, c.article, c.partie, c.sub, c.longueurMatelas, c.quantiteMatelas, c.nombrePlies, c.HSR, c.HSM, c.HSC, c.HSE, d.relaxation,c.codeMatelas
        FROM commande c
        JOIN db d ON c.commande = d.commande AND c.article = d.article
		
		where c.etatEtiquetage != 'Termine'
		 ";
$commandes = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Récupération des opérateurs et leurs matricules
$sql = 'SELECT id, matricule, fonction FROM effectif ';
$operateurs = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sqlTable = 'SELECT id, longueur, largeur FROM materiel order by longueur ASC';
$Tables = $conn->query($sqlTable)->fetchAll(PDO::FETCH_ASSOC);

// Création d'un tableau pour stocker les matricules par fonction
$operateurs_par_fonction = [
    'manutention' => [],
    'matelasseur' => [],
    'coupeur' => [],
    'etiquetage' => []
];

foreach ($operateurs as $operateur) {
    if (array_key_exists($operateur['fonction'], $operateurs_par_fonction)) {
        $operateurs_par_fonction[$operateur['fonction']][$operateur['id']] = $operateur['matricule'];
    }
}
// Fonction pour vérifier si une table est disponible pour une commande
function trouverTableDisponible($commande, $Tables, &$tablesOccupees, $dateDebutCommande) {
    foreach ($Tables as $table) {
        // Vérifier si la table est occupée jusqu'à une certaine date
        $dateFinTable = $tablesOccupees[$table['id']]['dateFin'] ?? null;
        if ($dateFinTable && strtotime($dateDebutCommande) < strtotime($dateFinTable)) { 
            continue; // Passer à la table suivante
        }

        // 1ère contrainte : La longueur du matelas doit être inférieure à la longueur de la table
        if ($commande['longueurMatelas'] + 0.3 >= $table['longueur']) { 
           
            continue; // Passer à la table suivante
        }
      // 2ème contrainte : Vérifier la somme des longueurs déjà utilisées sur la table
        $sommeLongueurs = isset($tablesOccupees[$table['id']])
            ? $tablesOccupees[$table['id']]['sommeLongueurs'] + $commande['longueurMatelas'] + 0.3
            : $commande['longueurMatelas'] + 0.3;

        if ($sommeLongueurs > $table['longueur']) {
            
            continue; // Passer à la table suivante
        }

        // 3ème contrainte : Vérifier la largeur du tissu (désactivée dans ton cas)
        // if ($commande['largeur'] >= $table['largeur']) {
        //     echo "Largeur du tissu trop grande pour la table ID: " . $table['id'] . "<br>";
        //     continue; // Passer à la table suivante
        // }        
        return $table['id'];
    }

    return null;
}



function calculerDateFin($dateDebut, $dureeHeuresDecimales) {
    $dureeMinutes = $dureeHeuresDecimales * 60;
    $heureDebut = strtotime($dateDebut);
    $jourSemaine = date('N', $heureDebut); // 1 (lundi) à 7 (dimanche)

    // Heures de travail par jour
    $tempsTravailSemaine = 8.25 * 60; // 8h00 - 17h15 avec pause déjeuner
    $tempsTravailSamedi = 4.8 * 60; // 8h00 - 13h00 sans pause déjeuner

    // Définir les heures de fin de journée pour la semaine et le samedi
    if ($jourSemaine >= 1 && $jourSemaine <= 5) {
        $finJournee = strtotime(date('Y-m-d', $heureDebut) . ' 17:15:00');
    } elseif ($jourSemaine == 6) { // Samedi
        $finJournee = strtotime(date('Y-m-d', $heureDebut) . ' 13:00:00');
    } else {
        $heureDebut = strtotime('next Monday 08:00:00', $heureDebut); // Passer au lundi si c'est dimanche
        return calculerDateFin(date('Y-m-d H:i:s', $heureDebut), $dureeHeuresDecimales);
    }

    $heureFin = $heureDebut + ($dureeMinutes * 60);

    // Pause déjeuner pour les jours de la semaine (lundi à vendredi)
    $debutPause = strtotime(date('Y-m-d', $heureDebut) . ' 11:30:00');
    $finPause = strtotime(date('Y-m-d', $heureDebut) . ' 12:15:00');

    while ($dureeMinutes > 0) {
        if ($heureFin > $finJournee) {
            if ($jourSemaine >= 1 && $jourSemaine <= 5) {
                $dureeMinutes -= ($finJournee - $heureDebut) / 60;
            } elseif ($jourSemaine == 6) {
                $dureeMinutes -= ($finJournee - $heureDebut) / 60;
            }
            // Passer au jour suivant (lundi si c'est samedi ou dimanche)
            if ($jourSemaine == 6) { // Si c'est samedi, passer à lundi
                $heureDebut = strtotime('next Monday 08:00:00', $heureDebut);
            } else { // Passer au jour suivant
                $heureDebut = strtotime(date('Y-m-d', strtotime('+1 day', $heureDebut)) . ' 08:00:00');
            }
            $jourSemaine = date('N', $heureDebut); // Mettre à jour le jour de la semaine

            // Réinitialiser les heures de fin de journée pour le nouveau jour
            if ($jourSemaine == 6) {
                $finJournee = strtotime(date('Y-m-d', $heureDebut) . ' 13:00:00');
            } else {
                $finJournee = strtotime(date('Y-m-d', $heureDebut) . ' 17:15:00');
                // Réinitialiser la pause déjeuner
                $debutPause = strtotime(date('Y-m-d', $heureDebut) . ' 11:30:00');
                $finPause = strtotime(date('Y-m-d', $heureDebut) . ' 12:15:00');
            }
            $heureFin = $heureDebut + ($dureeMinutes * 60);
        } else {
            // Gérer la pause déjeuner les jours de la semaine
            if ($jourSemaine >= 1 && $jourSemaine <= 5) {
                if ($heureDebut < $finPause && $heureFin > $debutPause) {
                    // Ajouter 45 minutes à l'heure de fin si on traverse la pause déjeuner
                    $heureFin += 45 * 60;
                }
            }
            return date('Y-m-d H:i:s', $heureFin);
        }
    }

    return date('Y-m-d H:i:s', $heureFin);
}
// Après la fin de la phase de coupeur, libérer la table
function libererTable($tableId, &$tablesOccupees, $dateFinPhase) {
    if (isset($tablesOccupees[$tableId])) {
        // Mettre à jour la date de fin d'occupation de la table
        $tablesOccupees[$tableId]['dateFin'] = $dateFinPhase;
        
    }
}

// Fonction pour obtenir la disponibilité initiale des opérateurs
function obtenirDisponibiliteOperateurs($operateurs_par_fonction) {
    $disponibilite = [];
    foreach ($operateurs_par_fonction as $fonction => $operateurs) {
        foreach ($operateurs as $operateur) {
            $disponibilite[$operateur] = date('Y-m-d 08:00:00'); // Disponibilité initiale
        }
    }
    return $disponibilite;
}

// Fonction pour trier les opérateurs selon leur disponibilité
function trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_disponibles) {
    usort($operateurs_disponibles, function($a, $b) use ($datesFinOperateurs) {
        return strtotime($datesFinOperateurs[$a] ?? '1970-01-01') - strtotime($datesFinOperateurs[$b] ?? '1970-01-01');
    });
    return $operateurs_disponibles;
}

function assignerCommandes($commandes,$Tables, $operateurs_par_fonction) {
    $plan_de_travail = [];
    $dureeTravailMax = 8.15 * 60; // 8 heures 15 minutes en minutes

    // Initialiser les disponibilités des opérateurs
    $datesFinOperateurs = obtenirDisponibiliteOperateurs($operateurs_par_fonction);

    // Phases à parcourir : manutention, matelasseur, coupeur, etiquetage
    $phases = [
        'HSR' => 'manutention',
        'HSM' => 'matelasseur',
        'HSC' => 'coupeur',
        'HSE' => 'etiquetage'
    ];

    // Classer les commandes selon la phase de manutention
    $commandes_avec_manutention = array_filter($commandes, function($c) {
        return isset($c['relaxation']) && $c['relaxation'] === 'Oui';
    });

    $commandes_sans_manutention = array_filter($commandes, function($c) {
        return isset($c['relaxation']) && $c['relaxation'] === 'Non';
    });


// Initialiser les tables occupées
$tablesOccupees = [];



// Traiter les commandes sans manutention
foreach ($commandes_sans_manutention as $commande) {
    
    $ligne = [
        'codeMatelas' => $commande['codeMatelas'],
        'commande' => $commande['commande'],
        'article' => $commande['article'],
        'partie' => $commande['partie'],
        'sub' => $commande['sub'],
        'quantiteMatelas' => $commande['quantiteMatelas'],
		'longueurMatelas' => $commande['longueurMatelas'],
        'relaxation' => $commande['relaxation']
    ];

    // Initialiser la date de début de la commande
    $dateDebutCommande = date('Y-m-d 08:00:00');

    // Boucler sur chaque phase et assigner les opérateurs
    foreach ($phases as $key => $fonction) {
      
        
        // Récupérer les opérateurs disponibles pour cette fonction
        $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);

        // Assigner les opérateurs nécessaires
            if ($fonction === 'manutention' && $commande['relaxation'] === 'Non') {
            continue;
        }elseif ( $fonction === 'matelasseur') {
			// Trier les opérateurs disponibles pour le matelassage
                $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
                // Assigner deux opérateurs pour ces phases
                $operateur1 = array_shift($operateurs_disponibles);
                $operateur2 = array_shift($operateurs_disponibles);
				 // Calculer la date de début pour l'opérateur assigné
        $dateDebutCommande = max($dateDebutCommande, $datesFinOperateurs[$operateur1] ?? $dateDebutCommande);
			
			
			
				    // Trouver une table disponible pour la phase de matelassage
    $tableAssignee = trouverTableDisponible($commande, $Tables, $tablesOccupees, $dateDebutCommande);

    if ($tableAssignee) {
        // Assigner la table à la commande
        $ligne['table'] = $tableAssignee;
        
        // Ajouter la commande à la table occupée et mettre à jour la somme des longueurs
        if (!isset($tablesOccupees[$tableAssignee])) {
            $tablesOccupees[$tableAssignee] = ['sommeLongueurs' => 0];
        }
        $tablesOccupees[$tableAssignee]['sommeLongueurs'] += $commande['longueurMatelas'];
                

        $duree = $commande[$key]; // Durée en heures

        // Calculer l'heure de fin pour l'opérateur assigné
        $dateFin = calculerDateFin($dateDebutCommande, $duree);

        // Mettre à jour la disponibilité de l'opérateur
        $datesFinOperateurs[$operateur1] = $dateFin;
		$datesFinOperateurs[$operateur2] = $dateFin;

        // Ajouter l'assignation au plan de travail
        $ligne[$fonction . 'operateur1'] = $operateur1;
		$ligne[$fonction . 'operateur2'] = $operateur2;
        $ligne[$fonction . 'Debut'] = $dateDebutCommande;
        $ligne[$fonction . 'Fin'] = $dateFin;
		$ligne[$fonction . 'HS'] = $duree;

        // Mettre à jour la date de début pour la phase suivante
        $dateDebutCommande = $dateFin;
        
		}else {
        // Aucune table disponible, trouver la table qui se libère le plus tôt
        $tableDisponiblePlusTot = null;
        $dateFinMinimale = null;

        foreach ($tablesOccupees as $table => $detailsTable) {
            // Comparer les dates de fin pour trouver la table qui se libère en premier
            if (!isset($dateFinMinimale) || $detailsTable['dateFin'] < $dateFinMinimale) {
                $dateFinMinimale = $detailsTable['dateFin'];
                $tableDisponiblePlusTot = $table;
            }
        }

        if ($tableDisponiblePlusTot) {
			// Assigner deux opérateurs
            $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
            $operateur1 = array_shift($operateurs_disponibles);
            $operateur2 = array_shift($operateurs_disponibles);
            // Mettre à jour la date de début avec la date de fin de la table qui se libère en premier
            $dateDebutCommande = max($dateFinMinimale, $datesFinOperateurs[$operateur1] ?? $dateFinMinimale);

            // Réassigner la table à la commande
            $ligne['table'] = $tableDisponiblePlusTot;

            // Mettre à jour la somme des longueurs pour cette table
            if (!isset($tablesOccupees[$tableDisponiblePlusTot])) {
                $tablesOccupees[$tableDisponiblePlusTot] = ['sommeLongueurs' => 0];
            }
            $tablesOccupees[$tableDisponiblePlusTot]['sommeLongueurs'] += $commande['longueurMatelas'];

            // Assigner deux opérateurs
            $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
            $operateur1 = array_shift($operateurs_disponibles);
            $operateur2 = array_shift($operateurs_disponibles);

            // Calculer la nouvelle date de fin pour la commande
            $duree = $commande[$key]; // Durée en heures
            $dateFin = calculerDateFin($dateDebutCommande, $duree);

            // Mettre à jour la disponibilité des opérateurs
            $datesFinOperateurs[$operateur1] = $dateFin;
            $datesFinOperateurs[$operateur2] = $dateFin;

            // Ajouter l'assignation au plan de travail
            $ligne[$fonction . 'operateur1'] = $operateur1;
            $ligne[$fonction . 'operateur2'] = $operateur2;
            $ligne[$fonction . 'Debut'] = $dateDebutCommande;
            $ligne[$fonction . 'Fin'] = $dateFin;
			$ligne[$fonction . 'HS'] = $duree;

            // Mettre à jour la table pour qu'elle soit occupée à partir de cette nouvelle date
            $tablesOccupees[$tableDisponiblePlusTot]['dateFin'] = $dateFin;

            // La table est occupée pour la phase de matelassage
            $dateDebutCommande = $dateFin;
        } else {
            // Aucune table ne sera disponible prochainement, traiter ce cas (par exemple, attendre ou signaler un problème)
            echo "Aucune table ne sera disponible pour la commande " . $commande['id'] . "<br>";
        }
    }
            } elseif( $fonction === 'coupeur') {
				

		
$operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
                // Assigner un seul opérateur pour les autres phases
                $operateur1 = array_shift($operateurs_disponibles);
                $operateur2 = '';
				$dateDebutCommande = max($dateDebutCommande, $datesFinOperateurs[$operateur1] ?? $dateDebutCommande);

        $duree = $commande[$key]; // Durée en heures

        // Calculer l'heure de fin pour l'opérateur assigné
        $dateFin = calculerDateFin($dateDebutCommande, $duree);

        // Mettre à jour la disponibilité de l'opérateur
        $datesFinOperateurs[$operateur1] = $dateFin;
		$datesFinOperateurs[$operateur2] = $dateFin;

        // Ajouter l'assignation au plan de travail
        $ligne[$fonction . 'operateur1'] = $operateur1;
		$ligne[$fonction . 'operateur2'] = $operateur2;
        $ligne[$fonction . 'Debut'] = $dateDebutCommande;
        $ligne[$fonction . 'Fin'] = $dateFin;
		$ligne[$fonction . 'HS'] = $duree;
		
		// Mettre à jour la date de début pour la phase suivante
        $dateDebutCommande = $dateFin;
		
        	// Libérer la table après la coupeur
        libererTable($tableAssignee, $tablesOccupees, $dateFin);

        
    
            }else {
$operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
                // Assigner un seul opérateur pour les autres phases
                $operateur1 = array_shift($operateurs_disponibles);
                $operateur2 = '';
				$dateDebutCommande = max($dateDebutCommande, $datesFinOperateurs[$operateur1] ?? $dateDebutCommande);

        $duree = $commande[$key]; // Durée en heures

        // Calculer l'heure de fin pour l'opérateur assigné
        $dateFin = calculerDateFin($dateDebutCommande, $duree);

        // Mettre à jour la disponibilité de l'opérateur
        $datesFinOperateurs[$operateur1] = $dateFin;
		$datesFinOperateurs[$operateur2] = $dateFin;

        // Ajouter l'assignation au plan de travail
        $ligne[$fonction . 'operateur1'] = $operateur1;
		$ligne[$fonction . 'operateur2'] = $operateur2;
        $ligne[$fonction . 'Debut'] = $dateDebutCommande;
        $ligne[$fonction . 'Fin'] = $dateFin;
		$ligne[$fonction . 'HS'] = $duree;
		
		// Mettre à jour la date de début pour la phase suivante
        $dateDebutCommande = $dateFin;
            }

       
    }

    // Ajouter la ligne au plan de travail
    $plan_de_travail[] = $ligne;
}

// Traiter les commandes avec manutention en premier
foreach ($commandes_avec_manutention as $commande) {
    $ligne = [
        'codeMatelas' => $commande['codeMatelas'],
        'commande' => $commande['commande'],
        'article' => $commande['article'],
        'partie' => $commande['partie'],
        'sub' => $commande['sub'],
        'quantiteMatelas' => $commande['quantiteMatelas'],
		'longueurMatelas' => $commande['longueurMatelas'],
        'relaxation' => $commande['relaxation']
    ];

    // Boucler sur chaque phase et assigner les opérateurs
    foreach ($phases as $key => $fonction) {
        
        	$dateDebutCommande = date('Y-m-d 08:00:00');
		if ($fonction == 'matelasseur'){
		$dateDebutCommande = date('Y-m-d 08:00:00', strtotime('+1 day'));
		}else{
			$dateDebutCommande = date('Y-m-d 08:00:00', strtotime('+1 day'));
		}
        
        // Gestion du matelassage
        if ($fonction == 'manutention'){
				$operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
				// Assigner deux opérateurs pour ces phases
                $operateur1 = array_shift($operateurs_disponibles);
                $operateur2 = array_shift($operateurs_disponibles);
				 // Calculer la date de début pour l'opérateur assigné
        $dateDebutCommande =  $datesFinOperateurs[$operateur1] ?? $dateDebutCommande;

        $duree = $commande[$key]; // Durée en heures

        // Calculer l'heure de fin pour l'opérateur assigné
        $dateFin = calculerDateFin($dateDebutCommande, $duree);

        // Mettre à jour la disponibilité de l'opérateur
        $datesFinOperateurs[$operateur1] = $dateFin;
		$datesFinOperateurs[$operateur2] = $dateFin;

        // Ajouter l'assignation au plan de travail
        $ligne[$fonction . 'operateur1'] = $operateur1;
		$ligne[$fonction . 'operateur2'] = $operateur2;
		$ligne[$fonction . 'duree'] = $duree * 60;
        $ligne[$fonction . 'Debut'] = $dateDebutCommande;
        $ligne[$fonction . 'Fin'] = $dateFin;
		$ligne[$fonction . 'HS'] = $duree;

        // Mettre à jour la date de début pour la phase suivante
        $dateDebutCommande = $dateFin;
				
			}elseif ($fonction == 'matelasseur') {
				
				// Trier les opérateurs disponibles pour le matelassage
                $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
                // Assigner deux opérateurs pour ces phases
                $operateur1 = array_shift($operateurs_disponibles);
                $operateur2 = array_shift($operateurs_disponibles);
				 // Calculer la date de début pour l'opérateur assigné
        $dateDebutCommande = max($dateDebutCommande, $datesFinOperateurs[$operateur1] ?? $dateDebutCommande);
    // Trouver une table disponible pour la phase de matelassage
    $tableAssignee = trouverTableDisponible($commande, $Tables, $tablesOccupees, $dateDebutCommande);

    if ($tableAssignee) {
        // Assigner la table à la commande
        $ligne['table'] = $tableAssignee;
        
        // Ajouter la commande à la table occupée et mettre à jour la somme des longueurs
        if (!isset($tablesOccupees[$tableAssignee])) {
            $tablesOccupees[$tableAssignee] = ['sommeLongueurs' => 0];
        }
        $tablesOccupees[$tableAssignee]['sommeLongueurs'] += $commande['longueurMatelas'];

		if (date('w', strtotime($dateDebutCommande)) == 0) {
    // Ajouter un jour si c'est le cas
    $dateDebutCommande = date($dateDebutCommande, strtotime($dateDebutCommande . ' +1 day'));
}
        $duree = $commande[$key]; // Durée en heures
        $dateFin = calculerDateFin($dateDebutCommande, $duree);

        // Mettre à jour la disponibilité des opérateurs et ajouter l'assignation au plan de travail
        $datesFinOperateurs[$operateur1] = $dateFin;
        $datesFinOperateurs[$operateur2] = $dateFin;
        
        $ligne[$fonction . 'operateur1'] = $operateur1;
        $ligne[$fonction . 'operateur2'] = $operateur2;
        
        $ligne[$fonction . 'Debut'] = $dateDebutCommande;
        $ligne[$fonction . 'Fin'] = $dateFin;
		$ligne[$fonction . 'HS'] = $duree;
		

        // La table est occupée pour la phase de matelassage, on met à jour la date pour la prochaine phase
        $dateDebutCommande = $dateFin;

    } else {
        // Aucune table disponible, trouver la table qui se libère le plus tôt
        $tableDisponiblePlusTot = null;
        $dateFinMinimale = null;

        foreach ($tablesOccupees as $table => $detailsTable) {
            // Comparer les dates de fin pour trouver la table qui se libère en premier
            if (!isset($dateFinMinimale) || $detailsTable['dateFin'] < $dateFinMinimale) {
                $dateFinMinimale = $detailsTable['dateFin'];
                $tableDisponiblePlusTot = $table;
            }
        }

        if ($tableDisponiblePlusTot) {
            // Assigner deux opérateurs
            $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);
            $operateur1 = array_shift($operateurs_disponibles);
            $operateur2 = array_shift($operateurs_disponibles);
            // Mettre à jour la date de début avec la date de fin de la table qui se libère en premier
            $dateDebutCommande = max($dateFinMinimale, $datesFinOperateurs[$operateur1] ?? $dateFinMinimale);

            // Réassigner la table à la commande
            $ligne['table'] = $tableDisponiblePlusTot;

            // Mettre à jour la somme des longueurs pour cette table
            if (!isset($tablesOccupees[$tableDisponiblePlusTot])) {
                $tablesOccupees[$tableDisponiblePlusTot] = ['sommeLongueurs' => 0];
            }
            $tablesOccupees[$tableDisponiblePlusTot]['sommeLongueurs'] += $commande['longueurMatelas'];

            

            // Calculer la nouvelle date de fin pour la commande
            $duree = $commande[$key]; // Durée en heures
            $dateFin = calculerDateFin($dateDebutCommande, $duree);

            // Mettre à jour la disponibilité des opérateurs
            $datesFinOperateurs[$operateur1] = $dateFin;
            $datesFinOperateurs[$operateur2] = $dateFin;

            // Ajouter l'assignation au plan de travail
            $ligne[$fonction . 'operateur1'] = $operateur1;
            $ligne[$fonction . 'operateur2'] = $operateur2;
            $ligne[$fonction . 'Debut'] = $dateDebutCommande;
            $ligne[$fonction . 'Fin'] = $dateFin;
			$ligne[$fonction . 'HS'] = $duree;

            // Mettre à jour la table pour qu'elle soit occupée à partir de cette nouvelle date
            $tablesOccupees[$tableDisponiblePlusTot]['dateFin'] = $dateFin;

            // La table est occupée pour la phase de matelassage
            $dateDebutCommande = $dateFin;
        } else {
            // Aucune table ne sera disponible prochainement, traiter ce cas (par exemple, attendre ou signaler un problème)
            echo "Aucune table ne sera disponible pour la commande " . $commande['id'] . "<br>";
        }
    }
} elseif ($fonction == 'coupeur') {

            // Trier les opérateurs disponibles pour la coupe
            $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);

            // Assigner un seul opérateur
            $operateur1 = array_shift($operateurs_disponibles);
            $operateur2 = ''; // Aucun opérateur 2 pour la phase coupe

            // Calculer la date de début et de fin pour l'opérateur assigné
            $dateDebutCommande = max($dateFin, $datesFinOperateurs[$operateur1] ?? $dateDebutCommande);
            $duree = $commande[$key]; // Durée en heures
            $dateFin = calculerDateFin($dateDebutCommande, $duree);

            // Mettre à jour la disponibilité des opérateurs et ajouter l'assignation au plan de travail
            $datesFinOperateurs[$operateur1] = $dateFin;

            $ligne[$fonction . 'operateur1'] = $operateur1;
            $ligne[$fonction . 'operateur2'] = $operateur2;
            $ligne[$fonction . 'Debut'] = $dateDebutCommande;
            $ligne[$fonction . 'Fin'] = $dateFin;
			$ligne[$fonction . 'HS'] = $duree;

            // Mettre à jour la date de début pour la phase suivante
            $dateDebutCommande = $dateFin;
			
			// Libérer la table après la coupeur
        libererTable($tableAssignee, $tablesOccupees, $dateFin);

        } else {
            // Pour les autres phases (manutention, étiquetage, etc.), pas besoin d'assigner une table
            $operateurs_disponibles = trierOperateursParDisponibilite($datesFinOperateurs, $operateurs_par_fonction[$fonction]);

            // Assigner un opérateur pour ces phases
            $operateur1 = array_shift($operateurs_disponibles);
            $operateur2 = ''; // Un seul opérateur pour les autres phases

            // Calculer la date de début et de fin pour l'opérateur assigné
            $dateDebutCommande = max($dateFin, $datesFinOperateurs[$operateur1] ?? $dateDebutCommande);
            $duree = $commande[$key]; // Durée en heures
            $dateFin = calculerDateFin($dateDebutCommande, $duree);

            // Mettre à jour la disponibilité des opérateurs
            $datesFinOperateurs[$operateur1] = $dateFin;

            // Ajouter l'assignation au plan de travail
            $ligne[$fonction . 'operateur1'] = $operateur1;
            $ligne[$fonction . 'operateur2'] = $operateur2;
            $ligne[$fonction . 'Debut'] = $dateDebutCommande;
            $ligne[$fonction . 'Fin'] = $dateFin;
			$ligne[$fonction . 'HS'] = $duree;

            // Mettre à jour la date de début pour la phase suivante
            $dateDebutCommande = $dateFin;
        }
    }

    // Ajouter la ligne au plan de travail
    $plan_de_travail[] = $ligne;
}


        
    





    

    return $plan_de_travail;

}

$plan_de_travail = assignerCommandes($commandes,$Tables, $operateurs_par_fonction);

// Requête SQL d'insertion
$sql = "INSERT INTO planification (commande, article, partie, sub, quantiteMatelas, codeMatelas, operateur1, operateur2, phase, datetime_debut, datetime_fin, HS, tabl)
        VALUES (:commande, :article, :partie, :sub, :quantiteMatelas, :codeMatelas, :operateur1, :operateur2, :phase, :datetime_debut, :datetime_fin , :HS, :table)";

// Préparer la requête
$stmt = $conn->prepare($sql);

// Boucler sur le plan de travail pour insérer chaque phase
foreach ($plan_de_travail as $ligne) {
    // Pour chaque phase (ex : matelasseur, manutention, coupeur, etc.)
    foreach ([ 'manutention','matelasseur', 'coupeur' , 'etiquetage'] as $phase) {
        // Vérifier si la phase existe dans la ligne actuelle
        if (isset($ligne[$phase . 'operateur1'])) {
            // Assigner les valeurs de chaque colonne
            $stmt->bindParam(':commande', $ligne['commande']);
            $stmt->bindParam(':article', $ligne['article']);
            $stmt->bindParam(':partie', $ligne['partie']);
            $stmt->bindParam(':sub', $ligne['sub']);
            $stmt->bindParam(':table', $ligne['table']);
            $stmt->bindParam(':quantiteMatelas', $ligne['quantiteMatelas']);
            $stmt->bindParam(':codeMatelas', $ligne['codeMatelas']);
            $stmt->bindParam(':operateur1', $ligne[$phase . 'operateur1']);
            $stmt->bindParam(':operateur2', $ligne[$phase . 'operateur2']);
			
            
            $stmt->bindParam(':datetime_debut', $ligne[$phase . 'Debut']);
            $stmt->bindParam(':datetime_fin', $ligne[$phase . 'Fin']);
            $stmt->bindParam(':HS', $ligne[$phase . 'HS']);
if ($phase == "matelasseur"){
				$phase = "matelassage";
			}elseif ($phase == "manutention"){
				$phase = "relaxation";
				
			}elseif ($phase== "coupeur"){
				$phase="coupe";
			
			}else{
				$phase = "etiquetage";
			}
			$stmt->bindParam(':phase', $phase);  // Le nom de la phase
            // Exécuter la requête pour chaque phase
            $stmt->execute();
        }
    }
}


echo "Plan de travail généré : ";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Plan de Travail</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Plan de Travail</h1>
    <table>
        <thead>
            <tr>
                <th>Code Matelas</th>
                <th>Commande</th>
                <th>Article</th>
                <th>Partie</th>
                <th>Sub</th>
                <th>Quantité Matelas</th>
				<th>Longueur Matelas</th>
                <th>Relaxation</th>
                <th>Manutention Operateur 1</th>
				<th>Manutention Operateur 2</th>
                <th>Manutention Début</th>
                <th>Manutention Fin</th>
				<th>Table Matelasseur</th>
                <th>Matelasseur Operatur 1</th>
				<th>Matelasseur Operatur 2</th>
                <th>Matelasseur Début</th>
                <th>Matelasseur Fin</th>
                <th>Coupeur Matricule</th>
                <th>Coupeur Début</th>
                <th>Coupeur Fin</th>
                <th>Étiquetage Matricule</th>
                <th>Étiquetage Début</th>
                <th>Étiquetage Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plan_de_travail as $ligne): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ligne['codeMatelas']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['commande']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['article']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['partie']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['sub']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['quantiteMatelas']); ?></td>
					<td><?php echo htmlspecialchars($ligne['longueurMatelas']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['relaxation'] ?? ''); ?></td>
					
                    <td><?php echo htmlspecialchars($ligne['manutentionoperateur1'] ?? ''); ?></td>
					<td><?php echo htmlspecialchars($ligne['manutentionoperateur2'] ?? ''); ?></td>
                    <td><?php echo !empty($ligne['manutentionDebut']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['manutentionDebut']))) : ''; ?></td>
                    <td><?php echo !empty($ligne['manutentionFin']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['manutentionFin']))) : ''; ?></td>
					<td><?php echo htmlspecialchars($ligne['table'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($ligne['matelasseuroperateur1'] ?? ''); ?></td>
					<td><?php echo htmlspecialchars($ligne['matelasseuroperateur2'] ?? ''); ?></td>
                    <td><?php echo !empty($ligne['matelasseurDebut']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['matelasseurDebut']))) : ''; ?></td>
                    <td><?php echo !empty($ligne['matelasseurFin']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['matelasseurFin']))) : ''; ?></td>
                    <td><?php echo htmlspecialchars($ligne['coupeuroperateur1'] ?? ''); ?></td>
                    <td><?php echo !empty($ligne['coupeurDebut']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['coupeurDebut']))) : ''; ?></td>
                    <td><?php echo !empty($ligne['coupeurFin']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['coupeurFin']))) : ''; ?></td>
                    <td><?php echo htmlspecialchars($ligne['etiquetageoperateur1'] ?? ''); ?></td>
                    <td><?php echo !empty($ligne['etiquetageDebut']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['etiquetageDebut']))) : ''; ?></td>
                    <td><?php echo !empty($ligne['etiquetageFin']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($ligne['etiquetageFin']))) : ''; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
