package com.mycompany.bornetapashouse.DTO;

public class Categorie_tapasDTO {
    
    private int tapas_id;
    private int categorie_id;

    public Categorie_tapasDTO(int tapas_id, int categorie_id) {
        this.tapas_id = tapas_id;
        this.categorie_id = categorie_id;
    }

    public int getTapas_id() {
        return tapas_id;
    }

    public void setTapas_id(int tapas_id) {
        this.tapas_id = tapas_id;
    }

    public int getCategorie_id() {
        return categorie_id;
    }

    public void setCategorie_id(int categorie_id) {
        this.categorie_id = categorie_id;
    }
    
        @Override
    public String toString() {
        return "CategorieDTO{" +
                "tapas_id=" + tapas_id +
                ", categorie_id='" + categorie_id + '\'' +
                '}';
    }
}
