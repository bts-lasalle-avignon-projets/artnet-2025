package com.example.artnetmobile;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import java.util.UUID;

public class Parametres extends AppCompatActivity {
    private static final String TAG = "_Parametres";
    private CommunicationBroker communicationBroker = CommunicationBroker.getInstance();
    VueArtnet vue = VueArtnet.getInstance();

    Button chargerEquipements;

    Boolean abonne = false;

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
        chargerEquipements.setText(
                abonne ? "Arrêter de charger les équipements" : getString(R.string.chargerBDDEquipements)
        );
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
        String topic = "artnet/bdd/equipements/lecture/#";
        if(!abonne)
        {
            abonne = true;
            communicationBroker.sabonner(topic);
            Toast.makeText(this, "Chargement des équipements...", Toast.LENGTH_SHORT).show();
            chargerEquipements.setText("Arrêter de charger les équipements");
        }
        else {
            abonne = false;
            communicationBroker.desabonner(topic);
            Toast.makeText(this, "Chargement arrêté !", Toast.LENGTH_SHORT).show();
            chargerEquipements.setText(getString(R.string.chargerBDDEquipements));
        }
    }
}
