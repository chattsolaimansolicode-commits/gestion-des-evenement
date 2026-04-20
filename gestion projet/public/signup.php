<?php
session_start();
require_once '../connexion/config.php';

$errors = [];
$name = '';
$email = '';

if (isset($_POST["submit"])) {
    $name  = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password          = $_POST["password"];
    $password_confirmer = $_POST["confirme"];

    // validation
    if (strlen($name) < 3) {
        $errors[] = "Nom invalide (minimum 3 caractères)";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }
    if (strlen($password) < 8) {
        $errors[] = "Mot de passe minimum 8 caractères";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Mot de passe doit contenir une lettre majuscule";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Mot de passe doit contenir un chiffre";
    }
    if ($password !== $password_confirmer) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    // check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Email déjà utilisé";
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <<link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Créer un compte</h2>

    <!-- show errors -->
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="signup.php">
        <input type="text"     name="name"     placeholder="Nom"               value="<?= $name ?>">
        <input type="email"    name="email"    placeholder="Email"             value="<?= $email ?>">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="password" name="confirme" placeholder="Confirmer mot de passe">
        <button type="submit" name="submit">S'inscrire</button>
    </form>

    <a href="login.php">Déjà un compte ? Se connecter</a>

</body>
</html>