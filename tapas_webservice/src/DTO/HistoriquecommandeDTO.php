<?php
namespace App\DTO;
class HistoriquecommandeDTO implements \JsonSerializable{
    private $id, $commandeid, $date, $statut;

    public function setid($newid){
        $this->id= $newid;
    }
    public function getid(){
        return $this->id;
    }
    public function setcommandeid($newcommandeid){
        $this->commandeid= $newcommandeid;
    }
    public function getcommandeid(){
        return $this->commandeid;
    }
    public function setdate($newdate){
        $this->date= $newdate;
    }
    public function getdate(){
        return $this->date;
    }
    public function setstatut($newstatut){
        $this->statut= $newstatut;
    }
    public function getstatut(){
        return $this->statut;
    }

    public function jsonSerialize() {
		return array(
			'id' => $this->id,
			'idcommande' => $this->commandeid,
            'date' => $this->date,
            'statut'=> $this->statut
		);
	}
}
