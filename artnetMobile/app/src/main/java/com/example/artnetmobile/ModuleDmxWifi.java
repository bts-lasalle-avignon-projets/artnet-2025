package com.example.artnetmobile;

import java.util.Vector;

public class ModuleDmxWifi
{
    private int                univers;
    private String             nomBoitier;
    private Vector<Equipement> equipement = new Vector<>();

    public boolean etablirConnexion()
    {
        return false;
    }

    public void controlerModule()
    {
    }

    public boolean ajouterEquipement(Equipement e)
    {
        return false;
    }

    public Vector<Equipement> getEquipement()
    {
        return equipement;
    }

    public boolean modifierValeur()
    {
        return false;
    }
}
