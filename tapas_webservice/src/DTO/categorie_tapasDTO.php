<?php
namespace App\DTO;
class Categorie_tapasDTO implements \JsonSerializable
{
    private $tapas_id, $categorie_id;

    public function setTapasId($newtapasid)
    {
        $this->tapas_id = $newtapasid;
    }
    public function getTapasId()
    {
        return $this->tapas_id;
    }
    public function setCategorieId($newcategorieid)
    {
        $this->categorie_id = $newcategorieid;
    }
    public function getCategorieId()
    {
        return $this->categorie_id;
    }

    public function jsonSerialize()
    {
        return array(
            'tapas_id' => $this->tapas_id,
            'categorie_id' => $this->categorie_id,
        );
    }
}
