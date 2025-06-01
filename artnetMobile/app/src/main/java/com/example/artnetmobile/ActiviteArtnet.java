/**
 * @file ActiviteArtnet.java
 * @brief Déclaration de l'activité principale
 * @author Aymeric CLEMENT
 */

package com.example.artnetmobile;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.annotation.NonNull;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

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
    VueArtnet        vue = VueArtnet.getInstance();
    private TextView etatMQTT;
    private Button   boutonDemarrage;

    /**
     * Attributs
     */
    private CommunicationBroker communicationBroker;
    private Handler             handler = null;

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

        boutonDemarrage = findViewById(R.id.boutonDemarrage);
        boutonDemarrage.setEnabled(false);
        etatMQTT = findViewById(R.id.textViewEtatMQTT);
        etatMQTT.setText("Initialisation broker MQTT ...");

        initialiserHandler();
        initialiserCommunicationBroker();

        boutonDemarrage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v)
            {
                vue.afficherAccueil(ActiviteArtnet.this);
            }
        });
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

        if(communicationBroker != null)
            communicationBroker.setHandler(handler);
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
        Log.d(TAG, "initialiserCommunicationBroker()");
        communicationBroker = CommunicationBroker.getInstance();
        communicationBroker.setHandler(handler);
        etatMQTT.setText("Connexion broker MQTT ...");
        communicationBroker.connecter();
    }

    private void initialiserHandler()
    {
        this.handler = new Handler(this.getMainLooper()) {
            @Override
            public void handleMessage(@NonNull Message message)
            {
                // Log.d(TAG, "handleMessage() id message = " + message.what);
                // Log.d(TAG, "handleMessage() message = " + message.obj.toString());
                switch(message.what)
                {
                    case CommunicationBroker.BROKER_CONNECTE:
                        Log.d(TAG, "handleMessage() BROKER_CONNECTE");
                        etatMQTT.setText("Broker MQTT connecté");
                        communicationBroker.sabonner(CommunicationBroker.TOPIC_ARTNET);
                        boutonDemarrage.setEnabled(true);
                        break;
                    case CommunicationBroker.BROKER_DECONNECTE:
                        Log.d(TAG, "handleMessage() BROKER_DECONNECTE");
                        etatMQTT.setText("Broker MQTT déconnecté");
                        boutonDemarrage.setEnabled(false);
                        break;
                    case CommunicationBroker.BROKER_ERREUR:
                        Bundle bundle = (Bundle)message.obj;
                        String erreur = bundle.getString("erreur");
                        Log.d(TAG, "handleMessage() BROKER_ERREUR " + erreur);
                        break;
                }
            }
        };
    }
}