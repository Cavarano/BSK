<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Pobranie danych użytkownika
$stmt = $pdo->prepare("SELECT username, password_hash FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Nie znaleziono użytkownika.";
    exit;
}

// Pobranie danych karty
$stmt = $pdo->prepare("SELECT card_token FROM credit_cards WHERE user_id = ?");
$stmt->execute([$user_id]);
$card = $stmt->fetch();

$card_number_masked = $card ? substr($card['card_token'], 0, 4) . str_repeat('*', 8) . substr($card['card_token'], -4) : "Brak karty";

// Obsługa edycji danych
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $new_card_number = $_POST['card_number'];

    // Aktualizacja użytkownika
    $stmt = $pdo->prepare("UPDATE users SET username = ?, password_hash = ? WHERE id = ?");
    $stmt->execute([$new_username, $new_password, $user_id]);

    // Aktualizacja karty
    if ($new_card_number) {
        $new_token = hash('sha256', $new_card_number . uniqid());
        $stmt = $pdo->prepare("REPLACE INTO credit_cards (user_id, card_token) VALUES (?, ?)");
        $stmt->execute([$user_id, $new_token]);
    }

    echo "Dane zostały zaktualizowane!";
    header("Refresh: 2");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil użytkownika</title>
</head>
<body>
<h1>Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h1>
<h2>Dane użytkownika:</h2>
<form method="POST">
    <label>
        Login:
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </label>
    <br>
    <label>
        Hasło:
        <input type="password" name="password" placeholder="Nowe hasło" required>
    </label>
    <br>
    <label>
        Numer karty:
        <input type="text" name="card_number" placeholder="Nowy numer karty">
    </label>
    <p>Obecny numer karty: <?php echo $card_number_masked; ?></p>
    <button type="submit">Zapisz zmiany</button>
</form>
<a href="logout.php">Wyloguj</a>
</body>
</html>