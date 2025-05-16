package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.text.InputType;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;

import org.json.JSONArray;

import java.util.ArrayList;
import java.util.List;
import java.util.Vector;

public class Configuration extends AppCompatActivity
{
    private static final String TAG = "_Configuration";
    VueArtnet        vue = VueArtnet.getInstance();
    CommunicationBroker communicationBroker = CommunicationBroker.getInstance();

    private List<EditText> listeCanaux = new ArrayList<>();

    private Spinner spinnerUnivers, spinnerEquipement;
    private LinearLayout conteneurCanaux;
    private ConstraintLayout layoutControle;

    private Univers univers;
    private EquipementDmx equipement;

    Button rechercherEquipement;
    Button boutonControlerEquipement;
    Button boutonEnvoyerValeurs;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_configuration);
        Log.d(TAG, "onCreate()");

        vue.initialiserNavbar(this);
        initialiserUI();
        initialiserListener();
    }

    private void controlerEquipement()
    {
        conteneurCanaux.removeAllViews();
        listeCanaux.clear();

        int nbCanaux = equipement.getNbCanaux();
        int adresse = equipement.getAdresseDMX();
        Vector<String> canaux = equipement.getCanaux();

        for (int i = 0; i < nbCanaux; i++) {
            LinearLayout blocCanal = new LinearLayout(Configuration.this);
            blocCanal.setOrientation(LinearLayout.VERTICAL);
            blocCanal.setPadding(16, 0, 16, 0);

            EditText canal = new EditText(Configuration.this);
            canal.setHint("Val. Canal " + (adresse + i));
            canal.setInputType(InputType.TYPE_CLASS_NUMBER);
            canal.setGravity(Gravity.CENTER);
            canal.setWidth(250);

            TextView description = new TextView(Configuration.this);
            description.setText((canaux != null && i < canaux.size()) ? canaux.get(i) : "Canal " + (adresse + i));
            description.setGravity(Gravity.CENTER);
            description.setTextSize(12);

            blocCanal.addView(canal);
            blocCanal.addView(description);
            conteneurCanaux.addView(blocCanal);

            listeCanaux.add(canal);
        }
    }

    private List<Integer> recupererValeurCanaux() {
        List<Integer> valeurs = new ArrayList<>();

        for (EditText canal : listeCanaux) {
            String texte = canal.getText().toString().trim();
            if(!texte.isEmpty())
            {
                try {
                    int val = Integer.parseInt(texte);
                    valeurs.add(val);
                }
                catch (NumberFormatException e)
                {
                    throw new RuntimeException(e);
                }
            } else {
                valeurs.add(0);
            }
        }
        return valeurs;
    }

    private String construireJson()
    {
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

    private void initialiserUI()
    {

        rechercherEquipement = findViewById(R.id.boutonRechercherEquipements);
        boutonControlerEquipement = findViewById(R.id.boutonControler);
        boutonEnvoyerValeurs = findViewById(R.id.boutonEnvoyerCommande);

        spinnerUnivers = findViewById(R.id.spinnerUnivers);
        spinnerEquipement = findViewById(R.id.spinnerEquipement);
        conteneurCanaux = findViewById(R.id.conteneurControleCanaux);
        layoutControle = findViewById(R.id.controlerEquipement);

        ArrayAdapter<Univers> adapterUnivers = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, Univers.getListeUniversActif());
        adapterUnivers.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerUnivers.setAdapter(adapterUnivers);
    }

    private void initialiserListener()
    {
        rechercherEquipement.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String nomUnivers = spinnerUnivers.getSelectedItem().toString().trim();
                univers = Univers.rechercherUniversNom(nomUnivers);

                ArrayAdapter<EquipementDmx> adapterEquipement = new ArrayAdapter<>(Configuration.this, android.R.layout.simple_spinner_item, Univers.getListeEquipement(univers));
                adapterEquipement.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spinnerEquipement.setAdapter(adapterEquipement);
            }
        });

        boutonControlerEquipement.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                equipement = (EquipementDmx) spinnerEquipement.getSelectedItem();
                vue.afficherControlerEquipement(layoutControle);
                controlerEquipement();
            }
        });

        boutonEnvoyerValeurs.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    String json = construireJson();
                    communicationBroker.envoyer("artnet/univers", univers.getNum(), json);
                } catch (Exception e) {
                    Log.e(TAG, "Erreur :", e);
                }
            }
        });
    }
}