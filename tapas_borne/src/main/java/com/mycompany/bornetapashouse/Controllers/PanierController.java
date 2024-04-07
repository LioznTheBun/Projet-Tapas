package com.mycompany.bornetapashouse.Controllers;

import Session.SessionCommande;
import static Session.SessionCommande.actualCommande;
import com.mycompany.bornetapashouse.App;
import com.mycompany.bornetapashouse.DAO.CommandeDAO;
import com.mycompany.bornetapashouse.DAO.TablesDAO;
import com.mycompany.bornetapashouse.DAO.TapasDAO;
import com.mycompany.bornetapashouse.DTO.CommandeDTO;
import com.mycompany.bornetapashouse.DTO.LiaisonDTO;
import com.mycompany.bornetapashouse.DTO.TablesDTO;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.ResourceBundle;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import static javafx.geometry.Pos.TOP_CENTER;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.VBox;
import javafx.scene.text.Font;
import javafx.stage.Modality;
import javafx.stage.Stage;

public class PanierController implements Initializable {

    @FXML
    VBox panierContainer;
    Label totalo = new Label();
    Label errorSelectTable = new Label();

    @FXML
    HBox totalobox;

    @FXML
    private HBox errobox;

    @FXML
    ComboBox<Integer> tableComboBox; // Utilisez Integer comme type

    @FXML
    private void switchToMenuFromPanier() throws IOException {
        App.setRoot("menu");
    }

    @FXML
    private void actualisePanier() throws IOException {
        App.setRoot("panier");
    }

    @FXML
    private void ConfirmIt() throws IOException {
        Integer selectedTableId = tableComboBox.getValue(); // Obtenez l'ID de la table

        //Cas ou la table est sélectionnée et le panier n'est pas vide
        if (selectedTableId != null && SessionCommande.getActualCommande().getQtList().get(0) != null ) {
            CommandeDTO commandeToSend = SessionCommande.getActualCommande();
            commandeToSend.setTableid(selectedTableId);
            int idRetour = CommandeDAO.envoyerCommande(commandeToSend);
            CommandeDAO.envoyerQuantités(commandeToSend, idRetour);
            showPopUp(selectedTableId, idRetour);
            App.setRoot("primary");
        } 
        
        //Cas ou le panier est vide
        else if (SessionCommande.getActualCommande().getQtList().isEmpty()) {
            errorSelectTable.setText("Votre panier est vide");
            errorSelectTable.setFont(new Font("Arial", 16));
            errobox.getChildren().add(errorSelectTable);
            errobox.setAlignment(Pos.CENTER);
            errorSelectTable.setStyle("-fx-text-fill: red;");
        }
        
        //Cas ou aucune table n'est selectionnée
        else {
            errorSelectTable.setText("Veuillez sélectionner une table.");
            errorSelectTable.setFont(new Font("Arial", 16));
            errobox.getChildren().add(errorSelectTable);
            errobox.setAlignment(Pos.CENTER);
            errorSelectTable.setStyle("-fx-text-fill: red;");
        }
    }

    private void showPopUp(int selectedTableId, int idRetour) {
        Stage popupwindow = new Stage();

        popupwindow.initModality(Modality.APPLICATION_MODAL);
        popupwindow.setTitle("Commande Reçu");

        Label label1 = new Label("Votre commande a bien été reçue et est en préparation :");

        Label numCommande = new Label("Commande n°" + idRetour);

        Label idTable = new Label("Table : " + selectedTableId);

        Button button1 = new Button("Ok");

        button1.setOnAction(e -> popupwindow.close());

        VBox layout = new VBox(10);

        layout.getChildren().addAll(label1, numCommande, idTable, button1);

        layout.setAlignment(Pos.CENTER);

        Scene scene1 = new Scene(layout, 300, 150);

        popupwindow.setScene(scene1);

        popupwindow.showAndWait();
    }

    @FXML
    public void initPan() throws FileNotFoundException, IOException {

        ArrayList<LiaisonDTO> actualQtList = SessionCommande.actualCommande.getQtList();
        Label panierLabel = new Label();

        //Gestion de la ComboBox des tables
        ArrayList<TablesDTO> allTables = TablesDAO.getAllTables();
        ArrayList<Integer> tableIds = new ArrayList<>();
        for (TablesDTO tablo : allTables) {
            tableIds.add(tablo.getId());
        }
        tableComboBox.getItems().addAll(tableIds);

        if (actualQtList.isEmpty()) {
            panierContainer.setAlignment(TOP_CENTER);
            panierLabel.setText("Votre panier est vide :/ N'hésitez pas a le remplir ;)");

        } else {
            float prixTotall = 0;
            Map<String, Map<String, Object>> quantitesEtPrixParTapas = new HashMap<>();

            // Calculer les quantités et les prix par id de tapas
            for (LiaisonDTO liaison : actualQtList) {
                int tapasId = liaison.getTapas_id();
                int quantite = liaison.getQuantite();
                TapasDTO tapasActual = TapasDAO.getTapasById(tapasId);
                String tapasNom = tapasActual.getNom();
                float tapasPrix = tapasActual.getPrix();

                if (quantitesEtPrixParTapas.containsKey(tapasNom)) {
                    Map<String, Object> quantiteEtPrix = quantitesEtPrixParTapas.get(tapasNom);
                    int ancienneQuantite = (int) quantiteEtPrix.get("quantite");
                    quantiteEtPrix.put("quantite", ancienneQuantite + quantite);
                } else {
                    // Créer une Map pour stocker la quantité et le prix
                    Map<String, Object> quantiteEtPrix = new HashMap<>();
                    quantiteEtPrix.put("quantite", quantite);
                    quantiteEtPrix.put("prix", tapasPrix);

                    // Ajouter la Map à l'id de tapas correspondant
                    quantitesEtPrixParTapas.put(tapasNom, quantiteEtPrix);
                }
            }

            // Afficher les quantités par id de tapas
            for (Map.Entry<String, Map<String, Object>> entry : quantitesEtPrixParTapas.entrySet()) {

                String tapasName = entry.getKey();
                Map<String, Object> quantitePrix = entry.getValue();
                int quantite = (int) quantitePrix.get("quantite");
                float prix = (float) quantitePrix.get("prix");
                float totalLine = (prix * quantite);
                prixTotall += totalLine;

                Label tapasLabel = new Label("- Tapa " + tapasName + " : " + quantite + " unité(s) | prix = " + totalLine + " €");
                tapasLabel.setFont(new Font("Arial", 14));

                Button deleteButton = new Button("Supprimer");
                deleteButton.setOnAction(e -> {
                    try {
                        deleteLine(tapasName);
                    } catch (IOException ex) {
                        Logger.getLogger(PanierController.class.getName()).log(Level.SEVERE, null, ex);
                    }
                });

                HBox labelBox = new HBox(tapasLabel);
                labelBox.setAlignment(Pos.CENTER_LEFT);
                labelBox.setMinWidth(335);

                HBox deleteButtonBox = new HBox(deleteButton);
                deleteButtonBox.setAlignment(Pos.CENTER_RIGHT);
                deleteButtonBox.setMinWidth(85);

                HBox tapasBox = new HBox(labelBox, deleteButtonBox);
                panierContainer.getChildren().add(tapasBox);
                panierContainer.setAlignment(Pos.TOP_CENTER);

            }

            totalo.setText("Prix Total : " + prixTotall + " €");
            totalo.setFont(new Font("Arial", 15));
            totalo.setStyle("-fx-underline: true;");

            totalobox.getChildren().add(totalo);
            SessionCommande.getActualCommande().setPrix_total(prixTotall);

        }
        panierContainer.getChildren().add(panierLabel);
        for (LiaisonDTO quantityact : SessionCommande.actualCommande.getQtList()) {
        }
    }

    @Override
    public void initialize(URL url, ResourceBundle rb
    ) {
        try {
            initPan();
        } catch (IOException ex) {
            Logger.getLogger(MenuController.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public void deleteLine(String tapasName) throws IOException {
        int idToRemove = 0;
        ArrayList<TapasDTO> allTapas = TapasDAO.getAllTapas();
        for (TapasDTO tapa : allTapas) {
            if (tapa.getNom().equals(tapasName)) {
                idToRemove = tapa.getId();

            }
        }
        System.out.println(idToRemove);
        ArrayList<LiaisonDTO> qtListo = SessionCommande.getActualCommande().getQtList();
        Iterator<LiaisonDTO> iterator = qtListo.iterator();
        while (iterator.hasNext()) {
            LiaisonDTO qt = iterator.next();
            if (qt.getTapas_id() == idToRemove) {
                iterator.remove();
            }
        }
        actualisePanier();
    }

}
