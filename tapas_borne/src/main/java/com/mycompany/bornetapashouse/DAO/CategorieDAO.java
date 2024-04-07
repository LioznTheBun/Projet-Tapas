/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DAO;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.mycompany.bornetapashouse.DTO.CategorieDTO;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import com.mycompany.bornetapashouse.Spring_base_client;
import java.util.ArrayList;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Repository;
import org.springframework.web.client.RestTemplate;

/**
 *
 * @author PC
 */
@Repository // Cette annotation indique à Spring qu'il s'agit d'un composant DAO
public class CategorieDAO {

    private final static String BASE_WS_URL = Spring_base_client.getBaseURL(); //Remplacer par l'URL du WebService de Gabin


    public static CategorieDTO getCategorieById(int id) {
        String chaineId = String.valueOf(id);

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/categorie/" + chaineId, String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        CategorieDTO categorie = gson.fromJson(jsonResponse, CategorieDTO.class);
        
        return categorie;
    }

    public static ArrayList<CategorieDTO> getAllCategorie() {

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/categorie", String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        java.lang.reflect.Type listType = new TypeToken<ArrayList<CategorieDTO>>() {
        }.getType();
        ArrayList<CategorieDTO> categorieList = gson.fromJson(jsonResponse, listType);

        return categorieList;
    }


    // ... Autres méthodes (update, delete, getById, getAll) adaptées de manière similaire
}