<?php

use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

/**
 * Lien : https://github.com/php-mqtt/client
 *
 * Installation :
 *
 * $ cd artnetAdministration/
 *
 * Si présence du fichier composer.lock, faire seulement :
 *
 * $ composer update
 *
 * Sinon :
 *
 * $ composer require php-mqtt/client
 */
class CommunicationBroker
{
    private string $hostname;
    private int $port;
    private string $username;
    private string $password;
    private array $messages;
    private MqttClient $client;

    public function __construct($parametres)
    {
        if ($parametres != null) {
            $this->hostname = $parametres['hostname'];
            $this->port = $parametres['port'];
        } else {
            $this->hostname = BROKER_MQTT_HOSTNAME;
            $this->port = BROKER_MQTT_PORT;
        }
        $this->messages = array();
        $this->client = new MqttClient($this->hostname, $this->port, 'artnetAdminsitration', MqttClient::MQTT_3_1, null, null);
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function connecter(): bool
    {
        if ($this->estConnecte())
            $this->deconnecter();

        try {
            $this->client->connect(null, true);
            return true;
        } catch (MqttClientException $e) {
            return false;
        }
    }

    public function estConnecte(): bool
    {
        return $this->client->isConnected();
    }

    public function deconnecter(): void
    {
        if (!$this->estConnecte())
            return;
        $this->client->disconnect();
    }

    public function publier(string $topic, string $message, $qos): bool
    {
        if (!$this->estConnecte())
            return false;
        try {
            $this->client->publish($topic, $message, $qos);
            return true;
        } catch (MqttClientException $e) {
            return false;
        }
    }

    public function souscrire(string $topic, $qos): bool
    {
        if (!$this->estConnecte())
            return false;
        try {
            $this->client->subscribe($topic, function ($topic, $message) {
                $this->traiterMessage($topic, $message);
            }, $qos);
            return true;
        } catch (MqttClientException $e) {
            return false;
        }
    }

    public function recevoirMessage(?int $timeout = null): bool
    {
        try {
            $this->client->loop(true, true, $timeout);
            return true;
        } catch (MqttClientException $e) {
            return false;
        }
    }

    public function traiterMessage($topic, $message)
    {
        // @todo Traiter le message reçu
        $this->messages[$topic] = $message;
        // un message reçu et on arrête la boucle
        $this->client->interrupt();
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getMessage($topic)
    {
        if (!isset($this->messages[$topic]))
            return null;
        $message = $this->messages[$topic];
        unset($this->messages[$topic]);
        return $message;
    }
}
