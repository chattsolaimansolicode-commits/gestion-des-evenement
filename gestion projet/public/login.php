<?php
session_start();
require_once '../connexion/config.php';

$error = "";

if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Connexion</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="email"    name="email"    placeholder="Email"         required>
        <input type="password" name="password" placeholder="Mot de passe"  required>
        <button type="submit" name="login">Se connecter</button>
    </form>

    <a href="signup.php">Pas encore de compte ? S'inscrire</a>

</body>
</html>