<?php
require_once "models/enums/ItemType.php";
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Spel toevoegen</title>
</head>

<body>
    <h1>Spel toevoegen</h1>
    <a href="speelkast.php">Terug naar Speelkast</a>

    <br><br>
    <!-- multipart/form-data nodig omdat we ook foto uploaden -->
    <form method="POST" enctype="multipart/form-data">

        <label for="titel">Titel:</label>
        <input type="text" name="titel" id="titel" required>

        <br><br>
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <!-- cases() functie geeft alle mogelijke waarde van enum -->
            <?php foreach (ItemType::cases() as $type): ?>
                <option value="<?= $type->value ?>">
                    <!-- ucfirst() functie maakt eerste character hoofdletter -->
                    <?= ucfirst($type->value) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label id="basisspelVeld" hidden>
            Basisspel:
            <input type="text" name="basisspel" id="basisspel">
        </label>

        <br><br>

        <label for="min_spelers">Minimaal aantal spelers:</label>
        <input type="number" name="min_spelers" id="min_spelers" min="1">

        <br><br>

        <label for="max_spelers">Maximaal aantal spelers:</label>
        <input type="number" name="max_spelers" id="max_spelers" min="1">

        <br><br>

        <label for="foto">Foto:</label>
        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/gif" required>

        <br><br>

        <button type="submit" name="toevoegen">Toevoegen</button>

    </form>

    <script>
        function getElement(id) {
            return document.getElementById(id);
        }

        const type = getElement("type");
        const uitbreidingveld = getElement("basisspelVeld");
        //function() is hier een callback function
        //word pas aangeroepen na een bepaalde gebeurtenis ("change" dus hier)
        type.addEventListener("change", function () {
            uitbreidingveld.hidden = type.value !== "uitbreiding";
        });

    </script>
</body>



</html>