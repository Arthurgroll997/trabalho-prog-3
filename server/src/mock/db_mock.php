<?php
class DbMock
{
    private $livrosNaoLidos;
    private $livrosLidos;
    private $ultimoLivroLido;
    private $livroAtual;
    private $proxLivroParaLer;
    private $numLivrosNaoLidos;
    private $numLivrosLidos;

    public function __construct()
    {
        $this->livrosNaoLidos = null;
        $this->livrosLidos = null;
        $this->ultimoLivroLido = null;
        $this->livroAtual = null;
        $this->proxLivroParaLer = null;
        $this->numLivrosLidos = 0;
        $this->numLivrosNaoLidos = 0;
    }

    public function getLivrosNaoLidos()
    {
        return $this->livrosNaoLidos;
    }

    public function getLivrosLidos()
    {
        return $this->livrosNaoLidos;
    }

    public function getLivroAtual()
    {
        return $this->livroAtual;
    }

    public function getUltimoLivroLido()
    {
        return $this->ultimoLivroLido;
    }

    public function getProxLivroParaLer()
    {
        return $this->proxLivroParaLer;
    }

    public function getNumLivrosNaoLidos()
    {
        return $this->numLivrosNaoLidos;
    }

    public function getNumLivrosLidos()
    {
        return $this->numLivrosLidos;
    }
}
?>