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
    <form method="POST" enctype="multipart/form-data" novalidate>

        <label for="titel">Titel:</label>
        <input type="text" name="titel" id="titel" value="<?= htmlspecialchars($_POST["titel"] ?? "") ?>" required>
        <span id="titel_error" style="color: red;"></span>

        <br><br>
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <!-- cases() functie geeft alle mogelijke waarde van enum -->
            <!-- POST is om te zorgen dat als formulier ingevuld was het die waarde blijft behouden -->
            <?php foreach (ItemType::cases() as $type): ?>
                <option value="<?= $type->value ?>" <?= ($_POST["type"] ?? "") === $type->value ? "selected" : "" ?>>
                    <!-- ucfirst() functie maakt eerste character hoofdletter -->
                    <?= ucfirst($type->value) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label id="basisspelVeld_box" hidden>
            Basisspel:
            <input type="text" name="basisspel" id="basisspelVeld"
                value="<?= htmlspecialchars($_POST["basisspel"] ?? "") ?>">
            <span id="basisspelVeld_error" style="color: red;"></span>
        </label>

        <br><br>

        <label for="min_spelers">Minimaal aantal spelers:</label>
        <input type="number" name="min_spelers" id="min_spelers" min="1"
            value="<?= htmlspecialchars($_POST["min_spelers"] ?? "") ?>">
        <span id="min_spelers_error" style="color: red;"></span>

        <br><br>

        <label for="max_spelers">Maximaal aantal spelers:</label>
        <input type="number" name="max_spelers" id="max_spelers" min="1"
            value="<?= htmlspecialchars($_POST["max_spelers"] ?? "") ?>">
        <span id="max_spelers_error" style="color: red;"></span>

        <br><br>

        <label for="foto">Foto:</label>
        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/gif" required>
        <span id="foto_error" style="color: red;"></span>

        <br><br>

        <button type="submit" name="toevoegen">Toevoegen</button>
        <?php if ($error !== ""): ?>
            <p style="color: red;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

    </form>

    <script>
        function getElement(id) {
            return document.getElementById(id);
        }

        const type = getElement("type");
        const basisspelVeld_box = getElement("basisspelVeld_box");
        basisspelVeld_box.hidden = type.value !== "uitbreiding";

        const titel_field = getElement("titel");
        const basisspelVeld_field = getElement("basisspelVeld");
        const min_spelers_field = getElement("min_spelers");
        const max_spelers_field = getElement("max_spelers");

        const titel_error = getElement("titel_error");
        const basisspelVeld_error = getElement("basisspelVeld_error");
        const min_spelers_error = getElement("min_spelers_error");
        const max_spelers_error = getElement("max_spelers_error");

        //function() is hier een callback function
        //word pas aangeroepen na een bepaalde gebeurtenis ("change" dus hier)
        type.addEventListener("change", function () {
            basisspelVeld_box.hidden = type.value !== "uitbreiding";
            basisspelVeld_error.textContent = "";
        });

        titel_field.addEventListener("change", function () {
            titel_error.textContent = titel_field.value.trim() === "" ? "Vul een titel in." : "";
        })

        basisspelVeld_field.addEventListener("change", function () {
            basisspelVeld_error.textContent = basisspelVeld_field.value.trim() === "" ? "Vul een basisspel in." : "";
        })

        min_spelers_field.addEventListener("change", function () {
            min_spelers_error.textContent = min_spelers_field.value.trim() === "" ? "Vul min spelers in." : "";
            checkSpelers();
        });

        max_spelers_field.addEventListener("change", function () {
            checkSpelers();
        });

        function checkSpelers() {
            if (min_spelers_field.value !== "" && max_spelers_field.value !== "" &&
                Number(max_spelers_field.value) < Number(min_spelers_field.value)) {
                max_spelers_error.textContent = "Max spelers mag niet lager zijn dan min spelers.";
            } else {
                max_spelers_error.textContent = "";
            }
        }

        



    </script>
</body>



</html>