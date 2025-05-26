package com.example.artnetmobile;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.Spinner;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import java.util.UUID;

public class Parametres extends AppCompatActivity {
    private static final String TAG = "_Parametres";
    private CommunicationBroker communicationBroker = CommunicationBroker.getInstance();
    VueArtnet vue = VueArtnet.getInstance();

    Button chargerEquipements;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_parametres);
        Log.d(TAG, "onCreate()");
        vue.initialiserNavbar(this);

        initialiserUI();
        initialiserListener();
    }

    private void initialiserUI()
    {
        chargerEquipements = findViewById(R.id.boutonChargementEquipements);
    }

    private void initialiserListener()
    {
        chargerEquipements.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                chargerBDDEquipements();
            }
        });
    }

    private void chargerBDDEquipements()
    {
        String topic = "artnet/bdd/equipements";
        String demande = "demande";
        String uuid = UUID.randomUUID().toString();
        Log.d("JSON", "PUBLISH (demande BDD équipement) --> " + topic + "/" + demande + " : " + uuid);
        communicationBroker.envoyer(topic, demande, uuid);
    }
}
