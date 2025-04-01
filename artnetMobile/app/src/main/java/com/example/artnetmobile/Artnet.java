package com.example.artnetmobile;

public class Artnet
{
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
