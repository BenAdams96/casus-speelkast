<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>XML exporteren</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>XML exporteren</h1>

    <a href="speelkast.php">Terug naar Speelkast</a>

    <p>Hieronder staan alle spellen uit de database in XML-formaat.</p>

    <button type="button" onclick="window.location.href='export.php?download=1'">
        Download XML
    </button>

    <h2>XML-overzicht</h2>

    <pre><?= htmlspecialchars($xml) ?></pre>

</body>

</html>