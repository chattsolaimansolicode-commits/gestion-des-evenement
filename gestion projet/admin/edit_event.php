<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: dashboard.php");
    exit();
}

// get event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: dashboard.php");
    exit();
}

// update
if (isset($_POST['update'])) {
    $title    = htmlspecialchars(trim($_POST['title']));
    $date     = $_POST['date'];
    $places   = (int)$_POST['places'];
    $price    = (float)$_POST['price'];
    $location = htmlspecialchars(trim($_POST['location']));

    $stmt = $pdo->prepare("
        UPDATE events
        SET title = ?, date_event = ?, nbPlaces = ?, price = ?, location = ?
        WHERE id = ?
    ");
    $stmt->execute([$title, $date, $places, $price, $location, $id]);
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Edit Event</h2>

    <a href="dashboard.php">← Back to Dashboard</a>
    <hr>

    <form method="POST" action="edit_event.php?id=<?= $id ?>">
        <input type="text"   name="title"    value="<?= htmlspecialchars($event['title']) ?>"    required>
        <input type="date"   name="date"     value="<?= $event['date_event'] ?>"                 required>
        <input type="number" name="places"   value="<?= (int)$event['nbPlaces'] ?>"              required>
        <input type="number" name="price"    value="<?= $event['price'] ?>"        step="0.01">
        <input type="text"   name="location" value="<?= htmlspecialchars($event['location']) ?>">
        <button type="submit" name="update">Update</button>
    </form>

</body>
</html>