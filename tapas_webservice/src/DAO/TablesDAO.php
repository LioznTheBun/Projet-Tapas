<?php
namespace App\DAO;
use App\tools\DatabaseLinker;
use App\DTO\TablesDTO;

class TablesDAO{

    public static function getAllTables(){
        $state = DataBaseLinker::getConnexion()->prepare("SELECT * FROM tables");
		$state->execute();
		$resultats = $state->fetchAll();
        $list= array();
        foreach ($resultats as $key) {
            $dto= new tablesDTO();
            $dto->setid($key["id"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getTableById($id){
        $state= DataBaseLinker::getConnexion()->prepare("select * from tables where id= :id");
        $state->bindValue(":id", $id);
        $state->execute();
        $result= $state->fetch();
        
        if ($result != null) {
            $dto= new tablesDTO();
            $dto->setid($result["id"]);
            return $dto;
        }
        return null;
    }
    public static function DeleteTableById($id){
        $state= DataBaseLinker::getConnexion()->prepare("delete from tables where id= :id");
        $state->bindValue(":id", $id);
        $state->execute();
    }

    public static function AddTable($dto){
        $state= DataBaseLinker::getConnexion()->prepare("insert into tables(id) value(?)");
        $state->execute(array($dto->getid()));
    }
}