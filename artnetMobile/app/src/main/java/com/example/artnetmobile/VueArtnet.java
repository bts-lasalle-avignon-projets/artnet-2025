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

    private static final int INVISIBLE = 8;
    private static final int VISIBLE = 0;

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
            textView.setClickable(true);

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
        if (rssi > -30) {
            return "Incroyable";
        } else if (rssi > -55) {
            return "Très bon";
        } else if (rssi > -67) {
            return "Assez bon";
        } else if (rssi > -70) {
            return "D'accord";
        } else if (rssi > -80) {
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
        TextView affichageNum = new TextView(conteneur.getContext());
        String texteNum = "Univers n°" + u.getNum();
        affichageNum.setTextSize(20);
        affichageNum.setText(texteNum);

        TextView affichageNom = new TextView(conteneur.getContext());
        String texteNom = "Nom : " + u.getNom();
        affichageNom.setTextSize(18);
        affichageNom.setText(texteNom);

        TextView affichageIP = new TextView(conteneur.getContext());
        String texteIP = "IP : " + u.getIp();
        affichageIP.setTextSize(16);
        affichageIP.setText(texteIP);

        TextView affichageMAC = new TextView(conteneur.getContext());
        String texteMac = "MAC : " + u.getMac();
        affichageMAC.setTextSize(16);
        affichageMAC.setText(texteMac);

        TextView affichageRSSI = new TextView(conteneur.getContext());
        String texteRSSI = "RSSI : " + afficherRssi(u.getRssi()) + " (" + u.getRssi() + ")";
        affichageRSSI.setTextSize(16);
        affichageRSSI.setText(texteRSSI);

        TextView affichageActif = new TextView(conteneur.getContext());
        String texteActif = "Topic actif ? : " + afficherActif(u.getActif());
        affichageActif.setTextSize(16);
        affichageActif.setText(texteActif);

        TextView affichageNbEquipements = new TextView(conteneur.getContext());
        String texteNbEquipements = "Nombre d'équipements : " + u.getNbEquipements();
        affichageNbEquipements.setTextSize(16);
        affichageNbEquipements.setText(texteNbEquipements);

        Button boutonBasculementActif = new Button(conteneur.getContext());
        String texteBoutonBasculement;
        if (u.getActif())
            texteBoutonBasculement = "Désactiver le topic";
        else
            texteBoutonBasculement = "Activer le topic";

        boutonBasculementActif.setTextSize(16);
        boutonBasculementActif.setText(texteBoutonBasculement);
        affichageActif.setTextSize(16);
        conteneur.addView(affichageNum);
        conteneur.addView(affichageNom);
        conteneur.addView(affichageIP);
        conteneur.addView(affichageMAC);
        conteneur.addView(affichageRSSI);
        conteneur.addView(affichageActif);
        conteneur.addView(affichageNbEquipements);
        conteneur.addView(boutonBasculementActif);

        boutonBasculementActif.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                CommunicationBroker.getInstance().basculerEmissionUnivers(u);
                afficherDetailsUnivers(conteneur, u);
            }
        });
    }

    public void afficherCreationEquipement(ConstraintLayout creationEquipement, ConstraintLayout configurationEquiepemnt)
    {
        Log.d(TAG, "afficherCreationEquipement()");
        configurationEquiepemnt.setVisibility(INVISIBLE);
        creationEquipement.setVisibility(VISIBLE);
    }

    public void afficherConfigurationEquipement(ConstraintLayout creationEquipement, ConstraintLayout configurationEquiepemnt)
    {
        Log.d(TAG, "afficherConfigurationEquipement()");
        creationEquipement.setVisibility(INVISIBLE);
        configurationEquiepemnt.setVisibility(VISIBLE);
    }
}