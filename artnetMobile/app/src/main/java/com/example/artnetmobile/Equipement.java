package com.example.artnetmobile;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;

import android.widget.Spinner;
import android.widget.Toast;

import java.util.Vector;

public class Equipement extends AppCompatActivity {
    private static final String TAG = "_Equipement";
    VueArtnet vue = VueArtnet.getInstance();
    private CommunicationBroker communicationBroker = CommunicationBroker.getInstance();;

    private ConstraintLayout creationEquipement;
    private Spinner spinnerEquipement;
    private Spinner spinnerUnivers;
    private LinearLayout conteneurCanaux;

    private int compteurEquipement;

    Button ajouterNouvelEquipement;

    private String[] equipements = {"Scanner", "Pars", "Lyres", "Lasers", "Spots"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_equipement);
        Log.d(TAG, "onCreate()");
        vue.initialiserNavbar(this);

        initialiserUI();
        initialiserListeners();
        initialiserListenerCanaux();
    }

    private void creerNouvelEquipement() {
        compteurEquipement++;
        int numUnivers = -1;

        EditText editTextNom = findViewById(R.id.editNom);
        EditText editTextNbCanaux = findViewById(R.id.editNbCanaux);
        EditText editTextAdresse = findViewById(R.id.editAdresse);

        String nomEquipement = editTextNom.getText().toString();
        String typeSelectionne = spinnerEquipement.getSelectedItem().toString();

        Object selectedItem = spinnerUnivers.getSelectedItem();

        if (selectedItem != null) {
            String entreeSpinner = spinnerUnivers.getSelectedItem().toString();
            Univers universSelectionne = Univers.rechercherUniversNom(entreeSpinner);
            numUnivers = universSelectionne.getNum();
        }

        if (nomEquipement.isEmpty()) {
            nomEquipement = typeSelectionne + "-" + selectedItem.toString() + "-" + compteurEquipement;
        }

        int nbCanaux = Integer.parseInt(editTextNbCanaux.getText().toString());
        int adresse = Integer.parseInt(editTextAdresse.getText().toString());

        Vector<String> canaux = new Vector<>();
        for (int i = 0; i < conteneurCanaux.getChildCount(); i++) {
            EditText editText = (EditText) conteneurCanaux.getChildAt(i);
            String val = editText.getText().toString().trim();
            if (!val.isEmpty()) {
                canaux.add(val);
            } else {
                canaux.add("Canal " + (adresse + i));
            }
        }

        EquipementDmx equipement = new EquipementDmx(numUnivers, nomEquipement, typeSelectionne, nbCanaux, adresse, canaux);
        communicationBroker.sauvegarderEquipement(equipement);
        Toast.makeText(Equipement.this, "Equipement créé : " + nomEquipement + ", sur l'univers " + numUnivers, Toast.LENGTH_SHORT).show();

        editTextNom.setText("");
        editTextNbCanaux.setText("");
        editTextAdresse.setText("");
        spinnerEquipement.setSelection(0);
    }

    private void initialiserUI() {

        creationEquipement = findViewById(R.id.creationEquipement);
        conteneurCanaux = findViewById(R.id.conteneurCanaux);
        spinnerEquipement = findViewById(R.id.spinnerEquipement);
        spinnerUnivers = findViewById(R.id.spinnerUnivers);

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, equipements);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerEquipement.setAdapter(adapter);

        ArrayAdapter<Univers> adapterUnivers = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, Univers.getListeUnivers());
        adapterUnivers.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerUnivers.setAdapter(adapterUnivers);
    }

    private void initialiserListeners() {
        ajouterNouvelEquipement = findViewById(R.id.boutonAjoutEquipements);
        ajouterNouvelEquipement.setOnClickListener(v -> creerNouvelEquipement());
    }

    private void initialiserListenerCanaux() {
        EditText editTextNbCanaux = findViewById(R.id.editNbCanaux);

        editTextNbCanaux.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {}

            @Override
            public void afterTextChanged(Editable s) {
                conteneurCanaux.removeAllViews();
                if (!s.toString().isEmpty()) {
                    try {
                        int nbCanaux = Integer.parseInt(s.toString());
                        if (nbCanaux > 20) { nbCanaux = 20; }
                        for (int i = 0; i < nbCanaux; i++) {
                            EditText canal = new EditText(Equipement.this);
                            canal.setHint("Canal " + (i + 1));
                            canal.setInputType(InputType.TYPE_CLASS_TEXT);
                            canal.setGravity(Gravity.CENTER);
                            canal.setWidth(200);
                            conteneurCanaux.addView(canal);
                        }
                        ajouterNouvelEquipement.setVisibility(View.VISIBLE);
                    } catch (NumberFormatException e) {
                        Log.w(TAG, "Nombre de canaux invalide");
                    }
                } else {
                    ajouterNouvelEquipement.setVisibility(View.INVISIBLE);
                }
            }
        });
    }
}