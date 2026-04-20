<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$q = htmlspecialchars(trim($_GET['q'] ?? ''));

$stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ?");
$stmt->execute(["%$q%"]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Events</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Search Events</h2>

    <a href="dashboard.php">← Back to Dashboard</a>
    <hr>

    <form method="GET" action="search.php">
        <input type="text" name="q" placeholder="Search..." value="<?= $q ?>">
        <button type="submit">Search</button>
    </form>
    <hr>

    <?php if (empty($events)): ?>
        <p>Aucun événement trouvé.</p>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <div>
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p>📅 <?= htmlspecialchars($event['date_event']) ?></p>
                <p>📍 <?= htmlspecialchars($event['location']) ?></p>
                <p>💰 <?= htmlspecialchars($event['price']) ?> DH</p>
                <p>🪑 Places: <?= (int)$event['nbPlaces'] ?></p>
                <a href="edit_event.php?id=<?= (int)$event['id'] ?>">✏️ Edit</a> |
                <a href="delete_event.php?id=<?= (int)$event['id'] ?>" onclick="return confirm('Supprimer cet événement ?')">🗑️ Delete</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>