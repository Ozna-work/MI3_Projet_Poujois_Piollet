<?php

function getConnexion(): PDO
{
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "mi3_projet";

    // connexion à l'aide d'une chaîne de connexion
    $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
    // Configure le mode d'erreur de PDO à exception (mode non par défaut)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

function getAllStructures(): array
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select * from structure");
        $res = $stmt->execute();

        if ($res) {
            $lines = $stmt->fetchAll();
            return $lines;
        }
    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }

    return array();
}

function getAllSecteurs() : array {
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select * from secteur");
        $res = $stmt->execute();

        if ($res) {
            $lines = $stmt->fetchAll();
            return $lines;
        }
    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }

    return array();
}

function insertStructure(string $nom,string $rue, string $cp, string $ville, int $estasso, int $nb_donAct)
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("INSERT INTO Structure(nom, rue, cp, ville, estasso, nb_donateurs, nb_actionnaires) VALUES (:nom,:rue,:cp,:ville,:estasso,:don,:act)");
        $stmt->bindValue("NOM",$nom, PDO::PARAM_STR);
        $stmt->bindValue("RUE",$rue, PDO::PARAM_STR);
        $stmt->bindValue("CP",$cp, PDO::PARAM_STR);
        $stmt->bindValue("VILLE",$ville, PDO::PARAM_STR);
        $stmt->bindValue("ESTASSO",$estasso, PDO::PARAM_INT);

        //Si c'est une association
        if($estasso == 1) {
            $stmt->bindValue("NB_DONATEURS",$nb_donAct, PDO::PARAM_INT);
            $stmt->bindValue("NB_ACTIONNAIRES",NULL, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue("NB_DONATEURS",NULL, PDO::PARAM_NULL);
            $stmt->bindValue("NB_ACTIONNAIRES",$nb_donAct, PDO::PARAM_INT);
        }

        $res = $stmt->execute();

        if ($res) {
            //$stmt = $conn->prepare("INSERT INTO Secteurs_Structures(id_structure, id_secteur) VALUES ()");
        }


    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

function insertSecteur(string $libelle)
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("INSERT INTO Secteur(libelle) VALUES (:libelle)");
        $stmt->bindValue("LIBELLE",$libelle, PDO::PARAM_STR);
        $stmt->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

?>