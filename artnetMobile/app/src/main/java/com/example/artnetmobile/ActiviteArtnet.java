/**
 * @file ActiviteArtnet.java
 * @brief Déclaration de l'activité principale
 * @author Aymeric CLEMENT
 */

package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.util.Log;

/**
 * @class ActiviteArtnet
 * @brief L'activité principale
 */
public class ActiviteArtnet extends AppCompatActivity
{
    /**
     * Constantes
     */
    private static final String TAG = "_ActiviteArtnet"; //!< TAG pour les logs (cf. Logcat)

    /**
     * Ressources GUI
     */

    /**
     * Attributs
     */
    private CommunicationBroker communicationBroker;
    private Artnet artnet = null;

    /**
     * @brief Méthode appelée à la création de l'activité
     */
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_principale);
        Log.d(TAG, "onCreate()");

        initialiserCommunicationBroker();

        artnet = new Artnet(communicationBroker);
    }

    /**
     * @brief Méthode appelée au démarrage après le onCreate() ou un restart
     * après un onStop()
     */
    @Override
    protected void onStart()
    {
        super.onStart();
        Log.d(TAG, "onStart()");
    }

    /**
     * @brief Méthode appelée après onStart() ou après onPause()
     */
    @Override
    protected void onResume()
    {
        super.onResume();
        Log.d(TAG, "onResume()");
    }

    /**
     * @brief Méthode appelée après qu'une boîte de dialogue s'est affichée (on
     * reprend sur un onResume()) ou avant onStop() (activité plus visible)
     */
    @Override
    protected void onPause()
    {
        super.onPause();
        Log.d(TAG, "onPause()");
    }

    /**
     * @brief Méthode appelée lorsque l'activité n'est plus visible
     */
    @Override
    protected void onStop()
    {
        super.onStop();
        Log.d(TAG, "onStop()");
    }

    /**
     * @brief Méthode appelée à la destruction de l'application (après onStop()
     * et détruite par le système Android)
     */
    @Override
    protected void onDestroy()
    {
        super.onDestroy();
        Log.d(TAG, "onDestroy()");
    }

    private void initialiserCommunicationBroker()
    {
        communicationBroker = new CommunicationBroker(this.getApplicationContext());
    }
}