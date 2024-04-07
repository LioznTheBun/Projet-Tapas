/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DAO;

import java.util.ArrayList;

import org.springframework.http.ResponseEntity;
import org.springframework.web.client.RestTemplate;
import org.springframework.http.HttpEntity;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonParseException;
import com.google.gson.reflect.TypeToken;
import com.mycompany.bornetapashouse.DTO.CommandeDTO;
import com.mycompany.bornetapashouse.DTO.LiaisonDTO;
import com.mycompany.bornetapashouse.Spring_base_client;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import org.springframework.http.HttpEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;

/**
 *
 * @author PC
 */
public class CommandeDAO {

    private final static String BASE_WS_URL = Spring_base_client.getBaseURL(); //Remplacer par l'URL du WebService de Gabin

    public static CommandeDTO getCommandeById(int id) {
        String chaineId = String.valueOf(id);

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/commande/" + chaineId, String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        CommandeDTO commande = gson.fromJson(jsonResponse, CommandeDTO.class);
        return commande;
    }

    public static ArrayList<CommandeDTO> getAllCommandes() {

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/commande", String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // Formater la date avant de la passer à Gson
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Gson gson = new GsonBuilder()
                .registerTypeAdapter(Date.class, (JsonDeserializer<Date>) (json, type, jsonDeserializationContext) -> {
                    try {
                        return dateFormat.parse(json.getAsString());
                    } catch (ParseException e) {
                        throw new JsonParseException(e);
                    }
                })
                .create();

        java.lang.reflect.Type listType = new TypeToken<ArrayList<CommandeDTO>>() {
        }.getType();
        ArrayList<CommandeDTO> commandeslist = gson.fromJson(jsonResponse, listType);

        return commandeslist;
    }

    //Méthode qui sert a envoyer un objet Commande en base de données (ne se suffit pas tout seul pour des détails de commande complets)
    public static int envoyerCommande(CommandeDTO commande) {
        RestTemplate restTemplate = new RestTemplate();
        String createURL = BASE_WS_URL + "/commande";

        HttpHeaders headers = new HttpHeaders();
        headers.setContentType(MediaType.APPLICATION_JSON);

        Gson gson = new GsonBuilder()
                .setDateFormat("EEE, yyyy-MM-dd HH:mm:ss zzz").create();

        // Convertir la commande en JSON
        String jsonString = gson.toJson(commande);
        HttpEntity<String> request = new HttpEntity<>(jsonString, headers);

        try {
            ResponseEntity<CommandeDTO> responseEntity = restTemplate.postForEntity(createURL, request, CommandeDTO.class);

            if (responseEntity.getStatusCode() == HttpStatus.CREATED) {
                CommandeDTO createdCommande = responseEntity.getBody();
                createdCommande.setId(responseEntity.getBody().getId());
                System.out.println("Commande envoyée avec succès ! ID: " + createdCommande.getId());
                return createdCommande.getId();
            } else {
                System.err.println("Erreur lors de l'envoi de la commande. Statut HTTP : " + responseEntity.getStatusCodeValue());
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
        return 0; // En cas d'erreur, retourner null
    }

    //Méthode qui complète l'envoi de commandes en envoyant les quantités de types de tapas commandés 
    public static void envoyerQuantités(CommandeDTO commande, int commandeId) {
    ArrayList<LiaisonDTO> quantityList = commande.getQtList();

    // Créer une HashMap pour stocker les quantités par ID de tapas
    Map<Integer, Integer> quantitesParTapas = new HashMap<>();

    for (LiaisonDTO quantityObj : quantityList) {
        int tapasId = quantityObj.getTapas_id();
        int quantite = quantityObj.getQuantite();

        // Ajouter la quantité à l'ID de tapas correspondant
        quantitesParTapas.put(tapasId, quantitesParTapas.getOrDefault(tapasId, 0) + quantite);
    }

    RestTemplate restTemplate = new RestTemplate();
    String createURL = BASE_WS_URL + "/quantite_tapas";

    HttpHeaders headers = new HttpHeaders();
    headers.setContentType(MediaType.APPLICATION_JSON);

    Gson gson = new GsonBuilder().create();
    for (Map.Entry<Integer, Integer> entry : quantitesParTapas.entrySet()) {
        int tapasId = entry.getKey();
        int quantite = entry.getValue();

        // Créer un objet LiaisonDTO avec la quantité totale
        LiaisonDTO quantityObj = new LiaisonDTO(tapasId, quantite);
        quantityObj.setCommandeid(commandeId);

        // Convertir la commande en JSON
        String jsonData = gson.toJson(quantityObj);
        HttpEntity<String> request = new HttpEntity<>(jsonData, headers);

        try {
            ResponseEntity<LiaisonDTO> responseEntity = restTemplate.postForEntity(createURL, request, LiaisonDTO.class);
            if (responseEntity.getStatusCode() == HttpStatus.CREATED) {
                System.out.println("Quantité envoyée avec succès ! ID de la commande : " + quantityObj.getCommandeid());
            } else {
                System.err.println("Erreur lors de l'envoi de la commande. Statut HTTP : " + responseEntity.getStatusCodeValue());
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}


}
