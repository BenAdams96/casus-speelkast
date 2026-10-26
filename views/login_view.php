
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen - Speelkast</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Inloggen</button>

        <?php if ($error !== ""): ?>
            <p style="color: red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </form>

    <p>Nog geen account? <a href="registreren.php">Registreer hier</a></p>

</body>
</html>
