<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if (isset($_POST['add'])) {
    $title    = htmlspecialchars(trim($_POST['title']));
    $date     = $_POST['date'];
    $places   = (int)$_POST['places'];
    $price    = (float)$_POST['price'];
    $location = htmlspecialchars(trim($_POST['location']));

    if ($title && $date && $places) {
        $stmt = $pdo->prepare("
            INSERT INTO events (title, date_event, nbPlaces, price, location)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$title, $date, $places, $price, $location]);
        $msg = "Événement ajouté ✅";
    } else {
        $msg = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h2>Add Event</h2>

    <a href="dashboard.php">← Back to Dashboard</a>
    <hr>

    <?php if ($msg): ?>
        <p style="color:green;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST" action="add_event.php">
        <input type="text"   name="title"    placeholder="Title"    required>
        <input type="date"   name="date"                            required>
        <input type="number" name="places"   placeholder="Places"   required>
        <input type="number" name="price"    placeholder="Price"    step="0.01">
        <input type="text"   name="location" placeholder="Location">
        <button type="submit" name="add">Add</button>
    </form>

</body>
</html>