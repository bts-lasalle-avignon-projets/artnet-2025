package com.example.artnetmobile;

import android.content.Context;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import org.eclipse.paho.android.service.MqttAndroidClient;
import org.eclipse.paho.client.mqttv3.DisconnectedBufferOptions;
import org.eclipse.paho.client.mqttv3.IMqttActionListener;
import org.eclipse.paho.client.mqttv3.IMqttDeliveryToken;
import org.eclipse.paho.client.mqttv3.IMqttToken;
import org.eclipse.paho.client.mqttv3.MqttCallback;
import org.eclipse.paho.client.mqttv3.MqttCallbackExtended;
import org.eclipse.paho.client.mqttv3.MqttClient;
import org.eclipse.paho.client.mqttv3.MqttConnectOptions;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;
import org.eclipse.paho.client.mqttv3.persist.MemoryPersistence;

public class CommunicationBroker
{
    /**
     * Constantes
     */
    private static final String TAG               = "_CommunicationBroker";
    public static final String  TEST_IP_BROKER         = "192.168.1.104";
    public static final int     TEST_PORT_BROKER       = 1883;
    public static final String  TOPIC_ARTNET      = "Artnet/#"; // par défaut
    public static final int     BROKER_CONNECTE   = 0;
    public static final int     BROKER_DECONNECTE = 1;
    public static final int     BROKER_MESSAGE    = 2;
    public static final int     BROKER_ERREUR     = 3;

    /**
     * Attributs
     */

    private String IP_BROKER;
    private int PORT_BROKER;
    Context context;
    public MqttClient mqttClient = null;
    String serveurUri;
    String clientId = MqttClient.generateClientId();

    public CommunicationBroker(Context applicationContext)
    {
        Log.d(TAG, "CommunicationBroker()");
        this.context = applicationContext;
        this.serveurUri = "tcp://" + TEST_IP_BROKER + ":" + TEST_PORT_BROKER;
        initialiser();
        connecter();
    }

    private void initialiser() {
        Log.d(TAG, "initialiser()");
        try {
            mqttClient = new MqttClient(serveurUri, clientId, new MemoryPersistence());

            mqttClient.setCallback(new MqttCallback() {
                @Override
                public void connectionLost(Throwable throwable) {
                    Log.d(TAG, "connectionLost() -> " + throwable.toString());
                }

                @Override
                public void messageArrived(String topic, MqttMessage mqttMessage) {
                    Log.d(TAG, "[MqttClient] messageArrived() " + topic + " -> " + mqttMessage.toString());
                }

                @Override
                public void deliveryComplete(IMqttDeliveryToken token) {
                    Log.d(TAG, "[MqttClient] deliveryComplete()");
                }
            });

        } catch (MqttException e) {
            Log.e(TAG, "Erreur lors de l'initialisation du client MQTT", e);
        }
    }

    public void connecter()
    {
        if(estConnecte())
            deconnecter();
        Log.d(TAG, "connecter() serveurUri -> " + serveurUri);
        MqttConnectOptions mqttConnectOptions = new MqttConnectOptions();
        mqttConnectOptions.setAutomaticReconnect(true);
        mqttConnectOptions.setCleanSession(true);

        try {
            mqttClient.connect(mqttConnectOptions);
            Log.d(TAG, "Connecté au broker MQTT");
        } catch (MqttException e) {
            Log.e(TAG, "Erreur de connexion MQTT", e);
        }
    }

    public void deconnecter()
    {
        if(!estConnecte())
            return;

        try {
            mqttClient.disconnect();
            Log.d(TAG, "Déconnecté du broker MQTT");
        } catch (MqttException e) {
            Log.e(TAG, "Erreur lors de la déconnexion MQTT", e);
        }
    }

    public boolean estConnecte()
    {
        Log.d(TAG, "estConnecte() " + (mqttClient != null && mqttClient.isConnected()));
        return mqttClient != null && mqttClient.isConnected();
    }

    public boolean sabonner(String topic)
    {
        Log.d(TAG, "sabonner() topic -> " + topic);
        if (mqttClient == null || !mqttClient.isConnected()) {
            return false;
        }
        if (topic.isEmpty())
            return false;

        try {
            mqttClient.subscribe(topic);
            Log.d(TAG, "Abonné au topic : " + topic);
            return true;
        } catch (MqttException e) {
            Log.e(TAG, "Erreur lors de l'abonnement MQTT", e);
            return false;
        }
    }

    public boolean desabonner(String topic)
    {
        Log.d(TAG, "desabonner() topic -> " + topic);
        if (mqttClient == null || !mqttClient.isConnected()) {
            return false;
        }
        if (topic.isEmpty())
            return false;

        try {
            mqttClient.unsubscribe(topic);
            Log.d(TAG, "Désabonné du topic : " + topic);
            return true;
        } catch (MqttException e) {
            Log.e(TAG, "Erreur lors du désabonnement MQTT", e);
            return false;
        }
    }
}