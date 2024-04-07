/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DTO;

/**
 *
 * @author PC
 */
public class TablesDTO {
    private int id;

    // Constructeur avec tous les champs
    public TablesDTO(int id) {
        this.id = id;
    }

    // Getters et setters
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    @Override
    public String toString() {
        return "TablesDTO{" +
                "id=" + id +
                '}';
    }
}

