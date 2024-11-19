<?php
session_start();

// Zniszczenie sesji
session_unset();
session_destroy();

// Przekierowanie na stronę logowania
header("Location: login.php");
exit;
?>