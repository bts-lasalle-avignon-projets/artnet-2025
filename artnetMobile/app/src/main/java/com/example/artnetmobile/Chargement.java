package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.util.Log;

public class Chargement extends AppCompatActivity
{
    /**
     * Constantes
     */
    private static final String TAG = "_Chargement"; //!< TAG pour les logs (cf. Logcat)

    private CommunicationBroker communicationBroker;
    private Artnet artnet = null;


    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_chargement);
        Log.d(TAG, "onCreate()");
        initialiserCommunicationBroker();
    }

    private void initialiserCommunicationBroker()
    {
        communicationBroker = new CommunicationBroker(this.getApplicationContext());
    }
}