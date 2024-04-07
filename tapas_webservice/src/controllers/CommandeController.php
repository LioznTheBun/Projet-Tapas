<?php
namespace App\controllers;
use App\DAO\CommandeDAO;
use App\DTO\CommandeDTO;

use OpenApi\Attributes as OA;

class CommandeController {

    private $requestMethod;
    private $CommandeId = null;

    public function __construct($requestMethod, $id) {
        $this->requestMethod = $requestMethod;
        $this->CommandeId = $id;
    }

    public function processRequest() {

        $response = $this->notFoundResponse();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->CommandeId) {
                    $response = $this->getCommandebyId($this->CommandeId);
                } else {
                    $response= $this->getAllCommandes();
                }
                break;
            case 'POST':
                if (empty($this->CommandeId)) {
                    $response = $this->createCommande();
                }
                break;
            case 'PUT':
                $response= $this->updateCommande();
                break;
            case 'DELETE':
                $response= $this->deleteCommande($this->CommandeId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }

    #[OA\Get(path: '/api/commande', tags:["Commandes"], description:"get all commandes of tapas")]
    #[OA\Response(response: '200', description: 'result of all commandes')]    
    public function getAllCommandes() {
        $result= CommandeDAO::getAllCommandes();
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/commande/{id}', tags:["Commandes"], description:"get a commande of tapas")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: 'result of a commande')] 
    private function getCommandebyId($id) {
        $result = CommandeDAO::getCommandeById($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    #[OA\Post(path: '/api/commande', tags:["Commandes"], description:"create a commande of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'tableid', type: 'number'),
                new OA\Property(property: 'prix_total', type: 'float'),
                new OA\Property(property: 'confirmee', type: 'boolean'),
                new OA\Property(property: 'date', type: 'string'),
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')] 
    private function createCommande() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        
        $Commande = new CommandeDTO();
        $Commande->settableid($input["tableid"]);
        $Commande->setprixtotal($input["prix_total"]);
        $Commande->setconfirmee($input["confirmee"]);
        $Commande->setdate($input["date"]);
    
        $id = CommandeDAO::AddCommande($Commande);
    
        if ($id) {
            $Commande->setid($id);
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode($Commande);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode(array('message' => 'Erreur lors de l\'insertion de la commande'));
        }
    
        return $response;
    }

    #[OA\Put(path: '/api/commande', tags:["Commandes"], description:"update' a commande of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'tableid', type: 'number'),
                new OA\Property(property: 'prix_total', type: 'float'),
                new OA\Property(property: 'confirmee', type: 'boolean'),
                new OA\Property(property: 'date', type: 'string'),
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')] 
    private function updateCommande() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (CommandeDAO::getCommandeById($input["id"]) !== null) {
            $Commande= new CommandeDTO();
            $Commande->setid($input["id"]);
            $Commande->settableid($input["tableid"]);
            $Commande->setprixtotal($input["prix_total"]);
            $Commande->setconfirmee($input["confirmee"]);
            $Commande->setdate($input["date"]);
            CommandeDAO::UpdateCommande($Commande);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Commande with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }

    #[OA\Delete(path: '/api/commande/{id}', tags:["Commandes"], description:"delete a commande of tapas rip")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')] 
    private function deleteCommande($id) {
        
        if (CommandeDAO::getCommandeById($id) !== null) {
            CommandeDAO::DeleteCommandeById($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Commande with id '. $id;
        }
        $response['body'] = null;
        return $response;
    }

    private function unprocessableEntityResponse() {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
