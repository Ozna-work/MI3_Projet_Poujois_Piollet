<?php


class Entreprise extends Structure
{

    private int $_nbActionnaires;

    public function __construct(int $_id, string $_nom, string $_rue, string $_cp, string $_ville, int $_nbActionnaires)
    {
        parent::__construct($_id, $_nom, $_rue, $_cp, $_ville);
        $this->_nbActionnaires = $_nbActionnaires;
    }


    public function getNbActionnaires(): int
    {
        return $this->_nbActionnaires;
    }

    public function setNbActionnaires(int $nbActionnaires): void
    {
        $this->_nbActionnaires = $nbActionnaires;
    }



}