package com.mycompany.bornetapashouse.Controllers;

import static Session.SessionCommande.actualCommande;
import com.mycompany.bornetapashouse.App;
import com.mycompany.bornetapashouse.DAO.CategorieDAO;
import com.mycompany.bornetapashouse.DAO.Categorie_tapasDAO;
import com.mycompany.bornetapashouse.DAO.TapasDAO;
import com.mycompany.bornetapashouse.DTO.CategorieDTO;
import com.mycompany.bornetapashouse.DTO.Categorie_tapasDTO;
import com.mycompany.bornetapashouse.DTO.CommandeDTO;
import com.mycompany.bornetapashouse.DTO.LiaisonDTO;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.util.ArrayList;
import java.util.ResourceBundle;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.geometry.Pos;
import javafx.scene.control.Accordion;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.control.Spinner;
import javafx.scene.control.SpinnerValueFactory;
import javafx.scene.control.TitledPane;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Background;
import javafx.scene.layout.BackgroundImage;
import javafx.scene.layout.BackgroundPosition;
import javafx.scene.layout.BackgroundRepeat;
import javafx.scene.layout.BackgroundSize;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.VBox;

public class MenuController implements Initializable {

    @FXML
    private ScrollPane container;

    @FXML
    private VBox body;

    @FXML
    public void switchToPrimary() throws IOException {
        App.setRoot("primary");
    }

    @FXML
    private void switchToPanier() throws IOException {
        App.setRoot("panier");
    }

    @FXML
    public void initMenu() throws FileNotFoundException, IOException {
        //Initialisation du container principale
        VBox accor = new VBox();
        container.setFitToWidth(true);
        container.setContent(accor);
        ArrayList<CategorieDTO> listeCategorie = CategorieDAO.getAllCategorie();
        //Parcour de chaque catégorie
        for (CategorieDTO categorie : listeCategorie) {
            //Container de la catégorie
            VBox vboxMain = new VBox(10);

            ArrayList<Categorie_tapasDTO> listeTapas = Categorie_tapasDAO.getCategorieTapasByCategorieId(categorie.getId());
            //Boucle pour montrer chaque tapas de la catégorie
            for (Categorie_tapasDTO tapas_id : listeTapas) {

                HBox tapasContainer = affichageTapas(tapas_id);

                vboxMain.getChildren().add(tapasContainer);

            }
            TitledPane panel = new TitledPane(categorie.getNom(), vboxMain);
            panel.setExpanded(false);
            accor.getChildren().add(panel);

        }

    }

    public HBox affichageTapas(Categorie_tapasDTO tapas_id) {
        TapasDTO tapas = TapasDAO.getTapasById(tapas_id.getTapas_id());
        Label nom = new Label("Tapas " + tapas.getNom());
        Label description = new Label("Ingrédients : \n" + tapas.getDescript());
        description.setWrapText(true);
        Label prix = new Label("Prix : \n" + Float.toString(tapas.getPrix()) + "€");
        prix.setMinWidth(40);
        prix.setAlignment(Pos.CENTER);

        //Création d'imageView
        String patImg = tapas.getPathImg();
        ImageView cadreIllustration = creationImageView(patImg);

        //Bouton d'ajout au panier
        VBox optCommande = creationBoutonAjoutPanier(tapas.getId());

        //TODO ajouter EventHandler pour enregistrer l'id du tapas et la quantité dans le panier
        //mise en page de tout les éléments concernant le tapas actuel
        VBox tapasInfo = new VBox(10);
        tapasInfo.getChildren().addAll(nom, description);

        HBox tapasContainer = new HBox(5);
        tapasContainer.setHgrow(tapasInfo, Priority.ALWAYS);

        tapasContainer.getChildren().addAll(cadreIllustration, tapasInfo, prix, optCommande);
        tapasContainer.setAlignment(Pos.TOP_RIGHT);
        return tapasContainer;
    }

    public ImageView creationImageView(String patImg) {
        ImageView cadreIllustration = new ImageView();
        cadreIllustration.setPreserveRatio(true);
        cadreIllustration.setFitWidth(100);
        if (patImg.equalsIgnoreCase("null")) {
            patImg = "default.png";
        }
        Image imgSerie = new Image("file:imgTapas/" + patImg);
        cadreIllustration.setImage(imgSerie);
        return cadreIllustration;
    }

    public VBox creationBoutonAjoutPanier(int tapas_id) {
        Spinner<Integer> quantite = new Spinner<Integer>();
        SpinnerValueFactory value = new SpinnerValueFactory.IntegerSpinnerValueFactory(1, 10);
        quantite.setValueFactory(value);

        Button ajoutPanier = new Button("Ajouter au panier");
        EventHandler<MouseEvent> ajoutTapas = new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent e) {
                int nbrTapas = quantite.getValue();
                LiaisonDTO liaison = new LiaisonDTO(tapas_id, nbrTapas);
                actualCommande.addToQtList(liaison);
            }
        };

        ajoutPanier.addEventHandler(MouseEvent.MOUSE_CLICKED, ajoutTapas);
        VBox optCommande = new VBox(10);
        optCommande.setMinWidth(150);
        optCommande.getChildren().addAll(quantite, ajoutPanier);
        return optCommande;
    }

    @Override
    public void initialize(URL url, ResourceBundle rb) {
        try {
            initMenu();
        } catch (IOException ex) {
            Logger.getLogger(MenuController.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
