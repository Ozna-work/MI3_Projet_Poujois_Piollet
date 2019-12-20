<?php

include_once('../Controller/Main.php');

session_start();

if (isset($_POST['idModifier'])) {
    echo $_POST['idModifier'];
}

if (isset($_POST['submit'], $_POST['nomStructure'], $_POST['rue'], $_POST['cp'], $_POST['ville'], $_POST['structure'], $_POST['nbDonaAct'], $_POST['check_list'])) {
    $_SESSION['nomStructure'] = $_POST['nomStructure'];
    $_SESSION['rue'] = $_POST['rue'];
    $_SESSION['cp'] = $_POST['cp'];
    $_SESSION['ville'] = $_POST['ville'];
    $_SESSION['structure'] = $_POST['structure'];
    $_SESSION['nbDonaAct'] = $_POST['nbDonaAct'];
    $_SESSION['check_list'] = $_POST['check_list'];

    inserer_nouvelles_structures($_POST['nomStructure'], $_POST['rue'], $_POST['cp'], $_POST['ville'], $_POST['structure'], $_POST['nbDonaAct'], $_POST['check_list']);
}

if (isset($_POST['modifier'])) {
    modifier_structure((int)$_POST['idModifier'],$_POST['nomStructure'], $_POST['rue'], $_POST['cp'], $_POST['ville'], $_POST['structure'], $_POST['nbDonaAct'], $_POST['check_list']);
}

if (isset($_POST['ajouterSecteur'], $_POST['nomSecteur'])) {
    $_SESSION['nomSecteur'] = $_POST['nomSecteur'];

    inserer_nouveaux_secteurs($_POST['nomSecteur']);
}

if (isset($_POST['idSuppression'])) {
    supprimer_structure($_POST['idSuppression']);
}

if (isset($_POST['idModifier'])) {
    $time = time() + 3600;
    $structAModifier = recuperer_structure_par_id($_POST['idModifier'])[0];
    $secteursAModifier = recuperer_idSecteurs_par_idStructure($_POST['idModifier']);
}

if (isset($_POST['clear'])) {
    $_POST = null;
    session_unset();
}

if (isset($_POST['idSecteurSupprime'])) {
    supprimer_secteur($_POST['idSecteurSupprime']);
}

if (isset($_POST['idSecteurAModifier'])){
    $_SESSION['nomSecteur'] = recuperer_libelle_secteur_par_id((int)$_POST['idSecteurAModifier']);
}

if(isset($_POST['modifierSecteur'], $_POST['idSecteurAModifier'])) {
    modifier_secteur((int)$_POST['idSecteurAModifier'], $_POST['nomSecteur']);
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
    <form method="post" action="">

        <table>
            <tr>
                <td>
                    <label for="nomStructure">Nom:</label>
                    <input required id="nomStructure" name="nomStructure" type="text" maxlength="100"
                           value="<?php
                           if (isset($structAModifier[1])) {
                               echo htmlspecialchars($structAModifier[1]);
                           } else if (isset($_SESSION['nomStructure'])) {
                               echo htmlspecialchars($_SESSION['nomStructure']);
                           } ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="rue">Rue:</label>
                    <input required id="rue" name="rue" type="text" maxlength="200"
                           value="<?php
                           if (isset($structAModifier[2])) {
                               echo htmlspecialchars($structAModifier[2]);
                           } else if (isset($_SESSION['rue'])) {
                               echo htmlspecialchars($_SESSION['rue']);
                           } ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cp">Code postal:</label>
                    <input required id="cp" name="cp" type="number" maxlength="5"
                           value="<?php
                           if (isset($structAModifier[3])) {
                               echo htmlspecialchars($structAModifier[3]);
                           } else if (isset($_SESSION['cp'])) {
                               echo htmlspecialchars($_SESSION['cp']);
                           } ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="ville">Ville:</label>
                    <input required id="ville" name="ville" type="text" maxlength="100"
                           value="<?php
                           if (isset($structAModifier[4])) {
                               echo htmlspecialchars($structAModifier[4]);
                           } else if (isset($_SESSION['ville'])) {
                               echo htmlspecialchars($_SESSION['ville']);
                           } ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="structure">Type de structure:</label>
                    <select name="structure" size="1" id="structure">
                        <option value="association" <?php
                        if (isset($structAModifier[5]) && $structAModifier[5] == 1) {
                            echo "selected";
                        } else if (isset($_SESSION['structure']) && $_SESSION['structure'] == "association") {
                            echo "selected";
                        } ?>
                        ">Association
                        <option value="entreprise" <?php
                        if (isset($structAModifier[5]) && $structAModifier[5] == 0) {
                            echo "selected";
                        } else if (isset($_SESSION['structure']) && $_SESSION['structure'] == "entreprise") {
                            echo "selected";
                        } ?>
                        ">Entreprise
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nbDonaAct">Nombre de donnateurs/actionnaires:</label></br>
                    <input required id="nbDonaAct" name="nbDonaAct" type="number" maxlength="11"
                           value="<?php if (isset($structAModifier[6]) || isset($structAModifier[7])) {
                               echo htmlspecialchars($structAModifier[6] + $structAModifier[7]);
                           } else if (isset($_SESSION['nbDonaAct'])) {
                               echo htmlspecialchars($_SESSION['nbDonaAct']);
                           } ?>"/>
                </td>
            </tr>

            <tr>
                <td>
                    <?php if (isset($secteursAModifier)) {
                        afficher_checkbox_secteurs($secteursAModifier);
                    } else if (isset($_SESSION['check_list'])) {
                        afficher_checkbox_secteurs($_SESSION['check_list']);
                    } else {
                        afficher_checkbox_secteurs(null);
                    } ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if (isset($_POST['idModifier'])) {
                        echo '<input hidden name="idModifier" value="'.$_POST['idModifier'].'">';
                        echo '<input type="submit" name="modifier" value="Modifier la structure">';
                    } else {
                        echo '<input type="submit" name="submit" value="Enregistrer la structure">';
                    } ?>

                </td>
            </tr>

        </table>
    </form>
    <form method="post">
        <input type="submit" name="clear" value="Clear">
    </form>
</div>

<div class="column">
    <?php afficher_structures(); ?>
</div>

<div class="column">
    <form class="" method="post">

        <table id="tableSecteur">
            <tr>
                <td>
                    <label for="nomSecteur">Nouveau secteur:</label>
                    <input id="nomSecteur" name="nomSecteur" type="text" maxlength="100"
                           value="<?php if (isset($_SESSION['nomSecteur'])) echo htmlspecialchars($_SESSION['nomSecteur']); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if (isset($_POST['idSecteurAModifier'])){
                        echo '<input hidden name="idSecteurAModifier" value="'.$_POST['idSecteurAModifier'].'">';
                        echo '<input type="submit" name="modifierSecteur" value="Modifier">';
                    }else{
                        echo '<input type="submit" name="ajouterSecteur" value="Ajouter">';
                    }
                    ?>

                </td>
            </tr>

            <?php afficher_secteurs(); ?>

        </table>
    </form>
</div>
</body>
</html>

