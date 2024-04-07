/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DTO;

/**
 *
 * @author PC
 */
public class CategorieDTO {
    private int id;
    private String nom;


    // Constructeur avec tous les champs
    public CategorieDTO(int id, String nom) {
        this.id = id;
        this.nom = nom;
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

    @Override
    public String toString() {
        return "CategorieDTO{" +
                "id=" + id +
                ", nom='" + nom + '\'' +
                '}';
    }
}

