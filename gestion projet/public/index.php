<?php
// events list
session_start();
require_once '../connexion/config.php';

// get upcoming events
$stmt = $pdo->prepare("SELECT * FROM events WHERE date_event >= CURDATE()");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Home</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <h1>Events</h1>

    <!-- Navbar -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>

    <div>
        <?php foreach ($events as $event): ?>
            <div>
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p><?= htmlspecialchars($event['date_event']) ?></p>
                <p><?= htmlspecialchars($event['location']) ?></p>
                <p><?= htmlspecialchars($event['price']) ?> DH</p>
                <p>Places restantes: <?= (int)$event['nbPlaces'] ?></p>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($event['nbPlaces'] > 0): ?>
                        <form method="POST" action="reserve.php">
                            <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
                            <button class="btn">Réserver</button>
                        </form>
                    <?php else: ?>
                        <span class="sold">SOLD OUT</span>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php">Login to Reserve</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>