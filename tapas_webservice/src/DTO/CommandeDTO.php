<?php
namespace App\DTO;
class CommandeDTO implements \JsonSerializable{
    private $id, $table_id, $prix_total, $confirmee, $date;

    public function setid($newid){
        $this->id= $newid;
    }
    public function getid(){
        return $this->id;
    }
    public function settableid($newtableid){
        $this->table_id= $newtableid;
    }
    public function gettableid(){
        return $this->table_id;
    }
    public function setprixtotal($newprix){
        $this->prix_total= $newprix;
    }
    public function getprixtotal(){
        return $this->prix_total;
    }
    public function setconfirmee($isconfim){
        $this->confirmee= $isconfim;
    }
    public function isconfirmee(){
        return $this->confirmee;
    }
    public function setdate($newdate){
        $this->date= $newdate;
    }
    public function getdate(){
        return $this->date;
    }

    public function jsonSerialize() {
		return array(
			'id' => $this->id,
			'tableid' => $this->table_id,
            'confirmee' => $this->confirmee,
            'prix_total'=> $this->prix_total,
            'date'=> $this->date
		);
	}
}