<?php
class configuration
{
    private string $exporter;
    private string $importer;

    public function exporterConfiguration(): string     //json format
    {
        return $this->exporter;
    }

    public function importerConfiguration(): string     //json format
    {
        return $this->importer;
    }
}