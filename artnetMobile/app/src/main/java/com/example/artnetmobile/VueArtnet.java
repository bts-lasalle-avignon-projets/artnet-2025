package com.example.artnetmobile;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.constraintlayout.widget.ConstraintLayout;

public class VueArtnet
{
    private static final String TAG = "_VueArtnet";
    private static VueArtnet    instance; // Singleton

    private static final int RSSI_INCROYABLE = -30;
    private static final int RSSI_TRES_BON = -55;
    private static final int RSSI_ASSEZ_BON = -67;
    private static final int RSSI_MOYEN = -70;
    private static final int RSSI_PAS_BON = -80;

    private VueArtnet()
    {
        Log.d(TAG, "VueArtnet()");
    }

    public static VueArtnet getInstance()
    {
        if(instance == null)
        {
            instance = new VueArtnet();
        }
        return instance;
    }

    public void initialiserNavbar(Activity activite)
    {
        Log.d(TAG, "afficherNavbar()");
        Button accueil = activite.findViewById(R.id.boutonAccueil);
        Button equipements = activite.findViewById(R.id.boutonEquipements);
        Button configurer = activite.findViewById(R.id.boutonConfiguration);
        Button credits = activite.findViewById(R.id.boutonCredits);

        accueil.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                afficherAccueil(activite);
            }
        });

        equipements.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                afficherEquipement(activite);
            }
        });

        configurer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                afficherConfiguration(activite);
            }
        });

        credits.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                afficherCredits(activite);
            }
        });
    }

    public void afficherAccueil(Activity activite)
    {
        Log.d(TAG, "afficherAccueil()");
        Intent intent = new Intent(activite, Accueil.class);
        activite.startActivity(intent);
    }

    public void afficherEquipement(Activity activite)
    {
        Log.d(TAG, "afficherEquipement()");
        Intent intent = new Intent(activite, Equipement.class);
        activite.startActivity(intent);
    }

    public void afficherConfiguration(Activity activite)
    {
        Log.d(TAG, "afficherConfiguration()");
        Intent intent = new Intent(activite, Configuration.class);
        activite.startActivity(intent);
    }

    public void afficherCredits(Activity activite)
    {
        Log.d(TAG, "afficherCredits()");
        Intent intent = new Intent(activite, Credits.class);
        activite.startActivity(intent);
    }

    public void afficherUniversExistants(LinearLayout conteneurUnivers, LinearLayout conteneurDetails) {
        Log.d(TAG, "afficherUniversExistants()");
        conteneurUnivers.removeAllViews();

        for(Univers u : Univers.getListeUnivers()) {
            Log.d(TAG, "Univers " + u.getNum() + " : " + u.getNom());

            TextView textView = new TextView(conteneurUnivers.getContext());

            String texte = "Univers n°" + u.getNum() + " : " + u.getNom();

            textView.setText(texte);
            textView.setTextSize(16);
            textView.setPadding(0, 0, 0, 30);

            if (u.getActif())
                textView.setTextColor(Color.GREEN);
            else
                textView.setTextColor(Color.RED);


            conteneurUnivers.addView(textView);

            textView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Log.d(TAG, "onClick() -> Univers : " + u.getNom());
                    afficherDetailsUnivers(conteneurDetails, u);
                }
            });
        }
    }

    private String afficherRssi(int rssi)
    {
        if (rssi > RSSI_INCROYABLE) {
            return "Incroyable";
        } else if (rssi > RSSI_TRES_BON) {
            return "Très bon";
        } else if (rssi > RSSI_ASSEZ_BON) {
            return "Assez bon";
        } else if (rssi > RSSI_MOYEN) {
            return "Moyen";
        } else if (rssi > RSSI_PAS_BON) {
            return "Pas bon";
        } else {
            return "Extrêmement faible (inutilisable)";
        }
    }

    private String afficherActif(boolean abonne)
    {
        return abonne ? "Oui" : "Non";
    }

    private void afficherDetailsUnivers(LinearLayout conteneur, Univers u) {
        Log.d(TAG, "affichageDetailsUnivers()");
        conteneur.removeAllViews();

        ajouterLabel(conteneur, "Univers n°" + u.getNum(), 20);
        ajouterLabel(conteneur, "Nom : " + u.getNom(), 18);
        ajouterLabel(conteneur, "IP : " + u.getIp(), 16);
        ajouterLabel(conteneur, "MAC : " + u.getMac(), 16);
        ajouterLabel(conteneur, "RSSI : " + afficherRssi(u.getRssi()) + " (" + u.getRssi() + ")", 16);
        ajouterLabel(conteneur, "Topic actif ? : " + (u.getActif() ? "Oui" : "Non"), 16);
        ajouterLabel(conteneur, "Nombre d'équipements : " + u.getNbEquipements(), 16);

        Button boutonBasculementActif = new Button(conteneur.getContext());
        boutonBasculementActif.setText(u.getActif() ? "Désactiver le topic" : "Activer le topic");
        conteneur.addView(boutonBasculementActif);

        boutonBasculementActif.setOnClickListener(v -> {
            CommunicationBroker.getInstance().basculerEmissionUnivers(u);
            afficherDetailsUnivers(conteneur, u);
        });
    }

    private static void ajouterLabel(LinearLayout conteneur, String texte, int tailleTexte)
    {
        TextView textView = new TextView(conteneur.getContext());
        textView.setText(texte);
        textView.setTextSize(tailleTexte);
        conteneur.addView(textView);
    }

    public void afficherCreationEquipement(ConstraintLayout creationEquipement, ConstraintLayout configurationEquipement)
    {
        Log.d(TAG, "afficherCreationEquipement()");
        configurationEquipement.setVisibility(View.GONE);
        creationEquipement.setVisibility(View.VISIBLE);
    }

    public void afficherConfigurationEquipement(ConstraintLayout creationEquipement, ConstraintLayout configurationEquipement)
    {
        Log.d(TAG, "afficherConfigurationEquipement()");
        creationEquipement.setVisibility(View.GONE);
        configurationEquipement.setVisibility(View.VISIBLE);
    }

    public void afficherControlerEquipement(ConstraintLayout controlerEquipement) {
        Log.d(TAG, "afficherControlerEquipement()");
        controlerEquipement.setVisibility(View.VISIBLE);
    }
}