<?php


class Association extends Structure
{
    private int $_nbDonnateurs;


    public function __construct(int $_id, string $_nom, string $_rue, string $_cp, string $_ville, int $_nbDonnateurs)
    {
        parent::__construct($_id, $_nom, $_rue, $_cp, $_ville);
        $this->_nbDonnateurs = $_nbDonnateurs;
    }

    public function getNbDonnateurs(): int
    {
        return $this->_nbDonnateurs;
    }

    public function setNbDonnateurs(int $nbDonnateurs): void
    {
        $this->_nbDonnateurs = $nbDonnateurs;
    }


}