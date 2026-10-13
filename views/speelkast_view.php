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
            <button type="submit" name="Toevoegen">Toevoegen</button>
            <button type="submit" name="Exporteren">Exporteren</button>
            <button type="submit" name="Avondlijst">Avondlijst</button>
            <button type="submit" name="logout">Uitloggen</button>
        </form>
    </div>
    <div>
        <h2>De speelkast</h2>
        <?php
        foreach ($items->item as $item) {
            echo $item->titel . "<br>";
        }
        ?>
    </div>
</body>

</html>