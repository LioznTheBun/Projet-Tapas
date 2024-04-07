<?php
namespace App\controllers;
use App\DAO\CategorieDAO;
use App\DTO\categorieDTO;

use OpenApi\Attributes as OA;

class CategorieController {

    private $requestMethod;
    private $CategorieId = null;

    public function __construct($requestMethod, $id) {
        $this->requestMethod = $requestMethod;
        $this->CategorieId = $id;
    }

    public function processRequest() {

        $response = $this->notFoundResponse();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->CategorieId) {
                    $response = $this->getCategorie($this->CategorieId);
                } else {
                    $response= $this->getAllCategorie();
                }
                break;
            case 'POST':
                if (empty($this->CategorieId)) {
                    $response = $this->createCategorie();
                }
                break;
            case 'PUT':
                $response= $this->updateCategorie();
                break;
            case 'DELETE':
                $response= $this->deleteCategorie($this->CategorieId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }

    #[OA\Get(path: '/api/categorie', tags:["Categories"], description:"get all categorie of tapas")]
    #[OA\Response(response: '200', description: 'result all categorie of tapas')] 
    public function getAllCategorie() {
        $result= CategorieDAO::getAllCategories();
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/categorie/{id}', tags:["Categories"], description:"get a categorie of tapas")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: 'get one categorie of tapas')] 
    private function getCategorie($id) {
        $result = CategorieDAO::getCategorieById($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    #[OA\Post(path: '/api/categorie', tags:["Categories"], description:"create a categorie of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'nom', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: '200', description: "")] 
    private function createCategorie() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["id"])) {
            return $this->unprocessableEntityResponse();
        }
        
        $Categorie= new CategorieDTO();
        $Categorie->setid($input["id"]);
        $Categorie->setnom($input["nom"]);
        CategorieDAO::AddCategorie($Categorie);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($Categorie);
        return $response;
    }

    #[OA\Put(path: '/api/categorie', tags:["Categories"], description:"update a categorie of tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'nom', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')] 
    private function updateCategorie() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (CategorieDAO::getCategorieById($input["id"]) !== null) {
            if (empty($input["id"])) {
                return $this->unprocessableEntityResponse();
            }
            $Categorie= new CategorieDTO();
            $Categorie->setid($input["id"]);
            $Categorie->setnom($input["nom"]);
            CategorieDAO::UpdateCategorie($Categorie);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Categorie with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }

    #[OA\Delete(path: '/api/categorie/{id}', tags:["Categories"], description:"delete a categorie of tapas rip")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')] 
    private function deleteCategorie($id) {
        
        if (CategorieDAO::getCategorieById($id) !== null) {
            CategorieDAO::DeleteCategorieById($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Categorie with id '. $id;
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
