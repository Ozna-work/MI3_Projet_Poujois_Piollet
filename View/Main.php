<?php

    var_dump($_POST);

    session_start();
    if (isset($_POST['nomStructure'],$_POST['rue'],$_POST['cp'],$_POST['ville'],$_POST['structure'],$_POST['nbDonaAct'])) {
        $_SESSION['nomStructure'] = $_POST['nomStructure'];
        $_SESSION['rue'] = $_POST['rue'];
        $_SESSION['cp'] = $_POST['cp'];
        $_SESSION['ville'] = $_POST['ville'];
        $_SESSION['structure'] = $_POST['structure'];
        $_SESSION['nbDonaAct'] = $_POST['nbDonaAct'];
    }

    if(isset($_POST['nomSecteur'])) {
        $_SESSION['nomSecteur'] = $_POST['nomSecteur'];
    }
?>

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
                    <label for="nomStructure">Nom:</label>
                    <input required id="nomStructure" type="text" maxlength="100" value="<?php if(isset($_SESSION['nomStructure'])) echo htmlspecialchars($_SESSION['nomStructure']); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="rue">Rue:</label>
                    <input required id="rue" type="text" maxlength="200" value="<?php if(isset($_SESSION['rue'])) echo htmlspecialchars($_SESSION['rue']); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cp">Code postal:</label>
                    <input required id="cp" type="number" maxlength="5" value="<?php if(isset($_SESSION['cp'])) echo htmlspecialchars($_SESSION['cp']); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="ville">Ville:</label>
                    <input required id="ville" type="text" maxlength="100" value="<?php if(isset($_SESSION['ville'])) echo htmlspecialchars($_SESSION['ville']); ?>"/>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="structure">Type de structure:</label>
                    <select name="structure" size="1" id="structure" >
                        <option value="association" <?php if(isset($_SESSION['structure']) && $_SESSION['structure']=="association") echo "selected"; ?>">Association
                        <option value="entreprise" <?php if(isset($_SESSION['structure']) && $_SESSION['structure']=="entreprise") echo "selected"; ?>">Entreprise
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nbDonaAct">Nombre de donnateurs/actionnaires:</label></br>
                    <input required id="nbDonaAct" type="number" maxlength="11" value="<?php if(isset($_SESSION['nbDonaAct'])) echo htmlspecialchars($_SESSION['nbDonaAct']); ?>"/>
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
    <form class="" method="post">

        <table>
            <tr>
                <td>
                    <label for="nomSecteur">Nouveau secteur:</label>
                    <input id="nomSecteur" type="text" maxlength="100" value="<?php if(isset($_SESSION['nomSecteur'])) echo htmlspecialchars($_SESSION['nomSecteur']); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Ajouter">
                </td>
            </tr>

            <?php afficher_secteurs();?>

        </table>
    </form>
</div>
<?php var_dump($_SESSION);?>
</body>
</html>
