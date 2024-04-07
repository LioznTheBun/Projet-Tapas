<?php
namespace App\DAO;
use App\tools\DatabaseLinker;
use App\DTO\TapasDTO;
class TapasDAO{
    public static function getAllTapas(){
        $state= DataBaseLinker::getConnexion()->prepare("select * from tapas");
        $state->execute();
        $results= $state->fetchAll();
        $list= array();
        foreach ($results as $key) {
            $dto= new TapasDTO();
            $dto->setid($key["id"]);
            $dto->setnom($key["nom"]);
            $dto->setprix($key["prix"]);
            $dto->setpathimg($key["path_img"]);
            $dto->setdescription($key["description"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getTapasById($id){
        $state= DataBaseLinker::getConnexion()->prepare("select * from tapas where id= ?");
        $state->execute(array($id));
        $result= $state->fetch();
        if ($result != null) {
            $dto= new TapasDTO();
            $dto->setid($result["id"]);
            $dto->setnom($result["nom"]);
            $dto->setprix($result["prix"]);
            $dto->setpathimg($result["path_img"]);
            $dto->setdescription($result["description"]);
            return $dto;
        }
        return null;
    }

    public static function DeleteTapasById($id){
        $state= DataBaseLinker::getConnexion()->prepare("delete from tapas where id= ?");
        $state->execute(array($id));
    }

    public static function UpdateTapas($TapasDTO){
        $state= DataBaseLinker::getConnexion()->prepare("update tapas set nom= ?, prix= ?, path_img= ?, description= ? where id= ?");
        $state->execute(array($TapasDTO->getnom(), $TapasDTO->getprix(), $TapasDTO->getpathimg(), $TapasDTO->getdescription(), $TapasDTO->getid()));
    }

    public static function AddTapas($TapasDTO){
        $state= DataBaseLinker::getConnexion()->prepare("insert into tapas(nom, prix, path_img, description) value (?,?,?,?)");
        $state->execute(array($TapasDTO->getnom(), $TapasDTO->getprix(), $TapasDTO->getpathimg(), $TapasDTO->getdescription()));
        return  DataBaseLinker::getConnexion()->lastInsertId();
    }
}
