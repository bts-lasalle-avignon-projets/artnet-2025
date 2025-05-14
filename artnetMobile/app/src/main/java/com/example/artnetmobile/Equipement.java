package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.util.Log;

public class Equipement extends AppCompatActivity
{
    /**
     * Constantes
     */
    private static final String TAG = "_Equipement"; //!< TAG pour les logs (cf. Logcat)
    VueArtnet        vue = VueArtnet.getInstance();

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_equipement);
        Log.d(TAG, "onCreate()");
        vue.initialiserNavbar(this);
    }
}