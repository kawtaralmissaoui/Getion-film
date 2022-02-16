<?php
error_reporting(1);
require_once("film.php");
extract( $_GET, EXTR_OVERWRITE );

header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

if(file_get_contents("php://input") != null)
{

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = $request->id;
$operation = $request->operation;
$titre = $request->titre;
$realisateur = $request->realisateur;
$acteur = $request->acteur;
$genre = $request->genre;
$origine = $request->origine;
$datesortie = $request->datesortie;
$duree = $request->duree;
$source=$request->source;
$valueToSearch=$request->valueToSearch;
}

$film = new Film();
if ($operation == 'ajouterFilm') 
{
    $resultat = $film->ajouterFilm($titre,$realisateur,$acteur,$genre,$origine,$datesortie,$duree,$source);
} 
elseif ($operation == 'listeFilms') 
{
    echo $film->listeFilmsJSON();
} 
elseif ($operation == 'supprimerFilms') 
{
    echo $film->supprimerFilms($id);
} 
elseif ($operation == 'modifierFilm') 
{
    echo $film->modifierFilm($id,$titre, $realisateur,$acteur,$genre,$origine,$datesortie,$duree);
} 
elseif ($operation == 'getInfoFilm') 
{
    echo $film->getInfoFilmJSON($id);
}
elseif ($operation == 'getChercherInfo') 
{
    echo $film->getChercherInfoJson($valueToSearch);
}

unset($film);
?>