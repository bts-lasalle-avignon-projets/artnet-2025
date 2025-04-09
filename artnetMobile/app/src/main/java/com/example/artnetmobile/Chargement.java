package com.example.artnetmobile;

import android.content.Intent;
import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.util.Log;
import android.os.Bundle;
import android.os.Handler;
import android.widget.ImageView;
import android.widget.TextView;

/**
 * @class Chargement
 * @brief Page d'attente de connexion & chargement du BROKER
 */

public class Chargement extends AppCompatActivity
{
    // Constantes
    private static final String TAG = "_Chargement"; //!< TAG pour les logs (cf. Logcat)

    // Attributs
    private ImageView imageChargement;
    private TextView texteChargement;
    private TextView texteConnexionBroker;
    private TextView texteChargementEquipements;
    private TextView texteChargementScene;

    private int[] images = {R.drawable.chargement1, R.drawable.chargement2, R.drawable.chargement3, R.drawable.chargement4};
    private int index = 0;
    private Handler handler = new Handler();

    private CommunicationBroker communicationBroker;
    private Artnet artnet = null;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_chargement);
        Log.d(TAG, "onCreate()");

        imageChargement = findViewById(R.id.imageChargement);
        texteChargement = findViewById(R.id.texteChargement);
        texteConnexionBroker = findViewById(R.id.texteConnexionBroker);
        texteChargementEquipements = findViewById(R.id.texteChargementEquipements);
        texteChargementScene = findViewById(R.id.texteChargementScene);

        startImageRotation();

        initialiserCommunicationBroker();
        artnet = new Artnet(communicationBroker);

        boolean connexionBroker = verifierCommunicationBroker();
        boolean recuperationEquipements = verifierRecuperationEquipements();
        boolean recuperationScenes = verifierRecuperationScenes();

        if (connexionBroker && recuperationEquipements && recuperationScenes)
        {
            // @todo Passer par la classe VueArtnet
            Intent intent = new Intent(Chargement.this, Accueil.class);
            startActivity(intent);
        }
    }

    private void startImageRotation() {
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                index = (index + 1) % images.length;
                imageChargement.setImageResource(images[index]);

                handler.postDelayed(this, 1000);
            }
        }, 1000);
    }

    private void initialiserCommunicationBroker()
    {
        String ip = getIntent().getStringExtra("IP");
        String port = getIntent().getStringExtra("PORT");

        communicationBroker = new CommunicationBroker(this.getApplicationContext(), ip, Integer.parseInt(port));
    }

    private boolean verifierCommunicationBroker()
    {
        if (communicationBroker.estConnecte()) {
            Log.d(TAG, "Connexion au broker terminé");
            texteConnexionBroker.setText("Connecté au Broker !");
            texteChargementEquipements.setText(R.string.chargementEquipements);
            return true;
        } else {
            return false;
        }
    }

    private boolean verifierRecuperationEquipements()
    {
        // @todo : Récupération + Vérification équipements
        return true;
    }

    private boolean verifierRecuperationScenes() {
        // @todo (facultatif) : Récupération + Vérification scènes
        return true;
    }

}