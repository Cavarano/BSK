<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Brak dostępu.");
}

$stmt = $pdo->prepare("SELECT card_token FROM credit_cards WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tokens as $token) {
    echo "Token karty: " . htmlspecialchars($token['card_token']) . "<br>";
}