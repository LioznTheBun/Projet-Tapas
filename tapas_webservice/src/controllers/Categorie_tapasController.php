<?php
namespace App\controllers;
use App\DAO\Categorie_tapasDAO;
use App\DTO\Categorie_tapasDTO; 

use OpenApi\Attributes as OA;

class Categorie_tapasController {
    
    private $requestMethod;
    private $Categorie_tapasId = null;
    private $idget;

    public function __construct($requestMethod, $id, $idget) {
        $this->requestMethod = $requestMethod;
        $this->Categorie_tapasId = $id;
        $this->idget= $idget;
    }

    public function processRequest() {
        $response = $this->notFoundResponse();
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->Categorie_tapasId) {
                    $response = $this->getCategorie_tapas($this->Categorie_tapasId);
                }else {
                    if (!empty($this->idget)) {
                        $response= $this->getCategorieTapasByTapasId($this->idget);
                    }else{
                        $response= $this->getAllCategorie_tapas();
                    }
                    
                }
                break;
            case 'POST':
                if (empty($this->Categorie_tapasId)) {
                    $response = $this->createCategorie_tapas();
                }
                break;
            case 'PUT':
                $response= $this->updateCategorie_tapas();
                break;
            case 'DELETE':
                $response= $this->deleteCategorie_tapas($this->Categorie_tapasId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }

    #[OA\Get(path: '/api/categorie_tapas?tapasid={id}', tags:["Categories Tapas"], description:"get a categorie of tapas")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')] 
    public function getCategorieTapasByTapasId($id) {
        $result= Categorie_tapasDAO::getCategorieTapasByTapasId($id);
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/categorie_tapas', tags:["Categories Tapas"], description:"get all categorie of tapas")]
    #[OA\Response(response: '200', description: '')] 
    public function getAllCategorie_tapas() {
        $result= Categorie_tapasDAO::getAllCategorieTapas();
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/categorie_tapas/{id}', tags:["Categories Tapas"], description:"get a categorie of tapas")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')]
    private function getCategorie_tapas($id) {
        $result = Categorie_tapasDAO::getCategorieTapasByCategorieId($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    #[OA\Post(path: '/api/categorie_tapas', tags:["Categories Tapas"], description:"create a categorie of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'tapas_id', type: 'number'),
                new OA\Property(property: 'categorie_id', type: 'number')
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function createCategorie_tapas() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["tapas_id"])) {
            return $this->unprocessableEntityResponse();
        }
        
        $Categorie_tapas= new Categorie_tapasDTO();
        $Categorie_tapas->settapasid($input["tapas_id"]);
        $Categorie_tapas->setcategorieid($input["categorie_id"]);
        Categorie_tapasDAO::AddCategorieTapas($Categorie_tapas);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($Categorie_tapas);
        return $response;
    }

    #[OA\Put(path: '/api/categorie_tapas', tags:["Categories Tapas"], description:"update a categorie of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'tapas_id', type: 'number'),
                new OA\Property(property: 'categorie_id', type: 'number')
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function updateCategorie_tapas() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (Categorie_tapasDAO::getCategorieTapasByCategorieId($input["categorieid"]) !== null) {
            if (empty($input["tapas_id"])) {
                return $this->unprocessableEntityResponse();
            }
            $Categorie_tapas= new Categorie_tapasDTO();
            $Categorie_tapas->settapasid($input["tapas_id"]);
            $Categorie_tapas->setcategorieid($input["categorie_id"]);
            Categorie_tapasDAO::UpdateCategorieTapas($Categorie_tapas);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Categorie_tapas with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }

    #[OA\Delete(path: '/api/categorie_tapas/{id}', tags:["Categories Tapas"], description:"delete a categorie of tapas rip")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')]
    private function deleteCategorie_tapas($id) {
        
        if (Categorie_tapasDAO::getCategorieTapasByTapasId($id) !== null) {
            Categorie_tapasDAO::DeleteCategorieTapasByTapasId($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Categorie_tapas with id '. $id;
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
