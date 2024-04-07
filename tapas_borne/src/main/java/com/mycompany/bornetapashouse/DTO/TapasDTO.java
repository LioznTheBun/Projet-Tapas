/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DTO;

/**
 *
 * @author PC
 */

public class TapasDTO {
    private int id;
    private String nom;
    private float prix;
    private String path_img;
    private int categorie_id;
    private String description;



    // Constructeur avec tous les champs
    public TapasDTO(int id, String nom, float prix, String pathImg, int categorieId, String descript) {
        this.id = id;
        this.nom = nom;
        this.prix = prix;
        this.path_img = pathImg;
        this.categorie_id = categorieId;
        this.description = descript;
    }

    // Getters et setters
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public float getPrix() {
        return prix;
    }

    public void setPrix(float prix) {
        this.prix = prix;
    }

    public String getPathImg() {
        return path_img;
    }

    public void setPathImg(String pathImg) {
        this.path_img = pathImg;
    }

    public int getCategorieId() {
        return categorie_id;
    }

    public void setCategorieId(int categorieId) {
        this.categorie_id = categorieId;
    }

    public String getDescript() {
        return description;
    }

    public void setDescript(String descript) {
        this.description = descript;
    }
    
    

    @Override
    public String toString() {
        return "TapasDTO{" +
                "id=" + id +
                ", nom='" + nom + '\'' +
                ", prix=" + prix +
                ", path_img='" + path_img + '\'' +
                ", categorie_id=" + categorie_id + '\'' +
                ", description=" + description +
                '}';
    }
}

