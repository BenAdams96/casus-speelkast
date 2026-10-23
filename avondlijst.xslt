<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">

    <!-- AI generated, geen ervaring met XSLT -->

    <xsl:output method="html" encoding="UTF-8" indent="yes"/>
    <xsl:param name="validatie"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Speelkast Avondlijst</title>
                <link rel="stylesheet" href="css/style.css"/>
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
                        <th>Aantal spelers</th>
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

                            <!-- Aantal spelers -->
                            <td>
                                <xsl:choose>
                                    <xsl:when test="min_spelers and max_spelers">
                                        <xsl:value-of select="min_spelers"/>
                                        tot
                                        <xsl:value-of select="max_spelers"/>
                                    </xsl:when>

                                    <xsl:otherwise>
                                        Onbekend
                                    </xsl:otherwise>
                                </xsl:choose>
                            </td>

                            <!-- Extra informatie -->
                            <td>
                                <xsl:value-of select="omschrijving"/>

                                <!-- Waarschuwing ontbrekend basisspel -->
                                <xsl:if test="type = 'uitbreiding' and basisspel_bestaat = 'nee'">
                                    <p style="color: red;">
                                        Let op: het basisspel staat niet in de Speelkast.
                                    </p>
                                </xsl:if>
                            </td>
                        </tr>
                    </xsl:for-each>

                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
