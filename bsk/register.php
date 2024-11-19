<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $password]);
        echo "Rejestracja zakończona sukcesem!";
    } catch (Exception $e) {
        echo "Błąd: " . $e->getMessage();
    }
}
?>
<form method="POST">
    <input type="text" name="username" placeholder="Nazwa użytkownika" required>
    <input type="password" name="password" placeholder="Hasło" required>
    <button type="submit">Zarejestruj</button>
</form>