package com.example.artnetmobile;

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
    private int[] images = {R.drawable.chargement1, R.drawable.chargement2, R.drawable.chargement3, R.drawable.chargement4};
    private int index = 0;
    private Handler handler = new Handler();

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_chargement);
        Log.d(TAG, "onCreate()");

        imageChargement = findViewById(R.id.imageChargement);

        startImageRotation();
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
}