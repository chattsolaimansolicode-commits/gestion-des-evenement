<?php
session_start();
require_once '../connexion/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);

if ($id) {
    // delete reservations first to avoid foreign key error
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE event_id = ?");
    $stmt->execute([$id]);

    // then delete the event
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: dashboard.php");
exit();