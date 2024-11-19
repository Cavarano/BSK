<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Nie jesteś zalogowany.");
}

function tokenizeCard($cardNumber) {
    return hash('sha256', $cardNumber . uniqid());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardNumber = $_POST['card_number'];
    $token = tokenizeCard($cardNumber);

    $stmt = $pdo->prepare("INSERT INTO credit_cards (user_id, card_token) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $token]);

    echo "Karta została dodana!";
}
?>
<form method="POST">
    <input type="text" name="card_number" placeholder="Numer karty" required>
    <button type="submit">Dodaj kartę</button>
</form>
