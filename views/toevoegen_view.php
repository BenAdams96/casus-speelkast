
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Spel toevoegen</title>
</head>

<body>
    <h1>Spel toevoegen</h1>

    <form method="POST" enctype="multipart/form-data">

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="bordspel">Bordspel</option>
            <option value="uitbreiding">Uitbreiding</option>
        </select>

        <br><br>

        <label for="titel">Titel:</label>
        <input type="text" name="titel" id="titel" required>

        <br><br>

        <label for="min_spelers">Minimaal aantal spelers:</label>
        <input type="number" name="min_spelers" id="min_spelers" min="1">

        <br><br>

        <label for="max_spelers">Maximaal aantal spelers:</label>
        <input type="number" name="max_spelers" id="max_spelers" min="1">

        <br><br>

        <label for="basisspel">Basisspel (alleen bij uitbreiding):</label>
        <input type="text" name="basisspel" id="basisspel">

        <br><br>

        <label for="foto">Foto:</label>
        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/gif" required>

        <br><br>

        <button type="submit" name="toevoegen">Toevoegen</button>

    </form>

    <a href="speelkast.php">Terug naar Speelkast</a>
</body>

</html>
