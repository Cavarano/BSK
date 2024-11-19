<?php
session_start();
require 'db.php';

// Sprawdzenie, czy użytkownik jest już zalogowany
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Obsługa logowania
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Pobranie użytkownika z bazy danych
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Weryfikacja hasła
    if ($user && password_verify($password, $user['password_hash'])) {
        // Logowanie użytkownika i przekierowanie na stronę główną
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Nieprawidłowe dane logowania.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
</head>
<body>
<h1>Logowanie</h1>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="POST">
    <label>
        Login:
        <input type="text" name="username" placeholder="Nazwa użytkownika" required>
    </label>
    <br>
    <label>
        Hasło:
        <input type="password" name="password" placeholder="Hasło" required>
    </label>
    <br>
    <button type="submit">Zaloguj</button>
</form>
</body>
</html>
