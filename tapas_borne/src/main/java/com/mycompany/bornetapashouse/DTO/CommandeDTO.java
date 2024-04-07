package com.mycompany.bornetapashouse.DTO;


import java.text.SimpleDateFormat;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.Date;


public class CommandeDTO {

    private int id;
    private int tableid;
    private float prix_total;
    private boolean confirmee;
    private String date;
    private static ArrayList<LiaisonDTO> quantityList;


    // Constructeur avec tous les champs
    public CommandeDTO(int tableid, float prix_total, boolean confirmee, String date) {
         //Reconversion automatique en sql par la suite
        this.tableid = tableid;
        this.prix_total = prix_total;
        this.confirmee = confirmee;
        this.date = date;
        this.quantityList = new ArrayList<LiaisonDTO>();
    }
    
    
   

    // Getters et setters
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getTableid() {
        return tableid;
    }

    public void setTableid(int tableid) {
        this.tableid = tableid;
    }

    public float getPrix_total() {
        return prix_total;
    }

    public void setPrix_total(float prix_total) {
        this.prix_total = prix_total;
    }

    public String getDate() {
        return date;
    }

    public boolean isConfirmee() {
        return confirmee;
    }

    public void setConfirmee(boolean confirmee) {
        this.confirmee = confirmee;
    }
    
    
    public ArrayList<LiaisonDTO> getQtList() {
        return quantityList;
    }
    
    static public void addToQtList(LiaisonDTO liaisonDTO) {
        quantityList.add(liaisonDTO);
    }

    
}
