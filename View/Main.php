<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Test</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/Main.css">
    <?php require_once('../Controller/Main.php'); ?>
</head>
<body>

<div class="column">
    <form class="" method="post">

        <table>
            <tr>
                <td>
                    <label for="nom">Nom:</label>
                    <input id="nom" type="text" maxlength="100"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="rue">Rue:</label>
                    <input id="rue" type="text" maxlength="200"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cp">Code postal:</label>
                    <input id="cp" type="number" maxlength="5"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="ville">Ville:</label>
                    <input id="ville" type="text" maxlength="100"/>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="structure">Type de structure:</label>
                    <select name="structure" size="1" id="structure">
                        <option>Association</option>
                        <option>Entreprise</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nbDonaAct">Nombre de donnateurs/actionnaires:</label></br>
                    <input id="nbDonaAct" type="number" maxlength="11"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Enregistrer la structure">
                </td>
            </tr>

        </table>
    </form>
</div>

<div class="column">
    <?php afficher_structures(); ?>
</div>

<div class="column">
    AAAAAAAAAAAH
</div>

</body>
</html>
