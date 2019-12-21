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
    modifier_structure((int)$_POST['idModifier'], $_POST['nomStructure'], $_POST['rue'], $_POST['cp'], $_POST['ville'], $_POST['structure'], $_POST['nbDonaAct'], $_POST['check_list']);
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

if (isset($_POST['idSecteurAModifier'])) {
    $_SESSION['nomSecteur'] = recuperer_libelle_secteur_par_id((int)$_POST['idSecteurAModifier']);
}

if (isset($_POST['modifierSecteur'], $_POST['idSecteurAModifier'])) {
    modifier_secteur((int)$_POST['idSecteurAModifier'], $_POST['nomSecteur']);
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sites des structures et secteurs</title>

    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/Main.css">
    <link rel="stylesheet" href="../src/js/jquery.mCustomScrollbar.concat.min.js">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
            integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ"
            crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
            integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY"
            crossorigin="anonymous"></script>

    <?php require_once('../Controller/Main.php'); ?>

</head>

<body>

<div class="wrapper">
    <nav id="sidebar1">
        <div id="dismiss1">
            <i class="fas fa-arrow-left"></i>
        </div>

        <div class="sidebar-header">
            <h3>Structures</h3>
        </div>

        <form id="formLeft" method="post" action="">

            <div class="form-group">
                <label for="nomStructure">Nom:</label>
                <input required id="nomStructure" name="nomStructure" type="text" maxlength="100"
                       value="<?php
                       if (isset($structAModifier[1])) {
                           echo htmlspecialchars($structAModifier[1]);
                       } else if (isset($_SESSION['nomStructure'])) {
                           echo htmlspecialchars($_SESSION['nomStructure']);
                       } ?>"/>
            </div>

            <div class="form-group">

                <label for="rue">Rue:</label>
                <input required id="rue" name="rue" type="text" maxlength="200"
                       value="<?php
                       if (isset($structAModifier[2])) {
                           echo htmlspecialchars($structAModifier[2]);
                       } else if (isset($_SESSION['rue'])) {
                           echo htmlspecialchars($_SESSION['rue']);
                       } ?>"/>
            </div>

            <div class="form-group">

                <label for="cp">Code postal:</label>
                <input required id="cp" name="cp" type="number" maxlength="5"
                       value="<?php
                       if (isset($structAModifier[3])) {
                           echo htmlspecialchars($structAModifier[3]);
                       } else if (isset($_SESSION['cp'])) {
                           echo htmlspecialchars($_SESSION['cp']);
                       } ?>"/>
            </div>

            <div class="form-group">

                <label for="ville">Ville:</label>
                <input required id="ville" name="ville" type="text" maxlength="100"
                       value="<?php
                       if (isset($structAModifier[4])) {
                           echo htmlspecialchars($structAModifier[4]);
                       } else if (isset($_SESSION['ville'])) {
                           echo htmlspecialchars($_SESSION['ville']);
                       } ?>"/>
            </div>

            <div class="form-group">

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
            </div>

            <label for="nbDonaAct">Nombre de donnateurs/actionnaires:</label></br>
            <input required id="nbDonaAct" name="nbDonaAct" type="number" maxlength="11"
                   value="<?php if (isset($structAModifier[6]) || isset($structAModifier[7])) {
                       echo htmlspecialchars($structAModifier[6] + $structAModifier[7]);
                   } else if (isset($_SESSION['nbDonaAct'])) {
                       echo htmlspecialchars($_SESSION['nbDonaAct']);
                   } ?>"/>

            <?php if (isset($secteursAModifier)) {
                afficher_checkbox_secteurs($secteursAModifier);
            } else if (isset($_SESSION['check_list'])) {
                afficher_checkbox_secteurs($_SESSION['check_list']);
            } else {
                afficher_checkbox_secteurs(null);
            } ?>

            <?php if (isset($_POST['idModifier'])) {
                echo '<input hidden name="idModifier" value="' . $_POST['idModifier'] . '">';
                echo '<input class="btn btn-success" type="submit" name="modifier" value="Modifier la structure">';
            } else {
                echo '<input class="btn btn-success" type="submit" name="submit" value="Enregistrer la structure">';
            } ?>
        </form>
        <form id="formClear" method="post">
            <input class="btn btn-info" type="submit" name="clear" value="Clear">
        </form>
    </nav>

    <!-- Page Content  -->
    <div id="content">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button type="button" id="sidebarCollapse1" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                    <span>Structures</span>
                </button>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>

                <button type="button" id="sidebarCollapse2" class="btn btn-info">
                    <i class="fas fa-align-right"></i>
                    <span>Secteurs</span>
                </button>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>

            </div>
        </nav>

        <div id="divTable">
            <?php afficher_structures(); ?>
        </div>
    </div>

    <nav id="sidebar2">
        <div id="dismiss2">
            <i class="fas fa-arrow-left"></i>
        </div>

        <div class="sidebar-header">
            <h3>Secteurs</h3>
        </div>
        <form id="formRight" class="" method="post">


            <label for="nomSecteur">Nouveau secteur:</label>
            <input id="nomSecteur" name="nomSecteur" type="text" maxlength="100"
                   value="<?php if (isset($_SESSION['nomSecteur'])) echo htmlspecialchars($_SESSION['nomSecteur']); ?>"/>

            <?php
            if (isset($_POST['idSecteurAModifier'])) {
                echo '<input hidden name="idSecteurAModifier" value="' . $_POST['idSecteurAModifier'] . '">';
                echo '<input class="btn btn-success" type="submit" name="modifierSecteur" value="Modifier">';
            } else {
                echo '<input class="btn btn-success" type="submit" name="ajouterSecteur" value="Ajouter">';
            }
            ?>

            <?php afficher_secteurs(); ?>

        </form>
    </nav>
</div>

<div class="overlay"></div>

<script src="../src/js/jquery-3.4.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="../src/js/bootstrap/bootstrap.min.js"></script>
<script src="../src/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="../src/js/Main.js"></script>
</body>

</html>