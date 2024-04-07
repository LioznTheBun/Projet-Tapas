<?php
namespace App\DTO;
class categorieDTO implements \JsonSerializable{
    private $id, $nom;

    public function setid($newid){
        $this->id= $newid;
    }
    public function getid(){
        return $this->id;
    }
    public function setnom($newname){
        $this->nom= $newname;
    }
    public function getnom(){
        return $this->nom;
    }

    public function jsonSerialize() {
		return array(
			'id' => $this->id,
			'nom' => $this->nom,
		);
	}
}