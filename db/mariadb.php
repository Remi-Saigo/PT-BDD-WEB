<?php
//essaye de faire le code qui est dans le bloc try
try {
    // se connecte à la BD et stocke la connexion dans  $dbh
    $dbh = new PDO('mysql:host=localhost;dbname=PHPBDHOTEL', 'login4439', 'HNLCQSaIAXkvUJo');
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
    $dbh = NULL;
}
?>