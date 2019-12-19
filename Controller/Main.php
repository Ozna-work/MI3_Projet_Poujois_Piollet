<?php

require_once('../Model/db/PDO.php');

function afficher_structures()
{
    $structures = getAllStructures();
    echo "<table id='listeStructures'>";

    echo "<tr>";
    echo "<th> TYPE</th>";
    echo "<th> NOM </th>";
    echo "<th> RUE </th>";
    echo "<th> CODE POSTALE </th>";
    echo "<th> VILLE </th>";
    echo "<th> NB ACTIONNAIRES/DONNATEURS </th>";
    echo "<th> SUPPRESSION </th>";
    echo "<th> MODIFIER</th>";
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

        echo '<td><form method="post" action="">';
        echo "<input hidden name='idSuppression' value='" . $structure[0] . "'/>";
        echo '<input type="submit" value="Supprimer"/>';
        echo '</form> </td>';

        echo '<td><form method="post" action="">';
        echo "<input hidden name='idModifier' value='" . $structure[0] . "'/>";
        echo '<input type="submit" value="Modifier"/>';
        echo '</form> </td>';

        echo "</tr>";
    }

    echo "</table>";
}

function afficher_secteurs()
{
    $secteurs = getAllSecteurs();


    foreach ($secteurs as $secteur) {
        echo "<tr>";
        echo "<td>" . $secteur[1] . "</td>";
        echo "<td> <input class='modifieSecteur' name='idSecteurModifie' type='submit' value='".$secteur[0]."'/>";
        echo "<td> <input class='deleteSecteur' name='idSecteurSupprime' type='submit' value='".$secteur[0]."'/>";
        echo "</tr>";
    }

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
    while ($i < sizeof($secteurs) && !$secteurPresent &&!$secteurUtilise) {
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
        echo "<tr>";
        echo '<td> <input type="checkbox" id="' . $id . '" name="check_list[]" value="' . $id . '"';
        if (!is_null($checklist) && in_array($id, $checklist)) {
            echo ' checked="checked" ';
        }
        echo '/>';
        echo '<label for="' . $secteurs[$i][0] . '">' . $secteurs[$i][1] . '</label>';
        echo "</td>";
        echo "</tr>";
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

function modifier_structure(int $id, string $nom, string $rue, string $cp, string $ville, string $structure, string $nbDonAct, $checkbox_list) {
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
            $idStructure = updateStructure($id,$nom, $rue, $cp, $ville, $estAsso, $nbDonAct);

            //$checkbox est un string
            foreach ($checkbox_list as $checkbox) {
                updateLinkSecteursStructures((int)$idStructure, (int)$checkbox);
            }
        }
    }}

?>