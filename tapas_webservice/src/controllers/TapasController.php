<?php
namespace App\controllers;
use App\DTO\TapasDTO;
use App\DAO\TapasDAO;

use OpenApi\Attributes as OA;

class TapasController {

    private $requestMethod;
    private $TapasId = null;

    public function __construct($requestMethod, $id) {
        $this->requestMethod = $requestMethod;
        $this->TapasId = $id;
    }

    public function processRequest() {

        $response = $this->notFoundResponse();

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->TapasId) {
                    $response = $this->getTapas($this->TapasId);
                } else {
                    $response= $this->getAllTapas();
                }
                break;
            case 'POST':
                if (empty($this->TapasId)) {
                    $response = $this->createTapas();
                }
                break;
            case 'PUT':
                $response= $this->updateTapas();
                break;
            case 'DELETE':
                $response= $this->deleteTapas($this->TapasId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'];
    }

    #[OA\Get(path: '/api/tapas', tags:["Tapas"], description:"give me all tapas")]
    #[OA\Response(response: '200', description: 'The data of all tapas')]
    public function getAllTapas() {
        $result= TapasDAO::getAllTapas();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    #[OA\Get(path: '/api/tapas/{id}', tags:["Tapas"], description: "give me tapas")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: 'The data of a tapas')]
    private function getTapas($id) {
        $result = TapasDAO::getTapasById($id);
        if ($result == null) {
            return $this->notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    
    /*

    @OA\Post(

     path="/products/save",

     tags={"Product"},

     summary="Post bulk products",

     description="Return bulk products",

     @OA\RequestBody(

      required=true,

      description="Bulk products Body",

      @OA\JsonContent(

      	@OA\Property(

     			property="products",

     		    @OA\Items(

                 @OA\Property(property="first_name", type="string"),

                 @OA\Property(property="last_name", type="string"),

                 @OA\Property(property="email", type="string"),

                 @OA\Property(property="phone", type="string"),

                 @OA\Property(property="file", type="file",format="file")

        		),

      	)

      )

    ),

    )
    */


    #[OA\Post(path: '/api/tapas', tags:["Tapas"], description:"create a tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'nom', type: 'string'),
                new OA\Property(property: 'prix', type: 'float'),
                new OA\Property(property: 'path_img', type: 'string', format: 'base64'),
                new OA\Property(property: 'description', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: '200', description:"")]
    private function createTapas() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["id"])) {
            return $this->unprocessableEntityResponse();
        }
        
        $Tapas= new TapasDTO();
        $Tapas->setnom($input["nom"]);
        $Tapas->setprix($input["prix"]);
        $Tapas->setpathimg($input["path_img"]);
        $Tapas->setdescription($input["description"]);
        $tempid= TapasDAO::AddTapas($Tapas);
        $Tapas->setid($tempid);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($Tapas);
        return $response;
    }

    #[OA\Put(path: '/api/tapas', tags:["Tapas"], description:"update a tapas tapas")]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'nom', type: 'string'),
                new OA\Property(property: 'prix', type: 'float'),
                new OA\Property(property: 'path_img', type: 'string'),
                new OA\Property(property: 'description', type: 'string')
            ]
        )
    )]
    #[OA\Response(response: '200', description: '')]
    private function updateTapas() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (empty($input["id"])) {
            return $this->unprocessableEntityResponse();
        }
        if (TapasDAO::getTapasById($input["id"]) !== null) { 
            $Tapas= new TapasDTO();
            $Tapas->setid($input["id"]);
            $Tapas->setnom($input["nom"]);
            $Tapas->setprix($input["prix"]);
            $Tapas->setpathimg($input["path_img"]);
            $Tapas->setdescription($input["description"]);
            TapasDAO::UpdateTapas($Tapas);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful update';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error: can not update Tapas with id ' . $input["id"];
        }
        $response['body'] = null;
        return $response;
    }

    #[OA\Delete(path: '/api/tapas/{id}', tags:["Tapas"], description:"destroy a tapas rip")]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID de l'élément à récupérer",
    )]
    #[OA\Response(response: '200', description: '')]
    private function deleteTapas($id) {
        
        if (TapasDAO::getTapasById($id) !== null) {
            TapasDAO::DeleteTapasById($id);
            $response['status_code_header'] = 'HTTP/1.1 200 Successful deletion';
        }else {
            $response['status_code_header'] = 'HTTP/1.1 404 Error on deletation Tapas with id '. $id;
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
