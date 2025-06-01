package com.example.artnetmobile;

import android.os.Bundle;
import android.util.Log;
import android.widget.LinearLayout;
import android.widget.Spinner;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

public class Parametres extends AppCompatActivity {
    private static final String TAG = "_Parametres";
    VueArtnet vue = VueArtnet.getInstance();

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_parametres);
        Log.d(TAG, "onCreate()");
        vue.initialiserNavbar(this);
    }
}
