<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT events.title, events.date_event
    FROM reservations
    JOIN events ON reservations.event_id = events.id
    WHERE reservations.user_id = ?
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Mes réservations</h2>

    <?php if (empty($reservations)): ?>
        <p>Vous n'avez aucune réservation.</p>
    <?php else: ?>
        <?php foreach ($reservations as $r): ?>
            <p><?= htmlspecialchars($r['title']) ?> - <?= htmlspecialchars($r['date_event']) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="index.php">Retour aux événements</a>
    <a href="logout.php">Déconnexion</a>

</body>
</html>