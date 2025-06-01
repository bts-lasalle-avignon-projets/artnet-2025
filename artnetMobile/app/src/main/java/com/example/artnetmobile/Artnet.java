package com.example.artnetmobile;

import android.util.Log;

public class Artnet
{
    private VueArtnet           vueArtnet;
    private CommunicationBroker communicationBroker;
    private static final String TAG = "_Artnet";

    public Artnet(CommunicationBroker communicationBroker)
    {
        this.communicationBroker = communicationBroker;
        this.vueArtnet = VueArtnet.getInstance();

        if(communicationBroker != null)
        {
            communicationBroker.connecter();
        }
    }

    public VueArtnet getVueArtnet() {
        return vueArtnet;
    }

    public void sauvegarderEquipement(EquipementDmx equipementDmx)
    {
        Log.d(TAG, "sauvegarderEquipement()");
        communicationBroker.sauvegarderEquipement(equipementDmx);
    }
}
