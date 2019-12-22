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

//Récupère toutes les structures présentes en base de données
function getAllStructures(): array
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select * from structure");
        $res = $stmt->execute();

        //Si la requete s'est exectuée sans erreur
        if ($res) {
            $structuresBd = $stmt->fetchAll(); //Recuperation des structures
            $structures = array();

            //Transformation des données en objets
            foreach ($structuresBd as $item){
                if ($item[5] == 1){ //Si estAsso = 1
                    array_push($structures, Association::buildFromArray($item));    //Création d'une association
                }else{
                    array_push($structures, Entreprise::buildFromArray($item));     //Sinon création d'une entreprise
                }
            }
            return $structuresBd;  //TODO retourner l'array d'objets
        }
    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }

    return array(); //En cas d'erreur on retourne un array vide
}

//récupère tous les éléments de la table associant secteurs et structures
function getAllLinkSecteurStructure(): array
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select * from secteurs_structures");
        $res = $stmt->execute();

        //Si la requete s'est exectuée sans erreur
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

    return array();//En cas d'erreur on retourne un array vide
}

//Retourne la structure correspondant à l'id donné
function getStructureById($id)
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select * from structure where id= :id");
        $stmt->bindValue("id", $id, PDO::PARAM_INT);
        $res = $stmt->execute();

        //Si la requete s'est exectuée sans erreur
        if ($res) {
            $structureDb = $stmt->fetchAll();
            if ($structureDb[0][5] == 1)    //Si estAsso = 1
            {
                $structure = Association::buildFromArray($structureDb[0]);
            }else{
                $structure = Entreprise::buildFromArray($structureDb[0]);
            }

            return $structureDb;   //TODO Retourner l'objet
        }

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//Retourne tous les secteurs ce la base de données
function getAllSecteurs(): array
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select * from secteur");
        $res = $stmt->execute();

        //Si la requete s'est exectuée sans erreur
        if ($res) {
            $lines = $stmt->fetchAll();
            $secteurs = [];
            foreach ($lines as $item){
                array_push($secteurs,$item);
            }
            return $lines;  //TODO retourner l'array d'objet
        }
    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }

    return array();//En cas d'erreur on retourne un array vide
}

//Recupère les Id des secteurs associés à une structure donnée
//TODO Retourner des objets secteur plutot que des id ? voir comment la fonction est utilisée, peut améliorer la lisibilité si pas d'avantages pratiques
function getSecteursIdByStructureId($id)
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select id from secteur where id IN(select id_secteur from secteurs_structures where id_structure = :id)");
        $stmt->bindValue("id", $id, PDO::PARAM_INT);
        $res = $stmt->execute();

        if ($res) {
            return $stmt->fetchAll();
        }

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//Retourne le libellé d'un secteur d'id donné
//TODO Retourner un objet secteur plutot que le nom ? voir comment la fonction est utilisée, peut améliorer la lisibilité si pas d'avantages pratiques
function getSecteurLibelleById($id)
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("select libelle from secteur where id = :id");
        $stmt->bindValue("id", $id, PDO::PARAM_INT);
        $res = $stmt->execute();

        if ($res) {
            return $stmt->fetchAll();
        }

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//Insère une nouvelle structure et retourne son id
function insertStructure(string $nom, string $rue, string $cp, string $ville, int $estasso, int $nb_donAct)
{
    try {
        $conn = getConnexion();
        $stmt_structure = $conn->prepare("INSERT INTO Structure(nom, rue, cp, ville, estasso, nb_donateurs, nb_actionnaires) VALUES (:nom,:rue,:cp,:ville,:estasso,:don,:act)");
        $stmt_structure->bindValue("nom", $nom, PDO::PARAM_STR);
        $stmt_structure->bindValue("rue", $rue, PDO::PARAM_STR);
        $stmt_structure->bindValue("cp", $cp, PDO::PARAM_STR);
        $stmt_structure->bindValue("ville", $ville, PDO::PARAM_STR);
        $stmt_structure->bindValue("estasso", $estasso, PDO::PARAM_INT);

        //Si c'est une association
        if ($estasso == 1) {
            $stmt_structure->bindValue("don", $nb_donAct, PDO::PARAM_INT);
            $stmt_structure->bindValue("act", NULL, PDO::PARAM_NULL);
        } else {
            $stmt_structure->bindValue("don", NULL, PDO::PARAM_NULL);
            $stmt_structure->bindValue("act", $nb_donAct, PDO::PARAM_INT);
        }

        $stmt_structure->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        $id = $conn->lastInsertId();
        // fermeture de la connexion
        $conn = null;

        return $id;
    }
}

//Insère un lien entre une structure et le secteur correspondant
function insertLinkSecteursStructure(int $idStructure, int $idSecteur)
{
    try {
        $conn = getConnexion();

        $stmt_link = $conn->prepare("INSERT INTO Secteurs_Structures(ID_STRUCTURE, ID_SECTEUR) VALUES (:idStructure, :idSecteur)");
        $stmt_link->bindValue("idStructure", $idStructure, PDO::PARAM_INT);
        $stmt_link->bindValue("idSecteur", $idSecteur, PDO::PARAM_INT);

        $stmt_link->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//function updateLinkSecteursStructures(int $idStructure, int $idSecteur)
//{
//    try {
//        $conn = getConnexion();
//
//        $stmt_delete = $conn->prepare("DELETE FROM Secteurs_Structures WHERE ID_STRUCTURE = :idStructure");
//        $stmt_delete->bindValue("idStructure", $idStructure, PDO::PARAM_INT);
//        $stmt_delete->execute();
//
//        insertLinkSecteursStructure($idStructure,$idSecteur);
//
//
//    } catch (PDOException $e) {
//        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
//    } finally {
//        // fermeture de la connexion
//        $conn = null;
//    }
//}

//Insère un nouveau secteur en base de données
function insertSecteur(string $libelle)
{
    try {
        $conn = getConnexion();
        $stmt = $conn->prepare("INSERT INTO Secteur(libelle) VALUES (:libelle)");
        $stmt->bindValue("libelle", $libelle, PDO::PARAM_STR);
        $stmt->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//Supprime tous les liens d'une structure à ses secteurs
function deleteAllLinkByIdStructure(int $id)
{
    try {
        $conn = getConnexion();

        $stmt_link = $conn->prepare("DELETE FROM secteurs_structures WHERE id_structure= (:id)");
        $stmt_link->bindValue("id", $id, PDO::PARAM_INT);
        $stmt_link->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//Supprime une structure (et les liens à ses secteurs)
function deleteStructure(int $id)
{
    try {
        $conn = getConnexion();

        deleteAllLinkByIdStructure($id);    //Supprime les liens structure -> secteurs

        $stmt_structure = $conn->prepare("DELETE FROM Structure WHERE id= (:id)");
        $stmt_structure->bindValue("id", $id, PDO::PARAM_INT);
        $stmt_structure->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

//Met à jour les informations d'une structure (selon son id), et retourne son id
function updateStructure(int $id, string $nom, string $rue, string $cp, string $ville, int $estasso, int $nb_donAct)
{
    try {
        $conn = getConnexion();
        $stmt_structure = $conn->prepare("UPDATE Structure 
                                                    SET NOM = :nom, RUE = :rue, CP = :cp, VILLE = :ville, ESTASSO = :estasso, NB_DONATEURS = :don, NB_ACTIONNAIRES = :act 
                                                    WHERE id = :id");
        $stmt_structure->bindValue("id", $id, PDO::PARAM_INT);
        $stmt_structure->bindValue("nom", $nom, PDO::PARAM_STR);
        $stmt_structure->bindValue("rue", $rue, PDO::PARAM_STR);
        $stmt_structure->bindValue("cp", $cp, PDO::PARAM_STR);
        $stmt_structure->bindValue("ville", $ville, PDO::PARAM_STR);
        $stmt_structure->bindValue("estasso", $estasso, PDO::PARAM_INT);

        //Si c'est une association
        if ($estasso == 1) {
            $stmt_structure->bindValue("don", $nb_donAct, PDO::PARAM_INT);
            $stmt_structure->bindValue("act", NULL, PDO::PARAM_NULL);
        } else {
            $stmt_structure->bindValue("don", NULL, PDO::PARAM_NULL);
            $stmt_structure->bindValue("act", $nb_donAct, PDO::PARAM_INT);
        }

        $stmt_structure->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;

        return $id;
    }
}

//Met à jour un secteur en se basant sur son id, et retourne son id
function updateSecteur(int $id, string $nom)
{
    try {
        $conn = getConnexion();
        $stmt_secteur = $conn->prepare("UPDATE Secteur SET LIBELLE = :nom WHERE id = :id");
        $stmt_secteur->bindValue("nom", $nom, PDO::PARAM_STR);
        $stmt_secteur->bindValue("id", $id, PDO::PARAM_INT);

        $stmt_secteur->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;

        return $id;
    }
}

//Supprime un secteur selon son id
function deleteSecteur(int $id)
{
    try {
        $conn = getConnexion();

        $stmt_link = $conn->prepare("DELETE FROM secteurs_structures WHERE id_secteur= (:id)");
        $stmt_link->bindValue("id", $id, PDO::PARAM_INT);
        $stmt_link->execute();

        $stmt_structure = $conn->prepare("DELETE FROM Secteur WHERE id= (:id)");
        $stmt_structure->bindValue("id", $id, PDO::PARAM_INT);
        $stmt_structure->execute();

    } catch (PDOException $e) {
        echo "Error " . $e->getCode() . " : " . $e->getMessage() . "<br/>" . $e->getTraceAsString();
    } finally {
        // fermeture de la connexion
        $conn = null;
    }
}

?>