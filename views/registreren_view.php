<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Registreren - Speelkast</title>
</head>

<body>
    <h1>Registreren</h1>

    <form method="POST">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Registreren</button>
    </form>

    <?php if ($error !== ""): ?>
        <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <p><a href="index.php">Terug naar inloggen</a></p>

</body>

</html>