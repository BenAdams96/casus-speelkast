
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>De Speelkast</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>De Speelkast</h1>

    <div class="topbar">
        <form method="POST">
            <button type="button" onclick="window.location.href='toevoegen.php'">Toevoegen</button>
            <button type="button" onclick="window.location.href='export.php'">Exporteren</button>
            <button type="button" onclick="window.location.href='avondlijst.php'">Avondlijst</button>
            <button type="submit" name="logout">Uitloggen</button>
        </form>

        <span>
            Ingelogd als: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong>
        </span>
    </div>

    <h2>Spellenoverzicht</h2>

    <?php foreach ($items as $item): ?>
        <div class="spel">

            <h3><?= htmlspecialchars($item->getTitel()) ?></h3>

            <div class="spel-inhoud">

                <div>
                    <?= $item->showImage() ?>
                </div>

                <div>
                    <p><strong>Eigenaar:</strong> <?= htmlspecialchars($item->getUsername()) ?></p>

                    <p><strong>Type:</strong> <?= htmlspecialchars($item->getType()->value) ?></p>

                    <?php if ($item->getMinSpelers() !== null && $item->getMaxSpelers() !== null): ?>
                        <p><strong>Aantal spelers:</strong> <?= $item->getMinSpelers() ?> tot <?= $item->getMaxSpelers() ?></p>
                    <?php else: ?>
                        <p><strong>Aantal spelers:</strong> onbekend</p>
                    <?php endif; ?>

                    <?php if ($item instanceof Expansion): ?>
                        <p><strong>Extra informatie:</strong> <?= htmlspecialchars($item->omschrijving()) ?></p>

                        <?php
                        //controleren of het basisspel in de speelkast staat
                        $basisspelBestaat = false;

                        foreach ($items as $anderItem) {
                            if (
                                $anderItem instanceof BoardGame &&
                                strcasecmp(trim($anderItem->getTitel()), trim($item->getBasisspel())) === 0
                            ) {
                                $basisspelBestaat = true;
                                break;
                            }
                        }
                        ?>

                        <?php if (!$basisspelBestaat): ?>
                            <p style="color: red;">
                                Let op: het basisspel staat niet in de Speelkast.
                            </p>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>

</body>

</html>
