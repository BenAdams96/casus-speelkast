<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Speelkast</title>
</head>

<body>
    je bent in de speelkast
    <div>
        <form method="POST">
            <button type="button" onclick="window.location.href='toevoegen.php'">Toevoegen</button>
            <button type="submit" name="Exporteren">Exporteren</button>
            <button type="submit" name="Avondlijst">Avondlijst</button>
            <button type="submit" name="logout">Uitloggen</button>
        </form>
    </div>
    <div>
        <h2>De speelkast</h2> <!-- OOP geimplementeerd dus nu andere format om lijst te laten zien -->
        <?php foreach ($items as $item): ?>
            <h3><?= htmlspecialchars($item->getTitel()) ?></h3>
            <p>Eigenaar: <?= htmlspecialchars($item->getUsername()) ?></p>
            <p>Type: <?= htmlspecialchars($item->getType()->value) ?></p>
            <p><?= htmlspecialchars($item->omschrijving()) ?></p>
            <!--$item->showImage() image nog toevoegen-->
        <?php endforeach; ?>
    </div>
</body>

</html>