package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;

public class Equipement extends AppCompatActivity
{
    /**
     * Constantes
     */
    private static final String TAG = "_Equipement"; //!< TAG pour les logs (cf. Logcat)
    VueArtnet        vue = VueArtnet.getInstance();

    private ConstraintLayout creationEquipement;
    private ConstraintLayout configurationEquipement;
    private Spinner spinnerEquipement;
    private Spinner spinnerUnivers;
    private String[] equipements = {"Scanner", "Pars", "Lyres", "Lasers", "Spots"};

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_equipement);
        Log.d(TAG, "onCreate()");
        vue.initialiserNavbar(this);

        Button afficherVueCreation = findViewById(R.id.ajouterEquipement);
        Button afficherVueConfiguration = findViewById(R.id.configurerEquipement);
        Button ajouterNouvelEquipement = findViewById(R.id.boutonAjoutEquipements);

        creationEquipement = findViewById(R.id.creationEquipement);
        configurationEquipement = findViewById(R.id.configurationEquipement);
        spinnerEquipement = findViewById(R.id.spinnerEquipement);
        spinnerUnivers = findViewById(R.id.spinnerUnivers);

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, equipements);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerEquipement.setAdapter(adapter);

        ArrayAdapter<Univers> adapterUnivers = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, Univers.getListeUnivers());
        adapterUnivers.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerUnivers.setAdapter(adapterUnivers);

        afficherVueCreation.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                afficherVueEquipement();
            }
        });

        afficherVueConfiguration.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                afficherVueConfiguration();
            }
        });

        ajouterNouvelEquipement.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                creerNouvelEquipement();
            }
        });

    }

    private void afficherVueEquipement() {
        Log.d(TAG, "ajouterEquipement()");
        vue.afficherCreationEquipement(creationEquipement, configurationEquipement);
    }

    private void afficherVueConfiguration() {
        Log.d(TAG, "configurerEquipement()");
        vue.afficherConfigurationEquipement(creationEquipement, configurationEquipement);
    }

    private void creerNouvelEquipement() {
        EditText editTextNom = findViewById(R.id.editNom);
        String nomEquipement = editTextNom.getText().toString();

        String entreeSpinner = spinnerUnivers.getSelectedItem().toString();;
        Univers universSelectionne = Univers.rechercherUniversNom(entreeSpinner);
        int numUnivers = universSelectionne.getNum();

        String typeSelectionne = spinnerEquipement.getSelectedItem().toString();

        EditText editTextNbCanaux = findViewById(R.id.editNbCanaux);
        int nbCanaux = Integer.parseInt(editTextNbCanaux.getText().toString());

        EditText editTextAdresse = findViewById(R.id.editAdresse);
        int adresse = Integer.parseInt(editTextAdresse.getText().toString());

        /* @todo S'occuper du vector canaux */

        new EquipementDmx(numUnivers, nomEquipement, typeSelectionne, nbCanaux, adresse);

        editTextNom.setText("");
        editTextNbCanaux.setText("");
        editTextAdresse.setText("");
        spinnerEquipement.setSelection(0);
    }
}