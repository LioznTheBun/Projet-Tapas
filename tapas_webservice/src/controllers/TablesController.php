<?php
namespace App\controllers;
use App\DAO\TablesDAO;
use App\DTO\TablesDTO;

use OpenApi\Attributes as OA;

class TablesController {

    private $requestMethod;
    private $TablesId = null;

    public function __construct($requestMethod, $id) {
        $this->requestMethod = $requestMethod;
        $this->TablesId = $id;
    }

    public function processRequest() {

        $response = $this->notFoundResponse();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->TablesId) {
                    $response = $this->getTables($this->TablesId);
                } else {
                    $response= $this->getAllTables();
                }
                break;
            case 'POST':
                if (empty($this->TablesId)) {
                    $response = $this->createTables();
                }
                break;
            case 'PUT':
                $response= $this->updateTables();
                break;
            case 'DELETE':
                $response= $this->deleteTables($this->TablesId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }
    #[OA\Get(path: '/api/tables', tags:["Tables"], description:"give me all tables")]
    #[OA\Response(response: '200', description: 'The data of all tables')]
    public function getAllTables() {
        $result= TablesDAO::getAllTables();
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/tables/{id}', tags:["Tables"], description:"give me a table")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: 'The data of a table')]
    private function getTables($id) {
        $result = TablesDAO::getTableById($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    #[OA\Post(path: '/api/tables', tags:["Tables"], description:"create a table")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number')
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function createTables() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["id"])) {
            return $this->unprocessableEntityResponse();
        }
        
        $Tables= new TablesDTO();
        $Tables->setid($input["id"]);
        TablesDAO::AddTable($Tables);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($Tables);
        return $response;
    }

    #[OA\Put(path: '/api/tables', tags:["Tables"], description:"update a tables")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number')
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function updateTables() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (TablesDAO::getTableById($input["id"]) !== null) {
            if (empty($input["id"])) {
                return $this->unprocessableEntityResponse();
            }
            $Tables= new TablesDTO();
            $Tables->setid($input["id"]);
            //TablesDAO::UpdateTables($Tables);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Tables with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }

    #[OA\Delete(path: '/api/tables/{id}', tags:["Tables"], description:"delete a table rip")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')]
    private function deleteTables($id) {
        
        if (TablesDAO::getTableById($id) !== null) {
            TablesDAO::DeleteTableById($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Tables with id '. $id;
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
