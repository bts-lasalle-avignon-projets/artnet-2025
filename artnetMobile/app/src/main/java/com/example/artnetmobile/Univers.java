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

    private static final Vector<Univers> listeUnivers = new Vector<>();

    public Univers(String nom, int univers, String ip, String mac, int rssi) {
        this.nom = nom;
        this.univers = univers;
        this.ip = ip;
        this.mac = mac;
        this.rssi = rssi;
        Log.d(TAG, "Univers()" + " -> " + "Num univers : " + univers + " ; Nom : " + nom + " ; IP : " + ip + " ; MAC : " + mac + " ; RSSI : " + rssi);
        listeUnivers.add(this);
    }

    public String getNom() { return nom; }
    public int getNum() { return univers; }
    public String getIp() { return ip; }
    public String getMac() { return mac; }
    public int getRssi() { return rssi; }

    public static List<Univers> getListeUnivers() {
        return listeUnivers;
    }

    public static Univers rechercherUnivers(String nom) {
        for (Univers u : listeUnivers) {
            if(u.nom.equals(nom)) {
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
}

