package com.example.artnetmobile;

import android.util.Log;

import java.util.List;
import java.util.Vector;

public class Univers {
    private static final String TAG = "Univers"; //!< TAG pour les logs (cf. Logcat)

    private final String nom;
    private int univers;
    private String ip;
    private String mac;
    private int rssi;
    private boolean actif;
    private int nbEquipements;

    private static final Vector<Univers> listeUnivers = new Vector<>();
    private static final Vector<EquipementDmx> listeEquipement = new Vector<>();

    public Univers(String nom, int univers, String ip, String mac, int rssi) {
        this.nom = nom;
        this.univers = univers;
        this.ip = ip;
        this.mac = mac;
        this.rssi = rssi;
        this.actif = false;
        this.nbEquipements = 0;
        Log.d(TAG, "Univers()" + " -> " + "Num univers : " + univers + " ; Nom : " + nom + " ; IP : " + ip + " ; MAC : " + mac + " ; RSSI : " + rssi);
        listeUnivers.add(this);
    }

    public String getNom() { return nom; }
    public int getNum() { return univers; }
    public String getIp() { return ip; }
    public String getMac() { return mac; }
    public int getRssi() { return rssi; }
    public boolean getActif() { return actif; }
    public int getNbEquipements() { return nbEquipements; }

    public static List<Univers> getListeUnivers() {
        return listeUnivers;
    }

    public static Univers rechercherUniversNom(String nom) {
        for (Univers u : listeUnivers) {
            if(u.nom.equals(nom)) {
                return u;
            }
        }
        return null;
    }

    public static Univers rechercherUniversNum(int num) {
        for (Univers u : listeUnivers) {
            if(u.univers == num) {
                return u;
            }
        }
        return null;
    }

    public void mettreAJour(int univers, String ip, String mac, int rssi) {
        this.univers = univers;
        this.ip = ip;
        this.mac = mac;
        this.rssi = rssi;
        Log.d(TAG, "mettreAJour()");
    }

    public void setActif(boolean actif) {
        this.actif = actif;
    }

    @Override
    public String toString() {
        return nom;
    }

    public void ajouterEquipement(Univers u) {
        u.nbEquipements++;
    }

    public void retirerEquipement(Univers u) {
        u.nbEquipements--;
    }

    public static int recupererNumUnivers(Univers u) {
        return u.getNum();
    }

    public static void ajouterEquipementUnivers(EquipementDmx equipement) {
        listeEquipement.add(equipement);

        Univers u = rechercherUniversNum(equipement.getUnivers());
        if (u != null) {
            u.nbEquipements++;
        } else {
            Log.w(TAG, "Erreur : Aucun univers trouvé pour le numéro : " + equipement.getUnivers());
        }
    }
}