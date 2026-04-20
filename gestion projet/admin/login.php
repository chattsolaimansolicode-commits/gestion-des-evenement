<?php
session_start();
require_once '../connexion/config.php';

$error = "";

if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // admin fixed (TP simple)
    if ($email === "admin@gmail.com" && $password === "123456") {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Accès refusé";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
     <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="email"    name="email"    placeholder="Email"    required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

</body>
</html>