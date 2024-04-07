<?php
namespace App\DAO;
use App\tools\DatabaseLinker;
use App\DTO\categorieDTO;
class CategorieDAO{
    public static function getAllCategories(){
        $state= DataBaseLinker::getConnexion()->prepare("select * from categorie");
        $state->execute();
        $results= $state->fetchAll();
        $list= array();
        foreach ($results as $key) {
            $dto= new categorieDTO();
            $dto->setid($key["id"]);
            $dto->setnom($key["nom"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getCategorieById($id){
        $state= DataBaseLinker::getConnexion()->prepare("select * from categorie where id= :id");
        $state->bindValue(":id", $id);
        $state->execute();
        $result= $state->fetch();
        if ($result != null) {
            $dto= new categorieDTO();
            $dto->setid($result["id"]);
            $dto->setnom($result["nom"]);
            return $dto;
        }
    }

    public static function AddCategorie($dto){
        $state= DataBaseLinker::getConnexion()->prepare("insert into categorie(nom) value(?)");
        $state->execute(array($dto->getnom()));
    }

    public static function DeleteCategorieById($id){
        $state= DataBaseLinker::getConnexion()->prepare("delete from categorie where id= ?");
        $state->execute(array($id));
    }

    public static function UpdateCategorie($dto){
        $state= DataBaseLinker::getConnexion()->prepare("update categorie set nom= ? where id= ?");
        $state->execute(array($dto->getnom(), $dto->getid()));
    }
}