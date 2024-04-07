/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DAO;

import java.util.ArrayList;

import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Repository;
import org.springframework.web.client.RestTemplate;

/**
 *
 * @author PC
 */
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import com.mycompany.bornetapashouse.Spring_base_client;

@Repository // Cette annotation indique à Spring qu'il s'agit d'un composant DAO
public class TapasDAO {

    private final static String BASE_WS_URL = Spring_base_client.getBaseURL(); //Remplacer par l'URL du WebService de Gabin


    public static TapasDTO getTapasById(int id) {
        String chaineId = String.valueOf(id);
        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/tapas/" + chaineId, String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        TapasDTO tapas = gson.fromJson(jsonResponse, TapasDTO.class);
        
        return tapas;
    }

    public static ArrayList<TapasDTO> getAllTapas() {

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/tapas", String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        java.lang.reflect.Type listType = new TypeToken<ArrayList<TapasDTO>>() {
        }.getType();
        ArrayList<TapasDTO> tapasList = gson.fromJson(jsonResponse, listType);

        return tapasList;
    }

    public static ArrayList<TapasDTO> getTapasByCategoryId(int id) {
        ArrayList<TapasDTO> tapasList = TapasDAO.getAllTapas();
        ArrayList<TapasDTO> tapasFilteredList = new ArrayList<TapasDTO>();
        for (TapasDTO tapaso : tapasList) {
            if (tapaso.getCategorieId() == id) {
                tapasFilteredList.add(tapaso);
            }
        }
        if ((tapasFilteredList) == null) {
            System.out.println("Aucun Tapas de cette catégorie trouvé !");
            return null;
        }
        return tapasFilteredList;
    }

    // ... Autres méthodes (update, delete, getById, getAll) adaptées de manière similaire
}
