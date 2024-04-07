/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DTO;

/**
 *
 * @author PC
 */
public class LiaisonDTO {
    private int tapas_id;
    private int commandeid;
    private int quantite;
    
    // Constructeur avec tous les champs
    public LiaisonDTO(int tapas_id, int quantite) {
        this.tapas_id = tapas_id;
        this.quantite = quantite;
    }

    // Getters et setters
    public int getTapas_id() {
        return tapas_id;
    }

    public void setTapas_id(int tapas_id) {
        this.tapas_id = tapas_id;
    }

    public int getCommandeid() {
        return commandeid;
    }

    public void setCommandeid(int commandeid) {
        this.commandeid = commandeid;
    }

    public int getQuantite() {
        return quantite;
    }

    public void setQuantite(int quantite) {
        this.quantite = quantite;
    }
    
    @Override
    public String toString() {
        return "LiaisonDTO{" +
                "tapas_id=" + tapas_id +
                ", commandeid=" + commandeid +
                '}';
    }
}
