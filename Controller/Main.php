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
        for($i = 1; $i<5; $i++) {
            echo "<td>".$structure[$i]."</td>";
        }

        //Si le nombre de donateurs n'est pas null, on affiche ce nombre, sinon on affiche le nombre d'actionnaires
        if($structure[6]) {
            echo "<td>".$structure[6]."</td>";
        } else {
            echo "<td>".$structure[7]."</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}

function afficher_secteurs() {
    $secteurs = getAllSecteurs();


    foreach ($secteurs as $secteur) {
        echo "<tr>";
        echo "<td>".$secteur[1]."</td>";
        echo "</tr>";
    }

}

function inserer_nouveaux_secteurs(string $libelle) {

    $secteurs = getAllSecteurs();
    $secteurPresent = false;
    $i = 0;

    //Tant qu'on a pas parcouru tous les secteurs ET que le secteur n'est pas présent
    while ($i<sizeof($secteurs) && !$secteurPresent) {
        //Prends la valeur true si le resultat est différent du libelle cherché
        $secteurPresent = ($secteurs[$i][1] == $libelle);
        $i++;
    }

    if(!$secteurPresent){
        insertSecteur($libelle);
    }
}

function inserer_nouvelles_structures(string $nom, string $rue, string $cp, string $ville, string $structure, string $nbDonAct) {
    $i = 0;
    $structures = getAllStructures();
    $structurePresent = false;

    if($structure == "Association") {
        $estAsso = 1;
        $typeContributeur = 6;  //Permet de savoir sur quel indice comparer les donnateurs/actionnaires
    } else {
        $estAsso = 0;
        $typeContributeur = 7;  //Permet de savoir sur quel indice comparer les donnateurs/actionnaires
    }

    while($i<sizeof($structures) && !$structurePresent) {
        $item = $structures[$i];
        $structurePresent = ($item[1] == $nom && $item[2] == $rue && $item[3] == $cp && $item[4] == $ville && $item[5] == $estAsso && $item[$typeContributeur] == $nbDonAct);
        $i++;
    }

    insertStructure($nom, $rue, $cp, $ville, $estAsso, $nbDonAct);

}

?>