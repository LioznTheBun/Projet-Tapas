/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.bornetapashouse.DAO;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.mycompany.bornetapashouse.DTO.TablesDTO;
import com.mycompany.bornetapashouse.Spring_base_client;
import java.util.ArrayList;
import org.springframework.http.ResponseEntity;
import org.springframework.web.client.RestTemplate;

/**
 *
 * @author PC
 */
public class TablesDAO {
    
    private final static String BASE_WS_URL = Spring_base_client.getBaseURL();

    public static ArrayList<TablesDTO> getAllTables() {

        RestTemplate restTemplate = new RestTemplate();
        // appel du webservice pour GET
        ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/tables", String.class);
        // si pas d'erreur HTTP, on peut récupérer les données envoyées
        String jsonResponse = responseEntity.getBody();

        // utilisation de la librairie Gson pour désérialiser le JSON
        // permet de transformer une chaîne de caractères JSON en objet de la classe TableDTO
        Gson gson = new Gson();
        java.lang.reflect.Type listType = new TypeToken<ArrayList<TablesDTO>>() {
        }.getType();
        ArrayList<TablesDTO> tableList = gson.fromJson(jsonResponse, listType);

        return tableList;
    }

}
