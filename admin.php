<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'PHPBDHOTEL';
$username = 'login4439';
$password = 'HNLCQSaIAXkvUJo';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = '';
$error = '';

// Traitement de l'ajout de chambre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $etage = $_POST['etage'];
    $type = $_POST['type'];
    $prix = $_POST['prix'];
    $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;
    
    try {
        // Insertion de la nouvelle chambre (sans spécifier le numéro car AUTO_INCREMENT)
        $stmt = $pdo->prepare("INSERT INTO Chambre (`Etage Chambre`, `Type Chambre`, Prix, Disponibilité) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$etage, $type, $prix, $disponibilite]);
        
        $numeroChambre = $pdo->lastInsertId();
        $message = "Chambre n°$numeroChambre ajoutée avec succès !";
        
        // Réinitialiser le formulaire
        $_POST = array();
    } catch(PDOException $e) {
        $error = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}

// Récupérer toutes les chambres pour affichage
try {
    $stmt = $pdo->query("SELECT `Numéro Chambre`, `Etage Chambre`, `Type Chambre`, Prix, Disponibilité 
                         FROM Chambre 
                         ORDER BY `Numéro Chambre` DESC");
    $chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Erreur lors de la récupération : " . $e->getMessage();
    $chambres = [];
}
?>


<head>
    <title>Ajouter une Chambre</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .form-content {
            padding: 40px;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 1em;
        }
        
        .form-group input,
        .form-group select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .checkbox-group label {
            cursor: pointer;
            font-weight: 500;
            color: #333;
        }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s, transform 0.2s;
        }
        
        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .chambres-list {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .list-header {
            background: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .list-header h2 {
            color: #333;
            font-size: 1.8em;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .badge.disponible {
            background: #d4edda;
            color: #155724;
        }
        
        .badge.occupee {
            background: #f8d7da;
            color: #721c24;
        }
        
        .prix-cell {
            font-weight: 600;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="header">
                <h1>➕ Ajouter une Chambre</h1>
                <p>Complétez le formulaire pour ajouter une nouvelle chambre à l'hôtel</p>
            </div>
            
            <div class="form-content">
                <?php if ($message): ?>
                    <div class="message success">✓ <?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="message error">✗ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="etage">Étage *</label>
                            <input type="number" id="etage" name="etage" min="0" max="50" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Type de Chambre *</label>
                            <select id="type" name="type" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="Simple">Simple</option>
                                <option value="Double">Double</option>
                                <option value="Suite">Suite</option>
                                <option value="Familiale">Familiale</option>
                                <option value="Deluxe">Deluxe</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix">Prix (€) *</label>
                            <input type="number" id="prix" name="prix" min="0" step="0.01" placeholder="Ex: 89.99" required>
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="disponibilite" name="disponibilite" checked>
                        <label for="disponibilite">Chambre disponible à la réservation</label>
                    </div>
                    
                    <button type="submit" name="ajouter" class="btn-submit">Ajouter la chambre</button>
                </form>
            </div>
        </div>
        
        <div class="chambres-list">
            <div class="list-header">
                <h2>📋 Liste des Chambres (<?php echo count($chambres); ?>)</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N° Chambre</th>
                            <th>Étage</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($chambres) > 0): ?>
                            <?php foreach ($chambres as $chambre): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($chambre['Numéro Chambre']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($chambre['Etage Chambre']); ?></td>
                                    <td><?php echo htmlspecialchars($chambre['Type Chambre']); ?></td>
                                    <td class="prix-cell"><?php echo number_format($chambre['Prix'], 2, ',', ' '); ?> €</td>
                                    <td>
                                        <span class="badge <?php echo $chambre['Disponibilité'] == 1 ? 'disponible' : 'occupee'; ?>">
                                            <?php echo $chambre['Disponibilité'] == 1 ? 'Disponible' : 'Occupée'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #6c757d;">
                                    Aucune chambre enregistrée pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
