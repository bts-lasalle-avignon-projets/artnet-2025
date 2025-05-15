package com.example.artnetmobile;

import android.util.Log;

import java.util.Vector;

public class EquipementDmx
{
    private static final String TAG = "_EquipementDmx";
    private static int         compteurID;
    private int                idEquipement;
    private int                univers;
    private String             nom;
    private String             type;
    private int                nbCanaux;
    private int                adresseDMX;
    private Vector<String> canaux = new Vector<>();

    public EquipementDmx(int univers, String nom, String type, int nbCanaux, int adresseDMX, Vector<String> canaux) {
        Log.d(TAG, "EquipementDmx()");
        this.idEquipement = ++compteurID;
        this.univers = univers;
        this.nom = nom;
        this.type = type;
        this.nbCanaux = nbCanaux;
        this.adresseDMX = adresseDMX;
        this.canaux = canaux;
        Univers.ajouterEquipementUnivers(this);
        Log.d(TAG, "ID : " + idEquipement + " - Univers : " + univers + " - Nom : " + nom + " - Type : " + type + " - Nombre canaux : " + nbCanaux + " - Premier canal : " + adresseDMX);
    }

    public Vector<String> getCanaux()
    {
        return canaux;
    }

    public void setIdEquipement(int idEquipement)
    {
        this.idEquipement = idEquipement;
    }

    public void setUnivers(int univers)
    {
        this.univers = univers;
    }

    public void setNom(String nom)
    {
        this.nom = nom;
    }

    public void setType(String type)
    {
        this.type = type;
    }

    public void setNbCanaux(int nbCanaux)
    {
        this.nbCanaux = nbCanaux;
    }

    public void setAdresseDMX(int adresseDMX)
    {
        this.adresseDMX = adresseDMX;
    }

    public void setCanaux(Vector<Equipement> canaux)
    {
    }

    public int getUnivers()
    {
        return univers;
    }

    public int getAdresseDMX()
    {
        return adresseDMX;
    }

    public int getIdEquipement()
    {
        return idEquipement;
    }

    public String getNom()
    {
        return nom;
    }

    public String getType()
    {
        return type;
    }

    public int getNbCanaux()
    {
        return nbCanaux;
    }

    public void configurerEquipement() {}
}