package com.example.artnetmobile;

public class Univers {
    private final String nom;
    private final int univers;
    private final String ip;
    private final String mac;
    private final int rssi;

    public Univers(String nom, int univers, String ip, String mac, int rssi) {
        this.nom = nom;
        this.univers = univers;
        this.ip = ip;
        this.mac = mac;
        this.rssi = rssi;
    }

    public String getNom() { return nom; }
    public int getUnivers() { return univers; }
    public String getIp() { return ip; }
    public String getMac() { return mac; }
    public int getRssi() { return rssi; }
}

