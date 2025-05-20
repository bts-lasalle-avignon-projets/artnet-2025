package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.SeekBar;
import android.widget.Spinner;
import android.widget.TextView;

import org.json.JSONArray;

import java.util.ArrayList;
import java.util.List;
import java.util.Vector;

public class Configuration extends AppCompatActivity {
    private static final String TAG = "_Configuration";
    VueArtnet vue = VueArtnet.getInstance();
    CommunicationBroker communicationBroker = CommunicationBroker.getInstance();

    private List<SeekBar> listeCanaux = new ArrayList<SeekBar>();

    private Spinner spinnerUnivers, spinnerEquipement;
    private LinearLayout conteneurCanaux;
    private ConstraintLayout layoutControle;

    private Univers univers;
    private EquipementDmx equipement;

    Button rechercherEquipement;
    Button boutonControlerEquipement;
    Button boutonEnvoyerValeurs;
    Button boutonReset;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_configuration);
        Log.d(TAG, "onCreate()");

        vue.initialiserNavbar(this);
        initialiserUI();
        initialiserListener();
    }

    private void controlerEquipement() {
        conteneurCanaux.removeAllViews();
        listeCanaux.clear();

        int nbCanaux = equipement.getNbCanaux();
        int adresse = equipement.getAdresseDMX();
        Vector<String> canaux = equipement.getCanaux();

        for (int i = 0; i < nbCanaux; i++) {
            LinearLayout blocCanal = new LinearLayout(Configuration.this);
            blocCanal.setOrientation(LinearLayout.VERTICAL);
            blocCanal.setPadding(16, 0, 16, 0);

            TextView valeurSlider = new TextView(Configuration.this);
            valeurSlider.setGravity(Gravity.CENTER);
            valeurSlider.setText("0");

            SeekBar slider = new SeekBar(Configuration.this);
            slider.setMax(255);
            slider.setProgress(0);
            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(500, 100); // largeur, hauteur en pixels
            slider.setLayoutParams(params);

            slider.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
                @Override
                public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
                    valeurSlider.setText(String.valueOf(progress));
                }

                @Override
                public void onStartTrackingTouch(SeekBar seekBar) {}

                @Override
                public void onStopTrackingTouch(SeekBar seekBar) {}
            });

            TextView description = new TextView(Configuration.this);
            description.setText((canaux != null && i < canaux.size()) ? canaux.get(i) : "Canal " + (adresse + i));
            description.setGravity(Gravity.CENTER);
            description.setTextSize(12);

            blocCanal.addView(valeurSlider);
            blocCanal.addView(slider);
            blocCanal.addView(description);
            conteneurCanaux.addView(blocCanal);

            listeCanaux.add(slider);
        }
    }

    private List<Integer> recupererValeurCanaux() {
        List<Integer> valeurs = new ArrayList<>();

        for (SeekBar slider : listeCanaux) {
            valeurs.add(slider.getProgress());
        }
        return valeurs;
    }

    private String construireJson() {
        JSONArray jsonArray = new JSONArray();
        List<Integer> valeurs = recupererValeurCanaux();
        int adresseBase = equipement.getAdresseDMX();

        for (int i = 0; i < valeurs.size(); i++) {
            try {
                org.json.JSONObject jsonCanal = new org.json.JSONObject();
                jsonCanal.put("canal", adresseBase + i);
                jsonCanal.put("valeur", valeurs.get(i));
                jsonArray.put(jsonCanal);
            } catch (Exception e) {
                Log.e(TAG, "Erreur : ", e);
            }
        }
        return jsonArray.toString();
    }

    private void initialiserUI() {

        rechercherEquipement = findViewById(R.id.boutonRechercherEquipements);
        boutonControlerEquipement = findViewById(R.id.boutonControler);
        boutonEnvoyerValeurs = findViewById(R.id.boutonEnvoyerCommande);
        boutonReset = findViewById(R.id.boutonReset);

        spinnerUnivers = findViewById(R.id.spinnerUnivers);
        spinnerEquipement = findViewById(R.id.spinnerEquipement);
        conteneurCanaux = findViewById(R.id.conteneurControleCanaux);
        layoutControle = findViewById(R.id.controlerEquipement);

        ArrayAdapter<Univers> adapterUnivers = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, Univers.getListeUniversActif());
        adapterUnivers.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerUnivers.setAdapter(adapterUnivers);
    }

    private void initialiserListener() {
        rechercherEquipement.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Object selectedItem = spinnerUnivers.getSelectedItem();

                if (selectedItem != null) {
                    univers = (Univers) selectedItem;
                    ArrayAdapter<EquipementDmx> adapterEquipement = new ArrayAdapter<>(Configuration.this, android.R.layout.simple_spinner_item, Univers.getListeEquipement(univers));
                    adapterEquipement.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    spinnerEquipement.setAdapter(adapterEquipement);
                }
            }
        });

        boutonControlerEquipement.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Object selectedItem = spinnerEquipement.getSelectedItem();

                if (selectedItem != null) {
                    equipement = (EquipementDmx) selectedItem;
                    vue.afficherControlerEquipement(layoutControle);
                    controlerEquipement();
                }
            }
        });

        boutonEnvoyerValeurs.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    String json = construireJson();
                    Log.d("JSON", json);
                    communicationBroker.envoyer("artnet/univers", univers.getNum(), json);
                } catch (Exception e) {
                    Log.e(TAG, "Erreur :", e);
                }
            }
        });

        boutonReset.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                for (SeekBar slider : listeCanaux) {
                    slider.setProgress(0);
                }
                String json = resetJSON();
                Log.d("JSON", json);
                communicationBroker.envoyer("artnet/univers", univers.getNum(), json);
            }
        });
    }

    private String resetJSON() {
        JSONArray jsonArray = new JSONArray();
        List<Integer> valeurs = recupererValeurCanaux();
        int adresseBase = equipement.getAdresseDMX();

        for (int i = 0; i < valeurs.size(); i++) {
            try {
                org.json.JSONObject jsonCanal = new org.json.JSONObject();
                jsonCanal.put("canal", adresseBase + i);
                jsonCanal.put("valeur", 0);
                jsonArray.put(jsonCanal);
            } catch (Exception e) {
                Log.e(TAG, "Erreur : ", e);
            }
        }
        return jsonArray.toString();
    }
}