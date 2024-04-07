/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package Session;

import com.mycompany.bornetapashouse.DAO.TapasDAO;
import com.mycompany.bornetapashouse.DTO.CommandeDTO;
import com.mycompany.bornetapashouse.DTO.LiaisonDTO;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import javafx.scene.control.Label;
import javafx.scene.text.Font;

/**
 *
 * @author PC
 */
public class SessionCommande {
    
    public static CommandeDTO actualCommande;
   

    public static CommandeDTO getActualCommande() {
        return actualCommande;
    }

    public static void setActualCommande(CommandeDTO actualCommande) {
        SessionCommande.actualCommande = actualCommande;
    }
    
    
    
    

    
   
}
    
    
    
    

