<?php
namespace App\DAO;
use App\DTO\HistoriquecommandeDTO;
use App\tools\DatabaseLinker;
class HistoriquecommandeDAO{
    public static function getAllHistorique(){
        $state= DataBaseLinker::getConnexion()->prepare("select * from historiquecommandes");
        $state->execute();
        $results= $state->fetchAll();
        $list= array();
        foreach ($results as $key) {
            $dto= new HistoriquecommandeDTO();
            $dto->setid($key["id"]);
            $dto->setcommandeid($key["commande_id"]);
            $dto->setdate($key["date_commande"]);
            $dto->setstatut($key["statut"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getHistoriqueByCommandeId($commandeid){
        $state= DataBaseLinker::getConnexion()->prepare("select * from historiquecommandes where commande_id= ?");
        $state->execute(array($commandeid));
        $result= $state->fetch();
        if ($result != null) {
            $dto= new HistoriquecommandeDTO();
            $dto->setid($result["id"]);
            $dto->setcommandeid($result["commande_id"]);
            $dto->setdate($result["date_commande"]);
            $dto->setstatut($result["statut"]);
            return $dto;
        }
        return null;
    }

    public static function getHistoriqueById($id){
        $state= DataBaseLinker::getConnexion()->prepare("select * from historiquecommandes where id= ?");
        $state->execute(array($id));
        $result= $state->fetch();
        if ($result != null) {
            $dto= new HistoriquecommandeDTO();
            $dto->setid($result["id"]);
            $dto->setcommandeid($result["commande_id"]);
            $dto->setdate($result["date_commande"]);
            $dto->setstatut($result["statut"]);
            return $dto;
        }
        return null;
    }

    public static function AddHistoriqueCommande($dto){
        $state= DataBaseLinker::getConnexion()->prepare("insert into historiquecommandes(commande_id, statut) value (?,?)");
        $state->execute(array($dto->getcommandeid(), $dto->getstatut()));
    } 

    public static function DeleteHistoriqueCommandeById($id){
        $state= DataBaseLinker::getConnexion()->prepare("delete from historiquecommandes where id= ?");
        $state->execute(array($id));
    }

    public static function updateHistoriqueCommande($dto){
        $state= DataBaseLinker::getConnexion()->prepare("update historiquecommandes set commande_id=?, statut=?,date_commande= ? where id= ?");
        $state->execute(array($dto->getcommandeid(), $dto->getstatut(), $dto->getdate(), $dto->getid()));
    } 
}