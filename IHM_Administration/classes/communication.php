<?php
class communication
{
    private string $nomClient;
    private int $ipClient;
    private int $portClient;
    private string $topic;
    
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