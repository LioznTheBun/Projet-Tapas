package com.mycompany.bornetapashouse;

import Session.SessionCommande;
import com.mycompany.bornetapashouse.DAO.CategorieDAO;
import com.mycompany.bornetapashouse.DAO.CommandeDAO;
import com.mycompany.bornetapashouse.DAO.TapasDAO;
import com.mycompany.bornetapashouse.DTO.CategorieDTO;
import com.mycompany.bornetapashouse.DTO.CommandeDTO;
import com.mycompany.bornetapashouse.DTO.LiaisonDTO;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.Date;

public class MainApp {

    public static void main(String[] args) {
       

        //Zone de débug :

        
        ArrayList<TapasDTO> AllTapas = TapasDAO.getAllTapas();
        

       
        App.main(args);
    }
}
