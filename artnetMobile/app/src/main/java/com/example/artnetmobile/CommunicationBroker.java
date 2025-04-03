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
import org.eclipse.paho.client.mqttv3.MqttCallbackExtended;
import org.eclipse.paho.client.mqttv3.MqttConnectOptions;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;

public class CommunicationBroker
{
    // Constantes
    private static final String TAG = "CommunicationBroker";
    public static final String  TOPIC_ARTNET      = "Artnet/#"; // par défaut
    public static final int     BROKER_CONNECTE   = 0;
    public static final int     BROKER_DECONNECTE = 1;
    public static final int     BROKER_MESSAGE    = 2;
    public static final int     BROKER_ERREUR     = 3;

    // Attributs
    private String IP_BROKER;
    private int PORT_BROKER;
    Context context;
    public MqttAndroidClient mqttAndroidClient = null;
    String serveurUri;
    String clientId = "clientArtnetMobile";

    public CommunicationBroker(Context applicationContext, String ip, int port)
    {
        Log.d(TAG, "CommunicationBroker() -> IP: " + ip + ", Port: " + port);
        this.context = applicationContext;
        this.IP_BROKER = ip;
        this.PORT_BROKER = port;
        this.serveurUri = "tcp://" + IP_BROKER + ":" + PORT_BROKER;
        initialiser();
    }

    private void initialiser()
    {
        Log.d(TAG, "initialiser()");
        mqttAndroidClient = new MqttAndroidClient(context, serveurUri, clientId);
        mqttAndroidClient.setCallback(new MqttCallbackExtended() {
            @Override
            public void connectComplete(boolean b, String s)
            {
                Log.d(TAG, "connectComplete() -> " + s + " (" + b + ")");
            }

            @Override
            public void connectionLost(Throwable throwable)
            {
                Log.d(TAG, "connectionLost() -> " + throwable.toString());
            }

            @Override
            public void messageArrived(String topic, MqttMessage mqttMessage) throws Exception
            {
                Log.d(TAG,
                      "[MqttAndroidClient] messageArrived() " + topic + " -> " +
                        mqttMessage.toString());
            }

            @Override
            public void deliveryComplete(IMqttDeliveryToken iMqttDeliveryToken)
            {
                Log.d(TAG, "[MqttAndroidClient] deliveryComplete()");
            }
        });
    }

    public void connecter()
    {
        if(estConnecte())
            deconnecter();
        Log.d(TAG, "connecter() serveurUri -> " + serveurUri);
        MqttConnectOptions mqttConnectOptions = new MqttConnectOptions();
        mqttConnectOptions.setAutomaticReconnect(true);
        mqttConnectOptions.setCleanSession(false);

        // @todo mqttAndroidClient.connect()
    }

    public void deconnecter()
    {
        if(!estConnecte())
            return;
         // @todo mqttAndroidClient.disconnect()
    }

    public boolean estConnecte()
    {
        Log.d(TAG, "estConnecte() " + mqttAndroidClient.isConnected());
        return mqttAndroidClient.isConnected();
    }

    public boolean sabonner(String topic)
    {
        Log.d(TAG, "sabonner() topic -> " + topic);
        if(mqttAndroidClient == null || !mqttAndroidClient.isConnected())
        {
            return false;
        }
        if(topic.isEmpty())
            return false;

        // @todo mqttAndroidClient.subscribe()

        return true;
    }

    public boolean desabonner(String topic)
    {
        Log.d(TAG, "desabonner() topic -> " + topic);
        if(mqttAndroidClient == null || !mqttAndroidClient.isConnected())
        {
            return false;
        }
        if(topic.isEmpty())
            return false;

        // @todo mqttAndroidClient.unsubscribe()

        return true;
    }
}