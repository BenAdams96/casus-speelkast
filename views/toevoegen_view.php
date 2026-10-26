<?php
require_once "models/enums/ItemType.php";
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Spel toevoegen</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Spel toevoegen</h1>

    <a href="speelkast.php">Terug naar Speelkast</a>

    <br><br>

    <form method="POST" enctype="multipart/form-data" novalidate>

        <label for="titel">Titel:</label>
        <div class="input-row">
            <input type="text" name="titel" id="titel"
                value="<?= htmlspecialchars($_POST["titel"] ?? "") ?>" required>
            <span id="titel_error" style="color: red;"></span>
        </div>

        <br><br>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <?php foreach (ItemType::cases() as $type): ?>
                <option value="<?= $type->value ?>"
                    <?= ($_POST["type"] ?? "") === $type->value ? "selected" : "" ?>>
                    <?= ucfirst($type->value) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Basisspel kiezen bij uitbreiding -->
        <div id="basisspelVeld_box" hidden>
            <label for="basisspelVeld">Basisspel:</label>

            <div class="input-row">
                <select name="basisspel" id="basisspelVeld">
                    <option value="">Kies een basisspel</option>

                    <?php foreach ($items as $item): ?>
                        <?php if ($item instanceof BoardGame): ?>
                            <option value="<?= htmlspecialchars($item->getTitel()) ?>"
                                <?= ($_POST["basisspel"] ?? "") === $item->getTitel() ? "selected" : "" ?>>
                                <?= htmlspecialchars($item->getTitel()) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <option value="Basisspel niet aanwezig"
                        <?= ($_POST["basisspel"] ?? "") === "Basisspel niet aanwezig" ? "selected" : "" ?>>
                        Basisspel niet aanwezig
                    </option>
                </select>

                <span id="basisspelVeld_error" style="color: red;"></span>
            </div>
        </div>

        <!-- Zelf een basisspel invoeren -->
        <div id="nieuwBasisspel_box" hidden>
            <label for="nieuwBasisspel">Naam basisspel:</label>

            <div class="input-row">
                <input type="text" name="nieuw_basisspel" id="nieuwBasisspel"
                    value="<?= htmlspecialchars($_POST["nieuw_basisspel"] ?? "") ?>"
                    placeholder="Naam van het basisspel">

                <span id="nieuwBasisspel_error" style="color: red;"></span>
            </div>
        </div>

        <br><br>

        <label for="min_spelers">Minimaal aantal spelers:</label>
        <div class="input-row">
            <input type="number" name="min_spelers" id="min_spelers" min="1"
                value="<?= htmlspecialchars($_POST["min_spelers"] ?? "") ?>">
            <span id="min_spelers_error" style="color: red;"></span>
        </div>

        <br><br>

        <label for="max_spelers">Maximaal aantal spelers:</label>
        <div class="input-row">
            <input type="number" name="max_spelers" id="max_spelers" min="1"
                value="<?= htmlspecialchars($_POST["max_spelers"] ?? "") ?>">
            <span id="max_spelers_error" style="color: red;"></span>
        </div>

        <br><br>

        <label for="foto">Foto:</label>
        <div class="input-row">
            <input type="file" name="foto" id="foto"
                accept="image/jpeg,image/png,image/gif" required>
            <span id="foto_error" style="color: red;"></span>
        </div>

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
        const basisspelVeld_field = getElement("basisspelVeld");
        const nieuwBasisspel_box = getElement("nieuwBasisspel_box");
        const nieuwBasisspel_field = getElement("nieuwBasisspel");

        const titel_field = getElement("titel");
        const min_spelers_field = getElement("min_spelers");
        const max_spelers_field = getElement("max_spelers");
        const foto_field = getElement("foto");

        const titel_error = getElement("titel_error");
        const basisspelVeld_error = getElement("basisspelVeld_error");
        const nieuwBasisspel_error = getElement("nieuwBasisspel_error");
        const min_spelers_error = getElement("min_spelers_error");
        const max_spelers_error = getElement("max_spelers_error");
        const foto_error = getElement("foto_error");

        //basisspelvelden tonen of verbergen
        function updateBasisspel() {
            const uitbreiding = type.value === "uitbreiding";

            basisspelVeld_box.hidden = !uitbreiding;

            nieuwBasisspel_box.hidden = !uitbreiding ||
                basisspelVeld_field.value !== "Basisspel niet aanwezig";
        }

        type.addEventListener("change", updateBasisspel);
        basisspelVeld_field.addEventListener("change", updateBasisspel);

        //bij openen direct de juiste velden tonen
        updateBasisspel();

        //validatie titel
        titel_field.addEventListener("change", checkTitel);

        function checkTitel() {
            titel_error.textContent =
                titel_field.value.trim() === "" ? "Vul een titel in." : "";
        }

        //validatie basisspel
        basisspelVeld_field.addEventListener("change", checkBasisspel);

        function checkBasisspel() {
            basisspelVeld_error.textContent =
                type.value === "uitbreiding" && basisspelVeld_field.value.trim() === "" ?
                "Kies een basisspel." : "";
        }

        //validatie zelf ingevuld basisspel
        nieuwBasisspel_field.addEventListener("change", checkNieuwBasisspel);

        function checkNieuwBasisspel() {
            nieuwBasisspel_error.textContent =
                type.value === "uitbreiding" &&
                basisspelVeld_field.value === "Basisspel niet aanwezig" &&
                nieuwBasisspel_field.value.trim() === "" ?
                "Vul de naam van het basisspel in." : "";
        }

        //validatie spelers
        min_spelers_field.addEventListener("change", checkSpelers);
        max_spelers_field.addEventListener("change", checkSpelers);

        function checkSpelers() {
            min_spelers_error.textContent = "";
            max_spelers_error.textContent = "";

            if (min_spelers_field.validity.badInput ||
                (min_spelers_field.value !== "" && !/^[1-9][0-9]*$/.test(min_spelers_field.value))) {
                min_spelers_error.textContent = "Ongeldig minimaal aantal spelers.";
            } else if (type.value === "bordspel" && min_spelers_field.value === "") {
                min_spelers_error.textContent = "Vul min spelers in.";
            }

            if (max_spelers_field.validity.badInput ||
                (max_spelers_field.value !== "" && !/^[1-9][0-9]*$/.test(max_spelers_field.value))) {
                max_spelers_error.textContent = "Ongeldig maximaal aantal spelers.";
            } else if (type.value === "bordspel" && max_spelers_field.value === "") {
                max_spelers_error.textContent = "Vul max spelers in.";
            }

            if (min_spelers_error.textContent === "" && max_spelers_error.textContent === "" &&
                min_spelers_field.value !== "" &&
                max_spelers_field.value !== "" &&
                Number(max_spelers_field.value) < Number(min_spelers_field.value)) {

                max_spelers_error.textContent =
                    "Max spelers mag niet lager zijn dan min spelers.";
            }
        }

        //validatie foto
        foto_field.addEventListener("change", checkFoto);

        function checkFoto() {
            const foto = foto_field.files[0];
            foto_error.textContent = "";

            if (!foto) {
                foto_error.textContent = "Kies een foto.";
            } else if (foto.size > 2 * 1024 * 1024) {
                foto_error.textContent = "Foto is te groot.";
            } else if (!["image/jpeg", "image/png", "image/gif"].includes(foto.type)) {
                foto_error.textContent = "Ongeldig afbeeldingstype.";
            }
        }

        //alle velden ook controleren bij verzenden
        document.querySelector("form").addEventListener("submit", function (event) {
            checkTitel();
            checkBasisspel();
            checkNieuwBasisspel();
            checkSpelers();
            checkFoto();

            if (titel_error.textContent !== "" || basisspelVeld_error.textContent !== "" ||
                nieuwBasisspel_error.textContent !== "" || min_spelers_error.textContent !== "" ||
                max_spelers_error.textContent !== "" || foto_error.textContent !== "") {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>
