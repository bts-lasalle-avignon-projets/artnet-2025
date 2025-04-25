package com.example.artnetmobile;

import android.app.Activity;
import android.content.Intent;
import android.util.Log;

public class VueArtnet
{
    private static final String TAG = "_VueArtnet";
    private static VueArtnet instance; // Singleton

    private VueArtnet() {
        Log.d(TAG, "VueArtnet créée");
    }

    public static VueArtnet getInstance() {
        if (instance == null) {
            instance = new VueArtnet();
        }
        return instance;
    }

    public void afficherChargement(Activity activite)
    {
        Log.d(TAG, "afficherChargement()");
        Intent intent = new Intent(activite, Chargement.class);
        activite.startActivity(intent);
    }

    public void afficherAccueil()
    {
    }

    public void afficherEquipement()
    {
    }

    public void afficherConfiguration()
    {
    }

    public void afficherCredits()
    {
    }
}
