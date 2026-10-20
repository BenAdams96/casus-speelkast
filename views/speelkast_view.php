<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Speelkast</title>
</head>

<body>
    <p style="text-align: left;">Ingelogd als: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></p>
    <div>
        <form method="POST">
            <button type="button" onclick="window.location.href='toevoegen.php'">Toevoegen</button>
            <button type="button" onclick="window.location.href='export.php'">Exporteren</button>
            <button type="button" onclick="window.location.href='avondlijst.php'"> Avondlijst </button>
            <button type="submit" name="logout">Uitloggen</button>
        </form>
    </div>

    <div>
        <h2>De speelkast</h2>

        <?php foreach ($items as $item): ?>
            <div style="margin-bottom: 20px;">

                <h3><?= htmlspecialchars($item->getTitel()) ?></h3>

                <div style="display: flex; align-items: center; gap: 20px;">
                    <div>
                        <?= $item->showImage() ?>
                    </div>

                    <div>
                        <p>Eigenaar: <?= htmlspecialchars($item->getUsername()) ?></p>
                        <p>Type: <?= htmlspecialchars($item->getType()->value) ?></p>
                        <p><?= htmlspecialchars($item->omschrijving()) ?></p>

                        <?php if ($item instanceof Expansion && $item->getBasisspel() === "Basisspel niet aanwezig"): ?>
                            <p style="color: red;">
                                Let op: het basisspel staat niet in de Speelkast.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>