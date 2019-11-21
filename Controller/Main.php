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

?>