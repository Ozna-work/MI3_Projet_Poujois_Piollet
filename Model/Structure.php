<?php

abstract class Structure
{
    //penser à limiter la taille des champs sur les inputs

    private int $_id;
    private string $_nom;
    private string $_rue;
    private string $_cp;
    private string $_ville;

    public function __construct(int $_id, string $_nom, string $_rue, string $_cp, string $_ville)
    {
        $this->_id = $_id;
        $this->_nom = $_nom;
        $this->_rue = $_rue;
        $this->_cp = $_cp;
        $this->_ville = $_ville;
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function setId(int $id): void
    {
        $this->_id = $id;
    }

    public function getNom(): string
    {
        return $this->_nom;
    }

    public function setNom(string $nom): void
    {
        $this->_nom = $nom;
    }

    public function getRue(): string
    {
        return $this->_rue;
    }

    public function setRue(string $rue): void
    {
        $this->_rue = $rue;
    }

    public function getCp(): string
    {
        return $this->_cp;
    }

    public function setCp(string $cp): void
    {
        $this->_cp = $cp;
    }

    public function getVille(): string
    {
        return $this->_ville;
    }

    public function setVille(string $ville): void
    {
        $this->_ville = $ville;
    }

}