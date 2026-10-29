
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>XML exporteren</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Speelkast exporteren</h1>

    <a href="speelkast.php">Terug naar Speelkast</a>

    <p>Exporteer de spellen als XML of download de volledige Speelkast inclusief foto's.</p>

    <button type="button" onclick="window.location.href='export.php?download=1'">
        Download XML
    </button>

    <button type="button" onclick="window.location.href='export.php?zip=1'">
        Download volledige Speelkast (ZIP)
    </button>

    <h2>XML-overzicht</h2>

    <pre><?= htmlspecialchars($xml, ENT_QUOTES, "UTF-8") ?></pre>

</body>

</html>
