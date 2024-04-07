<?php
namespace App\DTO;

class TapasDTO implements \JsonSerializable{
    private $id, $nom, $prix, $path_img, $description;

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
    public function setprix($newprice){
        $this->prix= $newprice;
    }
    public function getprix(){
        return $this->prix;
    }
    public function setpathimg($newpath){
        $this->path_img= $newpath;
    }
    public function getpathimg(){
        return $this->path_img;
    }
    public function setdescription($newdescription){
        $this->description= $newdescription;
    }
    public function getdescription(){
        return $this->description;
    }

    public function jsonSerialize() {
		return array(
			'id' => $this->id,
			'nom' => $this->nom,
            'prix' => $this->prix,
            'path_img'=> $this->path_img,
            'description' => $this->description
		);
	}
}
