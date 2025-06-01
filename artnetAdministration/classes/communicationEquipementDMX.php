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
class CommunicationEquipementDMX
{
    private string $nom;
    private string $type;
    private int $univers;
    private int $canalInitial;
    private int $nbCanaux;


    public function __construct(array $parametres)
    {
        if ($parametres != null) {
            $this->nom = $parametres['nom'];
            $this->type = $parametres['type'];
            $this->univers = $parametres['univers'];
            $this->canalInitial = $parametres['canalInitial'];
            $this->nbCanaux = $parametres['nbCanaux'];
        }
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getType(): int
    {
        return $this->type;
    }
    
    public function getUnivers(): string
    {
        return $this->univers;
    }

    public function getCanalInitial(): int
    {
        return $this->canalInitial;
    }

    public function getNombreCanaux(): int
    {
        return $this->nbCanaux;
    }
}