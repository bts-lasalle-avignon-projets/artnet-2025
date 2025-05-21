package com.example.artnetmobile;

import android.content.Context;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.widget.Toast;

import org.eclipse.paho.android.service.MqttAndroidClient;
import org.eclipse.paho.client.mqttv3.DisconnectedBufferOptions;
import org.eclipse.paho.client.mqttv3.IMqttActionListener;
import org.eclipse.paho.client.mqttv3.IMqttDeliveryToken;
import org.eclipse.paho.client.mqttv3.IMqttMessageListener;
import org.eclipse.paho.client.mqttv3.IMqttToken;
import org.eclipse.paho.client.mqttv3.MqttCallback;
import org.eclipse.paho.client.mqttv3.MqttCallbackExtended;
import org.eclipse.paho.client.mqttv3.MqttClient;
import org.eclipse.paho.client.mqttv3.MqttConnectOptions;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;
import org.eclipse.paho.client.mqttv3.persist.MemoryPersistence;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

public class CommunicationBroker
{
    /**
     * Constantes
     */
    private static final String TAG                   = "_CommunicationBroker";
    public static final String  TEST_IP_BROKER        = "192.168.1.104";
    public static final int     TEST_PORT_BROKER      = 1883;
    public static final String  TOPIC_ARTNET          = "Artnet/#"; // par défaut
    public static final int     BROKER_ERREUR         = 0;
    public static final int     BROKER_CONNECTE       = 1;
    public static final int     BROKER_DECONNECTE     = 2;
    public static final int     BROKER_MESSAGE_RECU   = 3;
    public static final int     BROKER_MESSAGE_ENVOYE = 4;

    /**
     * Attributs
     */
    private static CommunicationBroker    instance; // Singleton
    private Context context;
    private final String config = "artnet/config/#";
    Handler           handler    = null;
    public MqttClient mqttClient = null;
    String            serveurUri;
    String            clientId = MqttClient.generateClientId();

    private CommunicationBroker()
    {
        Log.d(TAG, "CommunicationBroker()");
        this.serveurUri = "tcp://" + TEST_IP_BROKER + ":" + TEST_PORT_BROKER;
        initialiser();
    }

    public static CommunicationBroker getInstance()
    {
        if(instance == null)
        {
            instance = new CommunicationBroker();
        }
        return instance;
    }

    public void setHandler(Handler handler)
    {
        this.handler = handler;
    }

    private void initialiser()
    {
        Log.d(TAG, "initialiser()");
        try
        {
            mqttClient = new MqttClient(serveurUri, clientId, new MemoryPersistence());
            mqttClient.setCallback(new MqttCallbackExtended() {
                @Override
                public void connectComplete(boolean b, String s)
                {
                    Log.d(TAG, "connectComplete() -> " + s + " (" + b + ")");
                    Message message = new Message();
                    message.what    = BROKER_CONNECTE;
                    if(handler != null)
                        handler.sendMessage(message);
                    sabonner(config);
                }

                @Override
                public void connectionLost(Throwable throwable)
                {
                    Log.d(TAG, "connectionLost() -> " + throwable.toString());
                    Message message = new Message();
                    message.what    = BROKER_DECONNECTE;
                    if(handler != null)
                        handler.sendMessage(message);
                }

                @Override
                public void messageArrived(String topic, MqttMessage mqttMessage)
                {
                    Log.d(TAG, "messageArrived() " + topic + " -> " + mqttMessage.toString());
                    Message message = new Message();
                    message.what    = BROKER_MESSAGE_RECU;
                    Bundle b        = new Bundle();
                    b.putString("topic", topic);
                    b.putString("message", mqttMessage.toString());
                    message.obj = b;
                    if(handler != null)
                        handler.sendMessage(message);
                }

                @Override
                public void deliveryComplete(IMqttDeliveryToken token)
                {
                    Log.d(TAG, "deliveryComplete()");
                    Message message = new Message();
                    message.what    = BROKER_MESSAGE_ENVOYE;
                    if(handler != null)
                        handler.sendMessage(message);
                }
            });
        }
        catch(MqttException e)
        {
            Log.e(TAG, "Erreur lors de l'initialisation du client MQTT", e);
            Message message = new Message();
            message.what    = BROKER_ERREUR;
            Bundle b        = new Bundle();
            b.putString("erreur", e.toString());
            if(handler != null)
                handler.sendMessage(message);
        }
    }

    public void connecter()
    {
        if(estConnecte())
            deconnecter();
        Log.d(TAG, "connecter() serveurUri -> " + serveurUri);

        Thread connexion = new Thread(new Runnable() {
            public void run()
            {
                MqttConnectOptions mqttConnectOptions = new MqttConnectOptions();
                mqttConnectOptions.setAutomaticReconnect(true);
                mqttConnectOptions.setCleanSession(true);
                try
                {
                    mqttClient.connect(mqttConnectOptions);
                }
                catch(MqttException e)
                {
                    Log.e(TAG, "Erreur de connexion MQTT", e);
                    Message message = new Message();
                    message.what    = BROKER_ERREUR;
                    Bundle b        = new Bundle();
                    b.putString("erreur", e.toString());
                    if(handler != null)
                        handler.sendMessage(message);
                }
            }
        });
        // Démarrage de la connexion
        connexion.start();
    }

    public void deconnecter()
    {
        if(!estConnecte())
            return;
        Log.d(TAG, "deconnecter() serveurUri -> " + serveurUri);
        Thread deconnexion = new Thread(new Runnable() {
            public void run()
            {
                try
                {
                    mqttClient.disconnect();
                }
                catch(MqttException e)
                {
                    Log.e(TAG, "Erreur de déconnexion MQTT", e);
                    Message message = new Message();
                    message.what    = BROKER_ERREUR;
                    Bundle b        = new Bundle();
                    b.putString("erreur", e.toString());
                    if(handler != null)
                        handler.sendMessage(message);
                }
            }
        });
        // Démarrage de la déconnexion
        deconnexion.start();
    }

    public boolean estConnecte()
    {
        Log.d(TAG, "estConnecte() " + (mqttClient != null && mqttClient.isConnected()));
        return mqttClient != null && mqttClient.isConnected();
    }

    public boolean sabonner(String topic)
    {
        Log.d(TAG, "sabonner() topic -> " + topic);
        if(mqttClient == null || !mqttClient.isConnected())
        {
            return false;
        }
        if(topic.isEmpty())
            return false;

        try
        {
            mqttClient.subscribe(topic);
            return true;
        }
        catch(MqttException e)
        {
            Log.e(TAG, "Erreur lors de l'abonnement MQTT", e);
            Message message = new Message();
            message.what    = BROKER_ERREUR;
            Bundle b        = new Bundle();
            b.putString("erreur", e.toString());
            if(handler != null)
                handler.sendMessage(message);
            return false;
        }
    }

    public boolean desabonner(String topic)
    {
        Log.d(TAG, "desabonner() topic -> " + topic);
        if(mqttClient == null || !mqttClient.isConnected())
        {
            return false;
        }
        if(topic.isEmpty())
            return false;

        try
        {
            mqttClient.unsubscribe(topic);
            return true;
        }
        catch(MqttException e)
        {
            Log.e(TAG, "Erreur lors du désabonnement MQTT", e);
            Message message = new Message();
            message.what    = BROKER_ERREUR;
            Bundle b        = new Bundle();
            b.putString("erreur", e.toString());
            if(handler != null)
                handler.sendMessage(message);
            return false;
        }
    }

    public void traiterMessageConfig(String topicMQTT, String messageMQTT)
    {
        try {
            JSONObject json = new JSONObject(messageMQTT);
            String nomUnivers = topicMQTT.substring("artnet/config/".length());
            int univers = json.getInt("univers");
            String ip = json.getString("ip");
            String mac = json.getString("mac");
            int rssi = json.getInt("rssi");

            Univers existant = Univers.rechercherUniversNom(nomUnivers);

            if (existant != null) {
                existant.mettreAJour(univers, ip, mac, rssi);
            } else {
                new Univers(nomUnivers, univers, ip, mac, rssi);
                if (context != null) {
                    Handler mainHandler = new Handler(context.getMainLooper());
                    mainHandler.post(() -> Toast.makeText(context, "Nouveau module détecté ! Nom : " + nomUnivers + " - Univers : " + univers, Toast.LENGTH_SHORT).show());
                }
            }
        } catch (JSONException e) {
            Log.e(TAG, "Erreur JSON : " + messageMQTT, e);
        }
    }

    public void envoyer(String topic, int num, String json) {
        Log.d(TAG, "envoyer()");

        try {
            String topicComplet = topic + "/" + num;
            MqttMessage message = new MqttMessage(json.getBytes());
            message.setQos(0);
            message.setRetained(false);

            mqttClient.publish(topicComplet, message);

            Message m = new Message();
            m.what = BROKER_MESSAGE_ENVOYE;
            if (handler != null) {
                handler.sendMessage(m);
            }
        } catch (MqttException e) {
            Log.e(TAG, "Erreur lors de l'envoi MQTT", e);
            Message m = new Message();
            m.what = BROKER_ERREUR;
            Bundle b = new Bundle();
            b.putString("erreur", e.toString());
            if (handler != null) {
                m.setData(b);
                handler.sendMessage(m);
            }
        }
    }

    public void initialiserContexte(Context context) {
        this.context = context.getApplicationContext();
    }

}