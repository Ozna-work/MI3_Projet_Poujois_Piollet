<?php

require_once("../Model/Structure.php");
require_once("../Model/Association.php");
require_once("../Model/Entreprise.php");
require_once('../Model/db/PDO.php');

function afficher_structures()
{
    $structures = getAllStructures();
    echo "<table class=\"table table-hover\" id='listeStructures'>";

    echo "<tr>";
    echo "<th scope=\"col\"> TYPE</th>";
    echo "<th scope=\"col\"> NOM </th>";
    echo "<th scope=\"col\"> RUE </th>";
    echo "<th scope=\"col\"> CODE POSTALE </th>";
    echo "<th scope=\"col\"> VILLE </th>";
    echo "<th scope=\"col\"> NB ACTIONNAIRES/DONNATEURS </th>";
    echo "<th scope=\"col\"> SECTEURS </th>";
    echo "<th scope=\"col\"> SUPPRESSION </th>";
    echo "<th scope=\"col\"> MODIFIER</th>";
    echo "</tr>";

    foreach ($structures as $structure) {
        echo "<tr>";

        //Si ESTASSO vaut 0, alors c'est une entreprise
        if ($structure[5] == 0) {
            echo "<td> Entreprise</td>";
        } else {
            echo "<td> Association</td>";
        }

        //Infos générique pour chaque structure
        for ($i = 1; $i < 5; $i++) {
            echo "<td>" . $structure[$i] . "</td>";
        }

        //Si le nombre de donateurs n'est pas null, on affiche ce nombre, sinon on affiche le nombre d'actionnaires
        if ($structure[6]) {
            echo "<td>" . $structure[6] . "</td>";
        } else {
            echo "<td>" . $structure[7] . "</td>";
        }

        //Affichage des secteurs
        echo "<td>";
        $idSecteurs = getSecteursIdByStructureId($structure[0]);
        foreach ($idSecteurs as $idSecteur) {
            $libelleSecteur = getSecteurLibelleById($idSecteur[0])[0][0];
            echo $libelleSecteur . '<br>';
        }

        echo "</td>";

        echo '<td><form method="post" action="">';
        echo "<input hidden name='idModifier' value='" . $structure[0] . "'/>";
        echo '<input class="btn btn-secondary" type="submit" value="Modifier"/>';
        echo '</form> </td>';

        echo '<td><form method="post" action="">';
        echo "<input hidden name='idSuppression' value='" . $structure[0] . "'/>";
        echo '<input  class="btn btn-danger" type="submit" value="Supprimer"/>';
        echo '</form> </td>';


        echo "</tr>";
    }

    echo "</table>";
}

function afficher_secteurs()
{
    $secteurs = getAllSecteurs();

    echo '<table>';
    foreach ($secteurs as $secteur) {

//        echo "<tr>";
//        echo "<td>" . $secteur[1] . "</td>";
//        echo "<td> <input class='modifieSecteur' name='idSecteurAModifier' type='submit' value='" . $secteur[0] . "'/>";
//        echo "<td> <input class='deleteSecteur' name='idSecteurSupprime' type='submit' value='" . $secteur[0] . "'/>";
//        echo "</tr>";

        echo '<div class="form-group">';
//        echo '  <label class="col-form-label">'. $secteur[1] .'</label>';
//        echo "<input class='modifieSecteur' name='idSecteurAModifier' type='submit' value='" . $secteur[0] . "'/>";
//        echo "<input class='deleteSecteur' name='idSecteurSupprime' type='submit' value='" . $secteur[0] . "'/>";
        echo "<tr>";
        echo "<td>" . $secteur[1] . "</td>";
        echo "<td> <input class='modifieSecteur' name='idSecteurAModifier' type='submit' value='" . $secteur[0] . "'/>";
        echo "<td> <input class='deleteSecteur' name='idSecteurSupprime' type='submit' value='" . $secteur[0] . "'/>";
        echo "</tr>";
        echo '</div>';

    }
    echo '</table>';


}

function inserer_nouveaux_secteurs(string $libelle)
{

    $secteurs = getAllSecteurs();
    $secteurPresent = false;
    $i = 0;

    //Tant qu'on a pas parcouru tous les secteurs ET que le secteur n'est pas présent
    while ($i < sizeof($secteurs) && !$secteurPresent) {
        //Prends la valeur true si le resultat est différent du libelle cherché
        $secteurPresent = ($secteurs[$i][1] == $libelle);
        $i++;
    }

    if (!$secteurPresent) {
        insertSecteur($libelle);
    }
}

function inserer_nouvelles_structures(string $nom, string $rue, string $cp, string $ville, string $structure, string $nbDonAct, $checkbox_list)
{
    if ($checkbox_list) {
        $i = 0;
        $structures = getAllStructures();
        $structurePresent = false;

        if ($structure == "Association") {
            $estAsso = 1;
            $typeContributeur = 6;  //Permet de savoir sur quel indice comparer les donnateurs/actionnaires
        } else {
            $estAsso = 0;
            $typeContributeur = 7;  //Permet de savoir sur quel indice comparer les donnateurs/actionnaires
        }

        while ($i < sizeof($structures) && !$structurePresent) {
            $item = $structures[$i];
            $structurePresent = ($item[1] == $nom && $item[2] == $rue && $item[3] == $cp && $item[4] == $ville && $item[5] == $estAsso && $item[$typeContributeur] == $nbDonAct);
            $i++;
        }

        if (!$structurePresent) {
            $idStructure = insertStructure($nom, $rue, $cp, $ville, $estAsso, $nbDonAct);

            //$checkbox est un string
            foreach ($checkbox_list as $checkbox) {
                insertLinkSecteursStructure((int)$idStructure, (int)$checkbox);
            }
        }
    }

}

function supprimer_structure(int $id)
{
    $i = 0;
    $structures = getAllStructures();
    $structurePresent = false;

    //Tant qu'on a pas parcouru toutes les structures et qu'on a pas trouvé
    while ($i < sizeof($structures) && !$structurePresent) {
        $structurePresent = $structures[$i][0] == $id;
        $i++;
    }

    if ($structurePresent) {
        deleteStructure($id);
    }
}

function supprimer_secteur(int $id)
{
    $i = 0;
    $secteurs = getAllSecteurs();
    $linkSecteurStructure = getAllLinkSecteurStructure();
    $secteurPresent = false;
    $secteurUtilise = false;

    //Tant qu'on a pas parcouru toutes les structures et qu'on a pas trouvé
    while ($i < sizeof($secteurs) && !$secteurPresent && !$secteurUtilise) {
        $secteurUtilise = $linkSecteurStructure[$i][2] == $id;
        $secteurPresent = $secteurs[$i][0] == $id;
        $i++;
    }

    if ($secteurPresent && !$secteurUtilise) {
        deleteSecteur($id);
    }
}

function afficher_checkbox_secteurs($checklist)
{
    $secteurs = getAllSecteurs();

    for ($i = 0; $i < sizeof($secteurs); $i++) {
        $id = $secteurs[$i][0];
        echo " <div class=\"form-check\">";
//        echo '<li> <input type="checkbox" id="' . $id . '" name="check_list[]" value="' . $id . '"';
//        if (!is_null($checklist) && in_array($id, $checklist)) {
//            echo ' checked="checked" ';
//        }
//        echo '/>';
//        echo '<label for="' . $secteurs[$i][0] . '">' . $secteurs[$i][1] . '</label>';

        echo '<label class="form-check-label">' .
            '<input type="checkbox" id="' . $id . '" name="check_list[]" class="form-check-input" value="' . $id . '"';
        if (!is_null($checklist) && in_array($id, $checklist)) {
            echo ' checked="checked" ';
        }
        echo '>' . $secteurs[$i][1];
        '</label>';
        echo "</li>";
        echo "</div>";
    }
}

function recuperer_structure_par_id(int $id)
{
    return getStructureById($id);
}

function recuperer_idSecteurs_par_idStructure(int $id)
{
    $secteurs = getSecteursIdByStructureId($id);
    $res = [];

    for ($i = 0; $i < sizeof($secteurs); $i++) {
        $res[$i] = $secteurs[$i][0];
    }

    return $res;
}

function recuperer_libelle_secteur_par_id(int $id)
{
    return getSecteurLibelleById($id)[0][0];
}

function modifier_structure(int $id, string $nom, string $rue, string $cp, string $ville, string $structure, string $nbDonAct, $checkbox_list)
{
    if ($checkbox_list) {
        $i = 0;
        $structures = getAllStructures();
        $structurePresent = false;

        if ($structure == "Association") {
            $estAsso = 1;
            $typeContributeur = 6;  //Permet de savoir sur quel indice comparer les donnateurs/actionnaires
        } else {
            $estAsso = 0;
            $typeContributeur = 7;  //Permet de savoir sur quel indice comparer les donnateurs/actionnaires
        }

        while ($i < sizeof($structures) && !$structurePresent) {
            $item = $structures[$i];

            //Vaut faux si différent
            $structurePresent = ($item[1] == $nom && $item[2] == $rue && $item[3] == $cp && $item[4] == $ville && $item[5] == $estAsso && $item[$typeContributeur] == $nbDonAct);

            //Si c'est présent il faut vérifier si les associations sont les mêmes
            if ($structurePresent) {
                $anciensSecteurs = getSecteursIdByStructureId($id);
                $ind = 0;
                $resFinalAnciensSecteurs = [];
                while ($ind < sizeof($anciensSecteurs)) {
                    $resFinalAnciensSecteurs[$ind] = $anciensSecteurs[$ind][0];
                    $ind++;
                }

                $structurePresent = empty(array_diff($resFinalAnciensSecteurs, $checkbox_list));
            } else {
                $structurePresent = false;
            }
            $i++;
        }

        if (!$structurePresent) {
            $idStructure = updateStructure($id, $nom, $rue, $cp, $ville, $estAsso, $nbDonAct);
            deleteAllLinkByIdStructure($id);
            //$checkbox est un string
            foreach ($checkbox_list as $checkbox) {
                insertLinkSecteursStructure((int)$idStructure, (int)$checkbox);
            }
        }
    }
}

function modifier_secteur(int $id, string $nom)
{
    updateSecteur($id, $nom);
}

?>