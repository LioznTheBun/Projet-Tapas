package com.mycompany.bornetapashouse.DAO;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.mycompany.bornetapashouse.DTO.Categorie_tapasDTO;
import com.mycompany.bornetapashouse.Spring_base_client;
import java.util.ArrayList;
import org.springframework.http.ResponseEntity;
import org.springframework.web.client.RestTemplate;

public class Categorie_tapasDAO {

    private final static String BASE_WS_URL = Spring_base_client.getBaseURL();

    public static ArrayList<Categorie_tapasDTO> getCategorieTapasByCategorieId(int categorie_id) {
        String chaineId = String.valueOf(categorie_id);
        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/categorie_tapas/" + chaineId, String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();
        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        java.lang.reflect.Type listType = new TypeToken<ArrayList<Categorie_tapasDTO>>() {
        }.getType();
        ArrayList<Categorie_tapasDTO> categorieTapasList = gson.fromJson(jsonResponse, listType);
        return categorieTapasList;
    }

    public static ArrayList<Categorie_tapasDTO> getCategorieTapasByTapasId(int tapas_id) {
        String chaineId = String.valueOf(tapas_id);

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/categorie_tapas/" + chaineId, String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe Marque
        Gson gson = new Gson();
        Categorie_tapasDTO categorie = gson.fromJson(jsonResponse, Categorie_tapasDTO.class);
        java.lang.reflect.Type listType = new TypeToken<ArrayList<Categorie_tapasDTO>>() {
        }.getType();
        ArrayList<Categorie_tapasDTO> categorieTapasList = gson.fromJson(jsonResponse, listType);
        return categorieTapasList;
    }
}
