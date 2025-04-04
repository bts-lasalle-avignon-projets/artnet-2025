<?php
class Communication
{
    private string $nomClient;
    private int $ipClient;
    private int $portClient;
    private string $topic;

    public function __construct($parametres)
    {
        $this->nomClient = $parametres['nomClient'];
        $this->ipClient = $parametres['ipClient'];
        $this->portClient = $parametres['portClient'];
        $this->topic = $parametres['topic'];
    }

    public function getNomClient(): string
    {
        return $this->nomClient;
    }

    public function getIpClient(): int
    {
        return $this->ipClient;
    }

    public function getPortClient(): int
    {
        return $this->portClient;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }
}
