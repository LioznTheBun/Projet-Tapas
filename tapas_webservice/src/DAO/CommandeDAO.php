<?php
namespace App\DAO;
use App\DTO\CommandeDTO;
use App\tools\DatabaseLinker;
class CommandeDAO{
    public static function getAllCommandes(){
        $state= DataBaseLinker::getConnexion()->prepare("select * from commandes");
        $state->execute();
        $results= $state->fetchAll();
        $list= array();
        foreach ($results as $key) {
            $dto= new commandeDTO();
            $dto->setid($key["id"]);
            $dto->settableid($key["table_id"]);
            $dto->setprixtotal($key["prix_total"]);
            $dto->setconfirmee($key["isconfirmee"]);
            $dto->setdate($key["datecommande"]);
            $list[]= $dto;
        }
        return $list;
    }

    public static function getCommandeById($id){
        $state= DataBaseLinker::getConnexion()->prepare("select * from commandes where id= :id");
        $state->bindValue(":id", $id);
        $state->execute();
        $result= $state->fetch();
        if ($result != null) {
            $dto= new commandeDTO();
            $dto->setid($result["id"]);
            $dto->settableid($result["table_id"]);
            $dto->setprixtotal($result["prix_total"]);
            $dto->setconfirmee($result["isconfirmee"]);
            $dto->setdate($result["datecommande"]);
            return $dto;
        }
        return null;
    }

    public static function AddCommande($dto){
        $state = DataBaseLinker::getConnexion()->prepare("INSERT INTO commandes (table_id, prix_total) VALUES (?, ?)");
        $state->execute(array($dto->gettableid(), $dto->getprixtotal()));
    
        // Récupérer l'ID généré
        $lastInsertId = DataBaseLinker::getConnexion()->lastInsertId();
    
        return $lastInsertId;
    }

    public static function DeleteCommandeById($id){
        $state= DataBaseLinker::getConnexion()->prepare("delete from commandes where id= ?");
        $state->execute(array($id));
    }

    public static function UpdateCommande($dto){
        $state= DataBaseLinker::getConnexion()->prepare("update commandes set table_id= ?, prix_total= ?, isconfirmee= ? where id= ?");
        $state->execute(array($dto->gettableid(), $dto->getprixtotal(), $dto->isconfirmee(), $dto->getid()));
    }
}