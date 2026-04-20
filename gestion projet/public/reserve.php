<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$event_id = (int)$_POST['event_id'];

// check places
$stmt = $pdo->prepare("SELECT nbPlaces FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: index.php");
    exit();
}

if ($event['nbPlaces'] > 0) {

    // avoid double reservation
    $check = $pdo->prepare("SELECT id FROM reservations WHERE user_id = ? AND event_id = ?");
    $check->execute([$user_id, $event_id]);

    if ($check->fetch()) {
        header("Location: dashboard.php?error=already_reserved");
        exit();
    }

    $pdo->prepare("INSERT INTO reservations (user_id, event_id) VALUES (?, ?)")
        ->execute([$user_id, $event_id]);

    $pdo->prepare("UPDATE events SET nbPlaces = nbPlaces - 1 WHERE id = ?")
        ->execute([$event_id]);

    header("Location: dashboard.php");
    exit();

} else {
    header("Location: index.php?error=sold_out");
    exit();
}