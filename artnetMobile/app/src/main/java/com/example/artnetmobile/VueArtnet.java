package com.example.artnetmobile;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

public class VueArtnet
{
    private static final String TAG = "_VueArtnet";
    private static VueArtnet    instance; // Singleton

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

    public void afficherUniversExistants(LinearLayout conteneur) {
        Log.d(TAG, "afficherUniversExistants()");
        conteneur.removeAllViews();

        for(Univers u : Univers.getListeUnivers()) {
            Log.d(TAG, "Univers " + u.getNum() + " : " + u.getNom());

            TextView textView = new TextView(conteneur.getContext());

            String texte = "Univers n°" + u.getNum() + " : " + "\n"
                            + "\tNom : " + u.getNom() + "\n"
                            + "\tIp : " + u.getIp() + "\n"
                            + "\tMac : " + u.getMac() + "\n"
                            + "\tSignal : " + afficherRssi(u.getRssi()) + " (" + u.getRssi() + "dBm) \n"
                            + "\tTopic actif ? " + afficherAbonne(u.getActif());

            textView.setText(texte);
            textView.setTextSize(16);
            textView.setClickable(true);

            if (u.getActif()) {
                textView.setTextColor(Color.GREEN);
            } else {
                textView.setTextColor(Color.RED);
            }

            conteneur.addView(textView);

            textView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Log.d(TAG, "onClick() -> Univers : " + u.getNom());
                    CommunicationBroker.getInstance().basculerEmissionUnivers(u);
                    afficherUniversExistants(conteneur);
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

    private String afficherAbonne(boolean abonne) {
        return abonne ? "Oui" : "Non";
    }

}
