<?php
namespace App\controllers;
use App\DTO\Quantite_tapasDTO;
use App\DAO\Quantite_tapasDAO;

use OpenApi\Attributes as OA;

class Quantite_tapasController {

    private $requestMethod;
    private $Quantite_tapasId = null;

    public function __construct($requestMethod, $id) {
        $this->requestMethod = $requestMethod;
        $this->Quantite_tapasId = $id;
    }

    public function processRequest() {

        $response = $this->notFoundResponse();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->Quantite_tapasId) {
                    $response = $this->getQuantite_tapas($this->Quantite_tapasId);
                } else {
                    $response= $this->getAllQuantite_tapas();
                }
                break;
            case 'POST':
                if (empty($this->Quantite_tapasId)) {
                    $response = $this->createQuantite_tapas();
                }
                break;
            case 'PUT':
                $response= $this->updateQuantite_tapas();
                break;
            case 'DELETE':
                $response= $this->deleteQuantite_tapas($this->Quantite_tapasId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }

    #[OA\Get(path: '/api/quantite_tapas', tags:["Quantite Tapas"], description:"get all quantite of tapas")]
    #[OA\Response(response: '200', description: 'result of all quantite tapas')]
    public function getAllQuantite_tapas() {
        $result= Quantite_tapasDAO::getAllQuantiteTapas();
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }
    #[OA\Get(path: '/api/quantite_tapas/{id}', tags:["Quantite Tapas"], description:"get quantite tapas ByCommandeId")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: 'result of quantite tapas')]
    private function getQuantite_tapas($id) {
        $result = Quantite_tapasDAO::getQuantiteTapasByCommandeId($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    #[OA\Post(path: '/api/quantite_tapas', tags:["Quantite Tapas"], description:"create a quantite of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'tapas_id', type: 'number'),
                new OA\Property(property: 'commandeid', type: 'number'),
                new OA\Property(property:"quantite", type:"number")
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function createQuantite_tapas() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["tapas_id"])) {
            return $this->unprocessableEntityResponse();
        }
        
        $Quantite_tapas= new Quantite_tapasDTO();
        $Quantite_tapas->settapasid($input["tapas_id"]);
        $Quantite_tapas->setquantite($input["quantite"]);
        $Quantite_tapas->setcommandeid($input["commandeid"]);
        Quantite_tapasDAO::AddQuantiteTapas($Quantite_tapas);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($Quantite_tapas);
        return $response;
    }

    #[OA\Put(path: '/api/quantite_tapas', tags:["Quantite Tapas"], description:"update a quantite of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'tapas_id', type: 'number'),
                new OA\Property(property: 'commandeid', type: 'number'),
                new OA\Property(property:"quantite", type:"number")
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function updateQuantite_tapas() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (Quantite_tapasDAO::getQuantiteTapasByCommandeId($input["commandeid"]) !== null) {
            if (empty($input["tapas_id"])) {
                return $this->unprocessableEntityResponse();
            }
            $Quantite_tapas= new Quantite_tapasDTO();
            $Quantite_tapas->settapasid($input["tapas_id"]);
            $Quantite_tapas->setquantite($input["quantite"]);
            $Quantite_tapas->setcommandeid($input["commandeid"]);
            Quantite_tapasDAO::UpdateQuantiteTapas($Quantite_tapas);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Quantite_tapas with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }
    #[OA\Delete(path: '/api/quantite_tapas/{id}', tags:["Quantite Tapas"], description:"delete quantite tapas ByCommandeId rip")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')]
    private function deleteQuantite_tapas($id) {
        
        if (Quantite_tapasDAO::getQuantiteTapasByCommandeId($id) !== null) {
            Quantite_tapasDAO::DeleteQuantiteTapasByCommandeId($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Quantite_tapas with id '. $id;
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
