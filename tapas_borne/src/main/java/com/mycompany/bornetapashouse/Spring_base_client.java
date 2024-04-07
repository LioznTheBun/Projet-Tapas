package com.mycompany.bornetapashouse;

import com.google.gson.Gson;
import com.mycompany.bornetapashouse.DTO.TapasDTO;
import org.springframework.http.HttpEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.client.RestTemplate;

@SuppressWarnings("exports")
public class Spring_base_client {

	final static String BASE_WS_URL = "http://127.0.0.1:3000/api"; // Remplacer par l'URL du
																							// WebService de Gabin

	public static void getData(int id) {
		String chaineId = String.valueOf(id);

		RestTemplate restTemplate = new RestTemplate();
		// appel du webservice pour GET
		ResponseEntity<String> responseEntity = restTemplate.getForEntity(BASE_WS_URL + "/tapas/" + chaineId,
				String.class);
		// si pas d'erreur HTTP, on peut récupérer les données envoyées
		String jsonResponse = responseEntity.getBody();

		// utilisation de la librairie Gson pour désérialiser le JSON
		// permet de transformer une chaîne de caractères JSON en objet de la classe
		// Marque
		Gson gson = new Gson();
		System.out.println("Debug de get : " + jsonResponse);
		TapasDTO tapas = gson.fromJson(jsonResponse, TapasDTO.class);

		System.out.println("* GET reponse *");
		System.out.println(tapas.getNom());
	}

	public static void deleteData(int id) {
		String chaineId = String.valueOf(id);

		RestTemplate restTemplate = new RestTemplate();
		// appel du webservice pour DELETE
		restTemplate.delete(BASE_WS_URL + "/tapas/" + chaineId, String.class);
	}

	public static void postData(TapasDTO tapas) {
		RestTemplate restTemplate = new RestTemplate();
		String createURL = BASE_WS_URL + "/tapas";

		HttpHeaders headers = new HttpHeaders();
		headers.setContentType(MediaType.APPLICATION_JSON);

		// utilisation de la librairie Gson pour sérialiser un objet Java
		// permet de transformer un objet de la classe Marque en une chaîne de
		// caractères JSON
		Gson gson = new Gson();
		String jsonString = gson.toJson(tapas);
		
		System.out.println("Debug du post : " + jsonString);
		HttpEntity<String> request = new HttpEntity<>(jsonString, headers);
		System.out.println("debug 3 :" + createURL + request);
		// récupération de la marque créée avec les données envoyées
		TapasDTO createdTapas = restTemplate.postForObject(createURL, request, TapasDTO.class);

		System.out.println("* POST reponse *");

		if (createdTapas != null) {
			System.out.println(createdTapas.getNom());
		}
	}
        
        public static String getBaseURL() {
            return BASE_WS_URL;
        }
}
