<?xml version="1.0" encoding="UTF-8"?>
<!-- AI generated, geen ervaring met XSLT -->
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <xsl:output method="html" encoding="UTF-8" indent="yes"/>
    <xsl:param name="validatie"/>
    <xsl:template match="/">

        <html>
            <head>
                <title>Speelkast Avondlijst</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 30px;
                    }

                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    th, td {
                        border: 1px solid #ccc;
                        padding: 10px;
                        text-align: left;
                    }

                    th {
                        background-color: #eee;
                    }

                    img {
                        width: 100px;
                    }
                </style>
            </head>

            <body>
                <h1>Speelkast - Avondlijst</h1>
                <a href="speelkast.php">Terug naar Speelkast</a>
                <p>
                    <xsl:value-of select="$validatie"/>
                </p>
                <table>
                    <tr>
                        <th>Foto</th>
                        <th>Titel</th>
                        <th>Type</th>
                        <th>Eigenaar</th>
                        <th>Extra informatie</th>
                    </tr>

                    <!-- Alle items uit XML doorlopen -->
                    <xsl:for-each select="speelkast/item">
                        <tr>
                            <td>
                                <img src="{foto}" alt="Spelfoto"/>
                            </td>

                            <td>
                                <xsl:value-of select="titel"/>
                            </td>

                            <td>
                                <xsl:value-of select="type"/>
                            </td>

                            <td>
                                <xsl:value-of select="eigenaar"/>
                            </td>

                            <td>
                                <!-- Informatie voor bordspellen -->
                                <xsl:if test="type = 'bordspel'">
                                    <xsl:if test="min_spelers and max_spelers">
                                        <xsl:value-of select="min_spelers"/>
                                        -
                                        <xsl:value-of select="max_spelers"/>
                                        spelers
                                    </xsl:if>
                                </xsl:if>

                                <!-- Informatie voor uitbreidingen -->
                                <xsl:if test="type = 'uitbreiding'">
                                    Basisspel:
                                    <xsl:value-of select="basisspel"/>
                                </xsl:if>
                            </td>
                        </tr>
                    </xsl:for-each>

                </table>
            </body>
        </html>

    </xsl:template>
</xsl:stylesheet>
