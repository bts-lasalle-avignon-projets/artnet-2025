package com.example.artnetmobile;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.util.Log;
import android.widget.Button;

public class Credits extends AppCompatActivity
{
    private static final String TAG = "_Credits";
    VueArtnet        vue = VueArtnet.getInstance();

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_credits);
        Log.d(TAG, "onCreate()");
        vue.initialiserNavbar(this);

        initialiserListener();
    }

    private void initialiserListener() {
        Button boutonProjet = findViewById(R.id.boutonGithubProjet);
        Button boutonCA = findViewById(R.id.boutonGithubCA);
        Button boutonVD = findViewById(R.id.boutonGithubVD);

        boutonProjet.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://github.com/bts-lasalle-avignon-projets/artnet-2025"));
            startActivity(intent);
        });

        boutonCA.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://github.com/aclement0"));
            startActivity(intent);
        });

        boutonVD.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://github.com/dvaudaine"));
            startActivity(intent);
        });
    }
}