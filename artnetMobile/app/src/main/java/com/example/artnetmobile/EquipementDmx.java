package com.example.artnetmobile;

import java.util.Vector;

public class EquipementDmx {
    private int idEquipement;
    private int univers;
    private String nom;
    private String type;
    private int nbCanaux;
    private int adresseDMX;
    private Vector<Equipement> canaux = new Vector<>();

    public Vector<Equipement> getCanaux() {
        return canaux;
    }

    public void setIdEquipement(int idEquipement) {
        this.idEquipement = idEquipement;
    }

    public void setUnivers(int univers) {
        this.univers = univers;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public void setType(String type) {
        this.type = type;
    }

    public void setNbCanaux(int nbCanaux) {
        this.nbCanaux = nbCanaux;
    }

    public void setAdresseDMX(int adresseDMX) {
        this.adresseDMX = adresseDMX;
    }

    public void setCanaux(Vector<Equipement> canaux) {

    }

    public int getUnivers() {
        return univers;
    }

    public int getAdresseDMX() {
        return adresseDMX;
    }

    public int getIdEquipement() {
        return idEquipement;
    }

    public String getNom() {
        return nom;
    }

    public String getType() {
        return type;
    }

    public int getNbCanaux() {
        return nbCanaux;
    }

    public void configurerEquipement() {

    }
}