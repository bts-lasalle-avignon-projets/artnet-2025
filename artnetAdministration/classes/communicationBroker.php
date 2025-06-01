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
    private MqttClient $client;
    private array $messages;
    private bool $unSeulMessage;
    private bool $arret;

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
        $this->unSeulMessage = false;
        $this->arret = false;
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

    public function recevoirMessage($topic = null, ?int $timeout = null): bool
    {
        $this->unSeulMessage = true;
        return $this->recevoirMessages($topic, $timeout);
    }

    public function recevoirMessages($topic = null, ?int $timeout = null): bool
    {
        if (!$this->estConnecte())
            return false;
        try {
            $tempsDebut = microtime(true);

            while (!$this->arret) {
                $this->client->loopOnce($tempsDebut, true);
                if ($timeout !== null && !empty($timeout) && (microtime(true) - $tempsDebut) > $timeout) {
                    break;
                }
            }
            if ($topic != null) {
                // Pas de message reçu sur le topic ?
                if (!isset($this->messages[$topic])) {
                    return false;
                } else {
                    return true;
                }
            }
            // Pas de message reçu ?
            if (count($this->messages) == 0) {
                return false;
            }
            return true;
        } catch (MqttClientException $e) {
            return false;
        }
    }

    public function arreterReception()
    {
        $this->arret = true;
    }

    public function traiterMessage($topic, $message)
    {
        // @todo Traiter le message reçu
        //echo ("traiterMessage() message : \"" . $message . "\" sur le topic \"" . $topic . "\"" . PHP_EOL);
        if (!isset($this->messages[$topic])) {
            $this->messages[$topic] = array();
        }
        $this->messages[$topic][] = $message;
        if ($this->unSeulMessage) {
            $this->unSeulMessage = false;
            $this->arreterReception();
        }
    }

    public function getMessages($topic = null): array
    {
        // Retourne tous les messages pour le topic
        if ($topic != null) {
            $messages = $this->messages[$topic];
            unset($this->messages[$topic]);
            return $messages;
        }
        // Sinon retourne tous les messages
        $messages = $this->messages;
        $this->messages = array();
        foreach ($messages as $topic => $message) {
            unset($this->messages[$topic]);
        }
        return $messages;
    }

    public function getMessage($topic)
    {
        if (!isset($this->messages[$topic]))
            return null;
        // Retourne le premier message du topic
        if (count($this->messages[$topic]) >= 1) {
            $message = array_shift($this->messages[$topic]);
            // Si le tableau est vide, on supprime le topic
            if (count($this->messages[$topic]) == 0)
                unset($this->messages[$topic]);
            return $message;
        }
        return null;
    }
}
