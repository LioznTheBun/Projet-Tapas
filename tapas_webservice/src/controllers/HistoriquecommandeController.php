<?php
namespace App\controllers;
use App\DTO\HistoriquecommandeDTO;
use App\DAO\HistoriquecommandeDAO;

use OpenApi\Attributes as OA;

class HistoriquecommandeController {

    private $requestMethod;
    private $HistoriquecommandeId = null;

    public function __construct($requestMethod, $id) {
        $this->requestMethod = $requestMethod;
        $this->HistoriquecommandeId = $id;
    }

    public function processRequest() {

        $response = $this->notFoundResponse();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->HistoriquecommandeId) {
                    $response = $this->getHistoriquecommandebycommandeid($this->HistoriquecommandeId);
                } else {
                    $response= $this->getAllHistoriquecommande();
                }
                break;
            case 'POST':
                if (empty($this->HistoriquecommandeId)) {
                    $response = $this->createHistoriquecommande();
                }
                break;
            case 'PUT':
                $response= $this->updateHistoriquecommande();
                break;
            case 'DELETE':
                $response= $this->deleteHistoriquecommande($this->HistoriquecommandeId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }

    #[OA\Get(path: '/api/historiquecommande', tags:["Historique Commandes"], description:"give me all historique of commande")]
    #[OA\Response(response: '200', description: 'The data of all historique commande')]    
    public function getAllHistoriquecommande() {
        $result= HistoriquecommandeDAO::getAllHistorique();
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/historiquecommande/{id}', tags:["Historique Commandes"], description:"give me historique of commande by commande id")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: 'The data of a historique commande')]    
    private function getHistoriquecommandebycommandeid($id) {
        $result = HistoriquecommandeDAO::getHistoriqueByCommandeId($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    #[OA\Post(path: '/api/historiquecommande', tags:["Historique Commandes"], description:"create historique of commande")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'commandeid', type: 'number'),
                new OA\Property(property:"date", type:"string"),
                new OA\Property(property:"statut", type:"string")
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]    
    private function createHistoriquecommande() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["id"])) {
            return $this->unprocessableEntityResponse();
        }
        
        $Historiquecommande= new HistoriquecommandeDTO();
        $Historiquecommande->setid($input["id"]);
        $Historiquecommande->setcommandeid($input["commandeid"]);
        $Historiquecommande->setdate($input["date"]);
        $Historiquecommande->setstatut($input["statut"]);
        HistoriquecommandeDAO::AddHistoriqueCommande($Historiquecommande);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($Historiquecommande);
        return $response;
    }

    #[OA\Put(path: '/api/historiquecommande', tags:["Historique Commandes"], description:"update historique of commande")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'commandeid', type: 'number'),
                new OA\Property(property:"date", type:"string"),
                new OA\Property(property:"statut", type:"string")
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]    
    private function updateHistoriquecommande() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (HistoriquecommandeDAO::getHistoriqueById($input["id"]) !== null) {
            if (empty($input["id"])) {
                return $this->unprocessableEntityResponse();
            }
            $Historiquecommande= new HistoriquecommandeDTO();
            $Historiquecommande->setid($input["id"]);
            $Historiquecommande->setcommandeid($input["commandeid"]);
            $Historiquecommande->setdate($input["date"]);
            $Historiquecommande->setstatut($input["statut"]);
            HistoriquecommandeDAO::updateHistoriqueCommande($Historiquecommande);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Historiquecommande with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }

    #[OA\Delete(path: '/api/historiquecommande/{id}', tags:["Historique Commandes"], description:"delete historique of commande")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')]    
    private function deleteHistoriquecommande($id) {
        
        if (HistoriquecommandeDAO::getHistoriqueById($id) !== null) {
            HistoriquecommandeDAO::DeleteHistoriqueCommandeById($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Historiquecommande with id '. $id;
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
