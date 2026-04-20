<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT events.*, COUNT(reservations.id) AS total_res
    FROM events
    LEFT JOIN reservations ON events.id = reservations.event_id
    GROUP BY events.id
    ORDER BY date_event DESC
");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h1>Admin Dashboard</h1>
    <a href="add_event.php">+ Add Event</a> |
    <a href="logout.php">Logout</a>
    <hr>

    <?php if (empty($events)): ?>
        <p>Aucun événement trouvé.</p>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <div class="card">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p>📅 <?= htmlspecialchars($event['date_event']) ?></p>
                <p>📍 <?= htmlspecialchars($event['location']) ?></p>
                <p>💰 <?= htmlspecialchars($event['price']) ?> DH</p>
                <p>📊 Reservations: <?= (int)$event['total_res'] ?></p>
                <p>🪑 Places: <?= (int)$event['nbPlaces'] ?></p>

                <?php if ($event['nbPlaces'] == 0): ?>
                    <span style="color:red; font-weight:bold;">SOLD OUT 🔴</span>
                <?php endif; ?>

                <a href="edit_event.php?id=<?= (int)$event['id'] ?>">✏️ Edit</a> |
                <a href="delete_event.php?id=<?= (int)$event['id'] ?>" onclick="return confirm('Supprimer cet événement ?')">🗑️ Delete</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>