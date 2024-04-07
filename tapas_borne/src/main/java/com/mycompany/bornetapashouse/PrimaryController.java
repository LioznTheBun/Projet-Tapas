package com.mycompany.bornetapashouse;

import Session.SessionCommande;
import com.mycompany.bornetapashouse.DTO.CommandeDTO;
import com.mycompany.bornetapashouse.DTO.LiaisonDTO;
import java.io.IOException;
import javafx.fxml.FXML;

public class PrimaryController {

    @FXML
    private void switchToSecondary() throws IOException {
        
        CommandeDTO actualCommande = new CommandeDTO(1, 0, false, "2002-12-12");
        SessionCommande.setActualCommande(actualCommande);
        App.setRoot("menu");
    }
    

}
