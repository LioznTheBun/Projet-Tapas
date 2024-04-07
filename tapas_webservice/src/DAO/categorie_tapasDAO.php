<?php
namespace App\DAO;
use App\tools\DatabaseLinker;
use \App\DTO\Categorie_tapasDTO;
class Categorie_tapasDAO{
    public static function getAllCategorieTapas(){
        $state= DataBaseLinker::getConnexion()->prepare("select * from categorie_tapas");
        $state->execute();
        $results= $state->fetchAll();
        $list= array();
        foreach ($results as $key) {
            $dto= new Categorie_tapasDTO();
            $dto->setTapasId($key["tapas_id"]);
            $dto->setCategorieId($key["categorie_id"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getCategorieTapasByCategorieId($categorieid){
        $state= DataBaseLinker::getConnexion()->prepare("select * from categorie_tapas where categorie_id= ?");
        $state->execute(array($categorieid));
        $results= $state->fetchAll();
        $list= array();
        if ($results != null) {
            foreach ($results as $key) {
                $dto= new categorie_tapasDTO();
                $dto->setTapasId($key["tapas_id"]);
                $dto->setCategorieId($key["categorie_id"]);
                $list[]= $dto;
            }
            return $list;
        }
        return null;
    }

	public static function getCategorieTapasByTapasId($tapasid){
        $state= DataBaseLinker::getConnexion()->prepare("select * from categorie_tapas where tapas_id= ?");
        $state->execute(array($tapasid));
        $results= $state->fetchAll();
        $list= array();
        if ($results != null) {
            foreach ($results as $key) {
                $dto= new categorie_tapasDTO();
                $dto->setTapasId($key["tapas_id"]);
                $dto->setCategorieId($key["categorie_id"]);
                $list[]= $dto;
            }
            return $list;
        }
        return null;
    }

    public static function AddCategorieTapas($dto){
        $state= DataBaseLinker::getConnexion()->prepare("insert into categorie_tapas(tapas_id, categorie_id) value (?,?)");
        $state->execute(array($dto->getTapasId(), $dto->getCategorieId()));
    }

    public static function DeleteCategorieTapasByTapasId($tapasid){
        $state= DataBaseLinker::getConnexion()->prepare("delete from categorie_tapas where tapas_id= ?");
        $state->execute(array($tapasid));
    }

    public static function UpdateCategorieTapas($dto){
        $state= DataBaseLinker::getConnexion()->prepare("update categorie_tapas set categorie_id= ? where tapas_id= ?");
        $state->execute(array($dto->getquantite(), $dto->getcommandeid(), $dto->gettapasid()));
    }
}
