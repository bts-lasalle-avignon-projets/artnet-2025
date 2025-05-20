package com.example.artnetmobile;

public class Artnet
{
    private VueArtnet           vueArtnet;
    private CommunicationBroker communicationBroker;

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
