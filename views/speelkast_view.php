
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>De Speelkast</title>
    <link rel="stylesheet" href="css/style.css?v=7">
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

    <?php
    //splits basisspellen en uitbreidingen
    $basisspellen = [];
    $uitbreidingen = [];

    foreach ($items as $itemId => $item) {
        if ($item instanceof Expansion) {
            $uitbreidingen[$itemId] = $item;
        } else {
            $basisspellen[$itemId] = $item;
        }
    }

    //sorteer beide groepen alfabetisch
    uasort($basisspellen, function ($a, $b) {
        return strcasecmp($a->getTitel(), $b->getTitel());
    });

    uasort($uitbreidingen, function ($a, $b) {
        return strcasecmp($a->getTitel(), $b->getTitel());
    });

    //maak een nieuwe lijst met uitbreidingen onder hun basisspel
    $gesorteerdeItems = [];

    foreach ($basisspellen as $itemId => $basisspel) {

        //voeg eerst het basisspel toe
        $gesorteerdeItems[$itemId] = $basisspel;

        //zoek bijbehorende uitbreidingen
        foreach ($uitbreidingen as $uitbreidingId => $uitbreiding) {

            if (
                strcasecmp(
                    trim($basisspel->getTitel()),
                    trim($uitbreiding->getBasisspel())
                ) === 0
            ) {
                $gesorteerdeItems[$uitbreidingId] = $uitbreiding;

                //deze uitbreiding is al toegevoegd
                unset($uitbreidingen[$uitbreidingId]);
            }
        }
    }

    //uitbreidingen zonder basisspel komen onderaan
    foreach ($uitbreidingen as $itemId => $uitbreiding) {
        $gesorteerdeItems[$itemId] = $uitbreiding;
    }
    ?>

    <?php foreach ($gesorteerdeItems as $itemId => $item): ?>

        <?php
        //controleren of het basisspel aanwezig is
        $basisspelBestaat = false;

        if ($item instanceof Expansion) {
            foreach ($basisspellen as $basisspel) {
                if (
                    strcasecmp(
                        trim($basisspel->getTitel()),
                        trim($item->getBasisspel())
                    ) === 0
                ) {
                    $basisspelBestaat = true;
                    break;
                }
            }
        }
        ?>

        <div class="spel <?= $basisspelBestaat ? 'uitbreiding' : '' ?>">

            <h3><?= htmlspecialchars($item->getTitel()) ?></h3>

            <div class="spel-inhoud">

                <!-- Foto links -->
                <div class="spel-foto">
                    <?= $item->showImage() ?>
                </div>

                <!-- Spelinformatie in het midden -->
                <div class="spel-info">

                    <p>
                        <strong>Eigenaar:</strong>
                        <?= htmlspecialchars($item->getUsername()) ?>
                    </p>

                    <p>
                        <strong>Type:</strong>
                        <?= htmlspecialchars($item->getType()->value) ?>
                    </p>

                    <?php if ($item->getMinSpelers() !== null && $item->getMaxSpelers() !== null): ?>
                        <p>
                            <strong>Aantal spelers:</strong>
                            <?= $item->getMinSpelers() ?> tot <?= $item->getMaxSpelers() ?>
                        </p>
                    <?php else: ?>
                        <p><strong>Aantal spelers:</strong> onbekend</p>
                    <?php endif; ?>

                    <p>
                        <strong>Extra informatie:</strong>
                        <?= htmlspecialchars($item->omschrijving()) ?>
                    </p>

                    <!-- Waarschuwing bij ontbrekend basisspel -->
                    <?php if ($item instanceof Expansion && !$basisspelBestaat): ?>
                        <p style="color: red;">
                            Let op: het basisspel staat niet in de Speelkast.
                        </p>
                    <?php endif; ?>

                </div>

                <!-- Verwijderknop alleen voor de eigenaar -->
                <?php if ($item->getUsername() === $_SESSION["username"]): ?>
                    <div class="spel-acties">

                        <form method="POST"
                              onsubmit="return confirm('Weet je zeker dat je dit spel wilt verwijderen?');">

                            <input type="hidden" name="item_id" value="<?= $itemId ?>">

                            <button type="submit" name="verwijderen" class="verwijder-knop">
                                Uit de kast halen
                            </button>

                        </form>

                    </div>
                <?php endif; ?>

            </div>
        </div>

    <?php endforeach; ?>

</body>

</html>
