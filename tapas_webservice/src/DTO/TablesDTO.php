<?php
namespace App\DTO;

class TablesDTO implements \JsonSerializable{
    private $id;

    public function setid($newid){
        $this->id= $newid;
    }
    public function getid(){
        return $this->id;
    }

    public function jsonSerialize() {
		return array(
			'id' => $this->id,
		);
	}
}