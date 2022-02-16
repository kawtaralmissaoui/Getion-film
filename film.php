<?php
require_once("connexion.php");
class Film
{
    var $connexion;
    function __construct()
    {
        $this->connexion = new ConnexionPDO();
    }
    function __destruct()
    {
        $this->connexion->close();
        unset($this->connexion);
    }

    function ajouterFilm($titre, $realisateur, $acteur,$genre,$origine,$datesortie,$duree,$source)
    {
        $data = $this->connexion->getDataBase();
        try {
            $query = "INSERT INTO films SET titre=:titre,realisateur=:realisateur,acteur=:acteur,genre=:genre,origine=:origine,datesortie=:datesortie,duree=:duree,source=:source";
            $stmt = $data->prepare($query);
            $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindParam(':realisateur', $realisateur, PDO::PARAM_STR);
            $stmt->bindParam(':acteur', $acteur, PDO::PARAM_STR); 
            $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
            $stmt->bindParam(':origine', $origine, PDO::PARAM_STR);
            $stmt->bindParam(':datesortie', $datesortie, PDO::PARAM_STR);
            $stmt->bindParam(':duree', $duree, PDO::PARAM_STR);
            $stmt->bindParam(':source',$source, PDO::PARAM_STR);
            $resultat = $stmt->execute();
            $id = $data->lastInsertId();
            $operations = '<span id="editer"><a title="Editer" href="#" data-id="' . $id . '">
            </a></span>-';
            $operations .= '<span id="supprimer"><a title="supprimer" href="#" data-id="' . $id . '">supprimer</a></span>';
            $mysql_data[] = array(
                'id' => $id,
                'titre' => $titre,
                'realisateur' => $realisateur,
                'acteur' => $acteur,
                'source' => $source,
                'genre' => $genre,
                'origine' => $origine,
                'datesortie' => $datesortie,
                'duree' => $duree,
                'source' => $source
            );
            if (!$resultat) {
                $result = 'error';
                $message = 'query error';
            } else {
                $result = 'success';
                $message = 'query success';
            }
            echo $this->connexion->resultatJson($result, $message, $mysql_data);
        }
         catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function listeFilms()
    {
        try {
            $data = $this->connexion->getDataBase();
            $query = "SELECT * FROM films";
            $stmt = $data->prepare($query);
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function listeFilmsJSON()
    {
        $data = $this->connexion->getDataBase();
        try {
            $mysql_data = array();
            $stmt = $this->listeFilms();
            $resultat = $stmt->execute();
            if (!$resultat) {
                $result = 'error';
                $message = 'query error';
            } else {
                $result = 'success';
                $message = 'query success';
                while ($ligne = $stmt->fetch()) {
                    $operations = '<span id="editer"><a title="Editer" href="#" data-id="' . $ligne['id'] . '">Editer</a></span>';
                    $operations .= '<span id="supprimer"><a title="supprimer" href="#" data-id="' . $ligne['id'] . '">supprimer</a></span>';
                    $mysql_data[] = array(
                        'id' => $ligne["id"],
                        'titre' => $ligne["titre"],
                        'realisateur' => $ligne["realisateur"],
                        'acteur' => $ligne["acteur"],
                        'genre' => $ligne["genre"],
                        'origine' => $ligne["origine"],
                        'datesortie' => $ligne["datesortie"],
                        'duree' => $ligne["duree"],
                        'source' => $ligne["source"],
                        'operations' => $operations
                    );
                }
    return $this->connexion->resultatJson($result, $message, $mysql_data);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function supprimerFilms($id)
    {
        $data = $this->connexion->getDataBase();
        try {
            $query = "DELETE FROM films WHERE id = :id ";
            $stmt = $data->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $resultat = $stmt->execute();
            if (!$resultat) {
                $result = 'error';
                $message = 'query error';
            } else {
                $result = 'success';
                $message = 'query success';
            }
            echo $this->connexion->resultatJson($result, $message, '');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function modifierFilm($id,$titre, $realisateur, $acteur,$genre,$origine,$datesortie,$duree)
    {
        $data = $this->connexion->getDataBase();
        try {
            $query = "UPDATE films SET titre=:titre,realisateur=:realisateur,acteur=:acteur,genre=:genre,origine=:origine,datesortie=:datesortie,duree=:duree WHERE id =:id ";
            $stmt = $data->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindParam(':realisateur', $realisateur, PDO::PARAM_STR);
            $stmt->bindParam(':acteur', $acteur, PDO::PARAM_STR);
            $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
            $stmt->bindParam(':origine', $origine, PDO::PARAM_STR);
            $stmt->bindValue(':datesortie', $datesortie, PDO::PARAM_STR);
            $stmt->bindParam(':duree', $duree, PDO::PARAM_STR);
            $resultat = $stmt->execute();

            if (!$resultat) {
                $result = 'error';
                $message = 'query error';
            } else {
                $result = 'success';
                $message = 'query success';
            }
            $operations = '<span id="editer"><a title="Éditer" href="#" data-id="' . $id . '">Editer</a></span>-';

            $operations .= '<span id="supprimer"><a title="supprimer" href="#" data-id="' . $id . '">Supprimer</a></span>';


            $mysql_data[] = array(
                'id' => $id,
                'titre' => $titre,
                'realisateur' => $realisateur,
                'acteur' => $acteur,
                'genre' => $genre,
                'origine' => $origine,
                'datesortie' => $datesortie,
                'duree' => $duree,
                'operations'    => $operations
            );
            return $this->connexion->resultatJson($result, $message, $mysql_data);
        } catch (PDOException $e) {

            echo $e->getMessage();
        }
    }

    function getInfoFilm($id)
    {
        try {
            $data = $this->connexion->getDataBase();
            $query = "SELECT * FROM films WHERE id = :id";
            $stmt = $data->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    function getInfoFilmJSON($id)
    {
        $data = $this->connexion->getDataBase();
        try {
            $mysql_data = array();
            $stmt = $this->getInfoFilm($id);
            $resultat = $stmt->execute();
            if (!$resultat) {
                $result = 'error';
                $message = 'query error';
            } else {
                $result = 'success';
                $message = 'query success';
            }   
            $operations = '<span id="editer"><a title="Éditer" href="#" data-id="' . $id . '">Editer</a></span>-';

            $operations .= '<span id="supprimer"><a title="supprimer" href="#" data-id="' . $id . '">Supprimer</a></span>'; 
                $ligne = $stmt->fetch();
                $mysql_data[] = array(
                        'id' => $ligne["id"],
                        'titre' => $ligne["titre"],
                        'realisateur' => $ligne["realisateur"],
                        'acteur' => $ligne["acteur"],
                        'genre' => $ligne["genre"],
                        'origine' => $ligne["origine"],
                        'datesortie' => $ligne["datesortie"],
                        'duree' => $ligne["duree"],
                        'operations' => $operations
                );
            
            return $this->connexion->resultatJson(
                $result,
                $message,
                $mysql_data
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

     function getChercherInfo($valueToSearch)
    {
     try 
        {
            $data =$this->connexion->getDataBase();
            $query = "SELECT * FROM films WHERE CONCAT(id,titre,realisateur,acteur,genre,origine,datesortie,duree) LIKE '%".$valueToSearch."%'";
            $stmt = $data->prepare($query);
            return $stmt;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    function getChercherInfoJson($valueToSearch)
    {
     $data =$this->connexion->getDataBase();
     try 
        {        
            $mysql_data=array();
            $stmt = $this->getChercherInfo($valueToSearch);
            $resultat=$stmt->execute();
            if (!$resultat) 
            {
                $result = 'error';
                $message = 'query error';
            } 
            else 
            {
                $result = 'success';
                $message = 'query success';
                while ($ligne = $stmt->fetch()) 
                {
                    $operations = '<span id="editer"><a title="Editer" href="#" data-id="'.$ligne['id'].'">Editer</a></span>      ';
                    $operations .= '<span id="supprimer"><a title="supprimer" href="#" data-id="'.$ligne['id'].'">Supprimer</a></span>'."<br>";
                    $mysql_data[] = array(
                        'id' => $ligne["id"],
                        'titre' => $ligne["titre"],
                        'realisateur' => $ligne["realisateur"],
                        'acteur' => $ligne["acteur"],
                        'genre' => $ligne["genre"],
                        'origine' => $ligne["origine"],
                        'datesortie' => $ligne["datesortie"],
                        'duree' => $ligne["duree"],
                        'source'=>$ligne["source"],
                        'operations' => $operations
);
                
                }
            }
            return  $this->connexion->resultatJson($result, $message, $mysql_data);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }
   

}
