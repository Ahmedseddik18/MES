<?php
include("../php/db.php");
include("../php/fonction.php");

// Requête SQL pour les heures supplémentaires
$query_hs = "
    SELECT 
        DATE(datetime_debut) AS date, 
        phase, 
        operateur1 AS operateur,
        SUM(CAST(HS AS DECIMAL(10, 2))) AS total_HS
    FROM 
        planification
    GROUP BY 
        DATE(datetime_debut), 
        phase, 
        operateur1
    UNION ALL
    SELECT 
        DATE(datetime_debut) AS date, 
        phase, 
        operateur2 AS operateur,
        SUM(CAST(HS AS DECIMAL(10, 2))) AS total_HS
    FROM 
        planification
    WHERE 
        operateur2 <> '0'
    GROUP BY 
        DATE(datetime_debut), 
        phase, 
        operateur2
    ORDER BY 
        date ASC, 
        CASE 
            WHEN phase = 'manutention' THEN 1
            WHEN phase = 'matelasseur' THEN 2
            WHEN phase = 'coupeur' THEN 3
            WHEN phase = 'etiquetage' THEN 4
            ELSE 5
        END,
        operateur ASC;
";

$stmt_hs = $conn->prepare($query_hs);
$stmt_hs->execute();

// Récupérer les résultats des heures supplémentaires
$results_hs = $stmt_hs->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour les détails de la planification
$query_details = "
    SELECT 
	HS,
        phase, 
        operateur1 AS operateur, 
        commande, 
        article, 
        datetime_debut, 
		codeMatelas,
        datetime_fin
    FROM 
        planification
    WHERE 
        operateur1 <> '0' 
    UNION ALL
    SELECT 
	    HS,
        phase, 
        operateur2 AS operateur, 
        commande, 
        article, 
        datetime_debut, 
		codeMatelas,
        datetime_fin
    FROM 
        planification
    WHERE 
        operateur2 <> '0' 
    ORDER BY 
	    operateur ASC,
        datetime_debut ASC,
        
         CASE 
            WHEN phase = 'manutention' THEN 1
            WHEN phase = 'matelasseur' THEN 2
            WHEN phase = 'coupeur' THEN 3
            WHEN phase = 'etiquetage' THEN 4
            ELSE 5
        END 
        ;
";

$stmt_details = $conn->prepare($query_details);
$stmt_details->execute();

// Récupérer les résultats des détails
$results_details = $stmt_details->fetchAll(PDO::FETCH_ASSOC);




// Requête SQL pour les opérations par opérateur
$query_operations = "
    SELECT 
        DATE(datetime_debut) AS date,  
	HS,
        phase, 
        operateur1 AS operateur, 
        commande, 
        article, 
        datetime_debut, 
		codeMatelas,
        datetime_fin
    FROM 
        planification
    WHERE 
        operateur1 <> '0' 
    UNION ALL
    SELECT 
	DATE(datetime_debut) AS date, 
	    HS,
        phase, 
        operateur2 AS operateur, 
        commande, 
        article, 
        datetime_debut, 
		codeMatelas,
        datetime_fin
    FROM 
        planification
    WHERE 
        operateur2 <> '0' 
    ORDER BY 
	 
	date ASC,
        operateur ASC,
        
         CASE 
            WHEN phase = 'manutention' THEN 1
            WHEN phase = 'matelasseur' THEN 2
            WHEN phase = 'coupeur' THEN 3
            WHEN phase = 'etiquetage' THEN 4
            ELSE 5
        END 
        ;
";

$stmt_operations = $conn->prepare($query_operations);
$stmt_operations->execute();

// Récupérer les résultats des opérations
$results_operations = $stmt_operations->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé des Heures Supplémentaires</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Résumé des Heures Supplémentaires</h1>

    <!-- Tableau des heures supplémentaires -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Phase</th>
                <th>Opérateur</th>
                <th>Total HS (heures)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results_hs as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['phase']); ?></td>
                    <td><?php echo htmlspecialchars($row['operateur']); ?></td>
                    <td><?php echo number_format($row['total_HS'] ,2); ?></td> <!-- Convertir les minutes en heures -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Détails de la Planification</h1>

    <!-- Tableau des détails de la planification -->
    <table>
        <thead>
            <tr>
                <th>Phase</th>
                <th>Opérateur</th>
                <th>Commande</th>
                <th>Article</th>
                <th>Code Matelas</th>
                <th>HS</th>
                <th>Date & Heure Début</th>
                <th>Date & Heure Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results_details as $row_detail): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_detail['phase']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['operateur']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['commande']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['article']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['codeMatelas']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['HS']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['datetime_debut']); ?></td>
                    <td><?php echo htmlspecialchars($row_detail['datetime_fin']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Opérations par Opérateur</h1>

    <!-- Tableau des opérations par opérateur -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Phase</th>
                <th>Opérateur</th>
                <th>Commande</th>
                <th>Article</th>
                <th>Code Matelas</th>
                <th>Date & Heure Début</th>
                <th>Date & Heure Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results_operations as $row_operation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_operation['date']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['phase']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['operateur']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['commande']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['article']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['codeMatelas']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['datetime_debut']); ?></td>
                    <td><?php echo htmlspecialchars($row_operation['datetime_fin']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
