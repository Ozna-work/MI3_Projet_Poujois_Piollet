<?php


class Secteur
{
    private int $_id;
    private string $_varchar;

    /**
     * Secteur constructor.
     * @param int $_id
     * @param string $_varchar
     */
    public function __construct(string $_varchar)
    {
        $this->_varchar = $_varchar;
    }


    public function getId(): int
    {
        return $this->_id;
    }

    public function setId(int $id): void
    {
        $this->_id = $id;
    }

    public function getVarchar(): string
    {
        return $this->_varchar;
    }

    public function setVarchar(string $varchar): void
    {
        $this->_varchar = $varchar;
    }



}