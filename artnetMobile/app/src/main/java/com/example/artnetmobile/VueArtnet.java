package com.example.artnetmobile;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.util.Log;
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
            Log.d(TAG, "Univers" + u.getNum() + " : " + u.getNom());

            TextView textView = new TextView(conteneur.getContext());

            String texte = "Univers n°" + u.getNum() + " : " + u.getNom();

            textView.setText(texte);
            textView.setTextSize(16);

            conteneur.addView(textView);
        }
    }
}
