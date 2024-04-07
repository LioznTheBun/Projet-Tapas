<?php
namespace App\DTO;

class Quantite_tapasDTO implements \JsonSerializable{
    private $tapas_id, $commande_id, $quantite;

    public function settapasid($newtapasid){
        $this->tapas_id= $newtapasid;
    }
    public function gettapasid(){
        return $this->tapas_id;
    }
    public function setcommandeid($newcommandeid){
        $this->commande_id= $newcommandeid;
    }
    public function getcommandeid(){
        return $this->commande_id;
    }
    public function setquantite($newquantite){
        $this->quantite= $newquantite;
    }
    public function getquantite(){
        return $this->quantite;
    }

    public function jsonSerialize() {
		return array(
			'idtapas' => $this->tapas_id,
			'idcommande' => $this->commande_id,
            'quantite' => $this->quantite,
		);
	}
}