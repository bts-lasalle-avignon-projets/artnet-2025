package com.example.artnetmobile;

import android.util.Log;

public class Artnet
{

    // Constantes
    private static final String TAG = "Artnet"; //!< TAG pour les logs (cf. Logcat)

    // Attributs
    private VueArtnet           vueArtnet;
    private ModuleDmxWifi       moduleDmxWifi;
    private CommunicationBroker communicationBroker;

    public Artnet(CommunicationBroker communicationBroker)
    {
        this.communicationBroker = communicationBroker;
        if(communicationBroker != null)
        {
            communicationBroker.connecter();
        }
    }

    public void ajouterEquipement()
    {
    }

    public void modifierCanal()
    {
    }

    public void envoyerCommande()
    {
    }
}
