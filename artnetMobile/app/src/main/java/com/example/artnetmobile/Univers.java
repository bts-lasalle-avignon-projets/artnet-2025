package com.example.artnetmobile;

import android.util.Log;

import java.util.ArrayList;
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

    private final List<EquipementDmx> equipements; // Liste propre à l'univers

    private static final Vector<Univers> listeUnivers = new Vector<>();

    public Univers(String nom, int univers, String ip, String mac, int rssi) {
        this.nom = nom;
        this.univers = univers;
        this.ip = ip;
        this.mac = mac;
        this.rssi = rssi;
        this.actif = false;
        this.nbEquipements = 0;
        this.equipements = new ArrayList<>(); // Initialisation de la liste

        Log.d(TAG, "Univers() -> Num univers : " + univers + " ; Nom : " + nom + " ; IP : " + ip + " ; MAC : " + mac + " ; RSSI : " + rssi);
        listeUnivers.add(this);
    }

    // --- Getters ---
    public String getNom() { return nom; }
    public int getNum() { return univers; }
    public String getIp() { return ip; }
    public String getMac() { return mac; }
    public int getRssi() { return rssi; }
    public boolean getActif() { return actif; }
    public int getNbEquipements() { return nbEquipements; }
    public List<EquipementDmx> getEquipements() { return equipements; }

    // --- Méthodes statiques ---
    public static List<Univers> getListeUnivers() {
        return listeUnivers;
    }

    public static List<Univers> getListeUniversActif() {
        List<Univers> actifs = new Vector<>();
        for (Univers u : listeUnivers) {
            if (u.getActif()) {
                actifs.add(u);
            }
        }
        return actifs;
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

    // --- Méthodes d'instance ---
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

    public void ajouterEquipement(EquipementDmx e) {
        equipements.add(e);
        nbEquipements++;
    }

    public void retirerEquipement(EquipementDmx e) {
        if (equipements.remove(e)) {
            nbEquipements--;
        }
    }

    public static void ajouterEquipementUnivers(EquipementDmx equipement) {
        Univers u = rechercherUniversNum(equipement.getUnivers());
        if (u != null) {
            u.ajouterEquipement(equipement);
        } else {
            Log.w(TAG, "Erreur : Aucun univers trouvé pour le numéro : " + equipement.getUnivers());
        }
    }

    public static EquipementDmx recupererEquipement(Univers u, String nom) {
        for (EquipementDmx e : u.getEquipements()) {
            if (e.getNom().equals(nom)) {
                return e;
            }
        }
        return null;
    }
}