package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.View;

import android.widget.Button;
import android.widget.LinearLayout;



public class Accueil extends AppCompatActivity
{
    /**
     * Constantes
     */
    private static final String TAG = "_Accueil"; //!< TAG pour les logs (cf. Logcat)
    VueArtnet        vue = VueArtnet.getInstance();

    /**
     * Attributs
     */
    private CommunicationBroker communicationBroker;
    private Handler             handler = null;
    private LinearLayout conteneurUnivers;
    private LinearLayout conteneurDetails;

    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_accueil);
        Log.d(TAG, "onCreate()");

        initialiserHandler();
        initialiserCommunicationBroker();

        vue.initialiserNavbar(this);

        Button boutonRechercherUnivers = findViewById(R.id.boutonRechercherUnivers);
        conteneurUnivers = findViewById(R.id.conteneurUnivers);
        conteneurDetails = findViewById(R.id.conteneurDetails);

        boutonRechercherUnivers.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v)
            {
                vue.afficherUniversExistants(conteneurUnivers, conteneurDetails);
            }
        });
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

    private void initialiserCommunicationBroker()
    {
        Log.d(TAG, "initialiserCommunicationBroker()");
        communicationBroker = CommunicationBroker.getInstance();
        communicationBroker.setHandler(handler);
        if(!communicationBroker.estConnecte())
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
                Bundle bundle;
                switch(message.what)
                {
                    case CommunicationBroker.BROKER_CONNECTE:
                        Log.d(TAG, "handleMessage() BROKER_CONNECTE");
                        communicationBroker.sabonner(CommunicationBroker.TOPIC_ARTNET);
                        break;
                    case CommunicationBroker.BROKER_DECONNECTE:
                        Log.d(TAG, "handleMessage() BROKER_DECONNECTE");
                        break;
                    case CommunicationBroker.BROKER_MESSAGE_RECU:
                        bundle             = (Bundle)message.obj;
                        String topicMQTT   = bundle.getString("topic");
                        String messageMQTT = bundle.getString("message");
                        Log.d(TAG,
                              "handleMessage() BROKER_MESSAGE_RECU " + topicMQTT + " -> " +
                                messageMQTT);
                        traiterMessageMQTT(topicMQTT, messageMQTT);
                        break;
                    case CommunicationBroker.BROKER_ERREUR:
                        bundle        = (Bundle)message.obj;
                        String erreur = bundle.getString("erreur");
                        Log.d(TAG, "handleMessage() BROKER_ERREUR " + erreur);
                        break;
                }
            }
        };
    }

    private void traiterMessageMQTT(String topicMQTT, String messageMQTT)
    {
        if(topicMQTT.startsWith("artnet/config/"))
        {
            communicationBroker.traiterMessageConfig(topicMQTT, messageMQTT);
        }
    }
}