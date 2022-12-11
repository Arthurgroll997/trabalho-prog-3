<?php
class Livro
{
    public $autor;
    public $titulo;
    public $genero;
    public $lido;
    public $dataConclusao;
    private $db;
    private $dateMgr;

    public function __construct($titulo, $genero, $autor, $lido, $dataConclusao, $db, $dateMgr)
    {
        $this->titulo = $titulo;
        $this->genero = $genero;
        $this->autor = $autor;
        $this->lido = $lido;
        $this->dataConclusao = $dataConclusao;
        $this->db = $db;
        $this->dateMgr = $dateMgr;
    }

    public function isConcluido()
    {
        return $this->lido;
    }

    public function getDataConclusao()
    {
        return $this->dataConclusao;
    }

    public function concluir()
    {
        $this->lido = true;
        $this->dataConclusao = $this->dateMgr->getCurrentDate();
    }
}
?>