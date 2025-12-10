<?php

$erreurs = [];
$nom = $prenom = $email = $password = "";
$success = false;

if (isset($_POST['transmettre'])) {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($nom)) $erreurs['nom'] = true;
    if (empty($prenom)) $erreurs['prenom'] = true;
    if (empty($email)) $erreurs['email'] = true;
    if (empty($password)) $erreurs['password'] = true;

    if (empty($erreurs)) {
        $stmt = $dbh->prepare("SELECT email FROM Client WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetch()) {
            $erreurs['email'] = true;
            $erreurs['exists'] = "Ce mail est déjà utilisé.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $role = "user";

            $sql = $dbh->prepare("
                INSERT INTO Client (nom, prenom, email, password, role) 
                VALUES (:nom, :prenom, :email, :password, :role)
            ");
            $sql->bindParam(':nom', $nom, PDO::PARAM_STR);
            $sql->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $passwordHash, PDO::PARAM_STR);
            $sql->bindParam(':role', $role, PDO::PARAM_STR);

            if ($sql->execute()) {
                $_SESSION['prenom'] = $prenom;
                $_SESSION['login'] = $email;
                $_SESSION['role'] = $role;

                $success = true;
            } else {
                $erreurs['db'] = "Erreur lors de l'inscription, veuillez réessayer.";
            }
        }
    }
}
?>

<?php if (!empty($erreurs)): ?>
<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <strong>Erreur !</strong><br>
    <?php foreach ($erreurs as $key => $msg): ?>
        <?= is_string($msg) ? $msg : "Veuillez remplir le champ manquant : $key" ?><br>
    <?php endforeach; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    <strong>Bien joué !</strong> Inscription réussie et vous êtes connecté !
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <meta http-equiv="refresh" content="1; url=index.php?page=home">
</div>
<?php endif; ?>

<form action="index.php?page=signup" method="post">
  <fieldset>
    <legend class="mt-4">Inscription</legend>

    <div>
      <label class="form-label mt-4">Votre Nom</label>
      <input name="nom" type="text" 
             class="form-control <?= isset($erreurs['nom']) ? 'is-invalid' : '' ?>" 
             value="<?= htmlspecialchars($nom) ?>">
    </div>

    <div>
      <label class="form-label mt-4">Votre Prénom</label>
      <input name="prenom" type="text" 
             class="form-control <?= isset($erreurs['prenom']) ? 'is-invalid' : '' ?>" 
             value="<?= htmlspecialchars($prenom) ?>">
    </div>

    <div>
      <label class="form-label mt-4">Email address</label>
      <input name="email" type="email" 
             class="form-control <?= isset($erreurs['email']) ? 'is-invalid' : '' ?>" 
             value="<?= htmlspecialchars($email) ?>"
             placeholder="Enter email">
    </div>

    <div>
      <label class="form-label mt-4">Password</label>
      <input name="password" type="password"
             class="form-control <?= isset($erreurs['password']) ? 'is-invalid' : '' ?>" 
             placeholder="Password" autocomplete="off">
    </div>

    <button name="transmettre" type="submit" class="btn btn-primary mt-3">Soumettre</button>
  </fieldset>
</form>
