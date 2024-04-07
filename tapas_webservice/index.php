<?php
namespace App;
use App\controllers\TapasController;
use App\controllers\Categorie_tapasController;
use App\controllers\TablesController;
use App\controllers\Quantite_tapasController;
use App\controllers\HistoriquecommandeController;
use App\controllers\CommandeController;
use App\controllers\CategorieController;

include("vendor/autoload.php");
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	// méthodes HTTP autorisées
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
	// durée d'expiration du cache
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	// sujet de l'URL (cartes ou marques)
	$urlSubject = "";
	// paramètre supplémentaire à la fin de l'URL (id de carte ou de marque)
	$urlParam = "";
	
	// découpage de l'URL
	$url = $_SERVER['REQUEST_URI'];
	if (preg_match("/^.+?api\/(.+)$/is" , $url, $urlContents)) {
		// récupération de la fin de l'URL ( après server/ )
		$urlEnd = $urlContents[1];
		// séparation de la fin de l'URL et des paramètres
		$urlParts = explode("/", $urlEnd);

		// récupération du sujet de l'URL
		$urlSubject = $urlParts[0];
		// récupération du paramètre supplémentaire s'il existe
		if (isset($urlParts[1])) {	
			$urlParam = $urlParts[1];
		}
	}

	// méthode HTTP utilisée (GET, POST, PUT, DELETE)
	$requestMethod = $_SERVER["REQUEST_METHOD"];

	
	// fonction de chargement automatique des fichiers
	/*function includeFileWithClassName($class_name)
    {
        // répertoires contenant les classes
        $directorys = array(
            'controllers/',
            'DAO/',
            'DTO/',
            'tools/'
        );
       
        // pour chaque répertoire
        foreach($directorys as $directory)
        {
            // si le fichier existe
            if(file_exists($directory.$class_name . '.php'))
            {
				// inclus le fichier une seule fois
                require_once($directory.$class_name . '.php');
                return;
            }           
        }
		require_once('controllers/TapasController.php'); 
    }*/
	
	
	// enregistrement de la fonction de chargement automatique des fichiers
	//spl_autoload_register('includeFileWithClassName');

	// en fonction de l'URL appelée, on charge différents controllers
	$tempid= null;
	if (!empty($_GET["tapasid"])) {
		$tempid= $_GET["tapasid"];
		$urlSubject= "categorie_tapas";
	}
	switch ($urlSubject) {
		case "tapas" :
			$tapasId= null;
			if (!empty($urlParam)) {
				$tapasId= (int) $urlParam;
			}
			$controller= new TapasController($requestMethod, $tapasId);
			$controller->processRequest();
			break;
		case "tables" : 
			$tablesId= null;
			if (!empty($urlParam)) {
				$tablesId= (int) $urlParam;
			}
			$controller= new TablesController($requestMethod, $tablesId);
			$controller->processRequest();
			break;
		case "quantite_tapas":
			$quantiteid= null;
			if (!empty($urlParam)) {
				$quantiteid= (int) $urlParam;
			}
			$controller= new Quantite_tapasController($requestMethod, $quantiteid);
			$controller->processRequest();
			break;
		case "historiquecommande":
			$historiqueid= null;
			if (!empty($urlParam)) {
				$historiqueid= (int) $urlParam;
			}
			$controller= new HistoriquecommandeController($requestMethod, $historiqueid);
			$controller->processRequest();
			break;
		case "commande":
			$commandeid= null;
			if (!empty($urlParam)) {
				$commandeid= (int) $urlParam;
			}
			$controller= new CommandeController($requestMethod, $commandeid);
			$controller->processRequest();
			break;
		case "categorie":
			$categorieid= null;
			if (!empty($urlParam)) {
				$categorieid= (int) $urlParam;
			}
			$controller= new CategorieController($requestMethod, $categorieid);
			$controller->processRequest();
			break;
		case "categorie_tapas":
			$categorieid= null;
			if (!empty($urlParam)) {
				$categorieid= (int) $urlParam;
			}
			$controller= new Categorie_tapasController($requestMethod, $categorieid, $tempid);
			$controller->processRequest();
			break;
		default:	
			header("HTTP/1.1 404 Not Found");
			exit();
			break;
	
	}	
	