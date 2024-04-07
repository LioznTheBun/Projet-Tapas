<?php
namespace App\DAO;
use App\tools\DatabaseLinker;
use App\DTO\Quantite_tapasDTO;

class Quantite_tapasDAO{
    public static function getAllQuantiteTapas(){
        $state= DataBaseLinker::getConnexion()->prepare("select * from quantite_tapas");
        $state->execute();
        $results= $state->fetchAll();
        $list= array();
        foreach ($results as $key) {
            $dto= new Quantite_tapasDTO();
            $dto->settapasid($key["tapas_id"]);
            $dto->setcommandeid($key["commande_id"]);
            $dto->setquantite($key["quantite"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getQuantiteTapasByCommandeId($commandeid){
        $state= DataBaseLinker::getConnexion()->prepare("select * from quantite_tapas where commande_id= ?");
        $state->execute(array($commandeid));
        $result= $state->fetchall();
        $list= array();
        foreach ($result as $r) {
            $dto= new Quantite_tapasDTO();
            $dto->settapasid($r["tapas_id"]);
            $dto->setcommandeid($r["commande_id"]);
            $dto->setquantite($r["quantite"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function AddQuantiteTapas($dto){
        $state= DataBaseLinker::getConnexion()->prepare("insert into quantite_tapas(tapas_id, commande_id, quantite) value (?,?,?)");
        $state->execute(array($dto->gettapasid(), $dto->getcommandeid(), $dto->getquantite()));
    }

    public static function DeleteQuantiteTapasByCommandeId($commandeid){
        $state= DataBaseLinker::getConnexion()->prepare("delete from quantite_tapas where commande_id= ?");
        $state->execute(array($commandeid));
    }

    public static function DeleteQuantiteTapasByOneTapas($tapasid){
        $state= DataBaseLinker::getConnexion()->prepare("delete from quantite_tapas where tapas_id= ? limit 1");
        $state->execute(array($tapasid));
    }

    public static function UpdateQuantiteTapas($dto){
        $state= DataBaseLinker::getConnexion()->prepare("update quantite_tapas set commande_id= ?, quantite= ? where tapas_id= ?");
        $state->execute(array($dto->getquantite(), $dto->getcommandeid(), $dto->gettapasid()));
    }
}
