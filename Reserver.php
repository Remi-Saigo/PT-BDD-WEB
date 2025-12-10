<?php
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
    $numerochambre = $_POST['numero_chambre'];
    
    try {
        $stmt = $dbh->prepare("SELECT Disponibilité FROM Chambre WHERE `NumeroChambre` = ?");
        $stmt->execute([$numerochambre]);
        $chambre = $stmt->fetch();
        if ($chambre && $chambre['Disponibilité'] == 1) {
            $updateStmt = $dbh->prepare("UPDATE Chambre SET Disponibilité = 0 WHERE `NumeroChambre` = ?");
            $updateStmt->execute([$numerochambre]);
            
            $message = "Chambre n°$numerochambre réservée avec succès !";
        } else {
            $error = "Cette chambre n'est plus disponible.";
        }
    } catch(PDOException $e) {
        $error = "Erreur lors de la réservation : " . $e->getMessage();
    }
}
try {
    $stmt = $dbh->query("SELECT `NumeroChambre`, `Etage Chambre`, `Type Chambre`, Prix 
                         FROM Chambre 
                         WHERE Disponibilité = 1 
                         ORDER BY `NumeroChambre`");
    $chambresDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Erreur lors de la récupération des chambres : " . $e->getMessage();
    $chambresDisponibles = [];
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Chambres</title>
    <style>/* CSS avec ChatGPT */
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
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
        
        .content {
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
        
        .chambres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .chambre-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .chambre-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .chambre-numero {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .chambre-info {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            color: #6c757d;
        }
        
        .prix {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
            margin: 15px 0;
        }
        
        .btn-reserver {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .btn-reserver:hover {
            opacity: 0.9;
        }
        
        .no-chambres {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-chambres-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        .no-chambres h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏨 Réservation de Chambres</h1>
            <p>Choisissez votre chambre parmi nos disponibilités</p>
        </div>
        
        <div class="content">
            <?php if ($message): ?>
                <div class="message success">✓ <?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error">✗ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (count($chambresDisponibles) > 0): ?>
                <h2 style="margin-bottom: 20px; color: #333;">Chambres disponibles (<?php echo count($chambresDisponibles); ?>)</h2>
                
                <div class="chambres-grid">
                    <?php foreach ($chambresDisponibles as $chambre): ?>
                        <div class="chambre-card">
                            <div class="chambre-numero">Chambre #<?php echo htmlspecialchars($chambre['NumeroChambre']); ?></div>
                            
                            <div class="chambre-info">
                                <div class="info-row">
                                    <span class="info-label">Étage:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($chambre['Etage Chambre'] ?? 'N/A'); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Type:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($chambre['Type Chambre'] ?? 'Standard'); ?></span>
                                </div>
                            </div>
                            
                            <div class="prix"><?php echo number_format($chambre['Prix'], 2, ',', ' '); ?> €</div>
                            
                            <form method="POST">
                                <input type="hidden" name="numero_chambre" value="<?php echo htmlspecialchars($chambre['NumeroChambre']); ?>">
                                <button type="submit" name="reserver" class="btn-reserver">Réserver maintenant</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-chambres">
                    <div class="no-chambres-icon">😔</div>
                    <h2>Aucune chambre disponible</h2>
                    <p>Toutes nos chambres sont actuellement réservées. Veuillez réessayer plus tard.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
*