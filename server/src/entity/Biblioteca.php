<?php
class Biblioteca
{
    private $livrosNaoLidos;
    private $livrosLidos;
    private $ultimoLivroLido;
    private $livroAtual;
    private $proxLivroParaLer;
    private $numLivrosNaoLidos;
    private $numLivrosLidos;
    public $db;
    public $dateMgr;

    public function __construct($db, $dateMgr)
    {
        $this->db = $db;
        $this->dateMgr = $dateMgr;

        $this->livrosNaoLidos = $this->db->getLivrosNaoLidos();
        $this->livrosLidos = $this->db->getLivrosLidos();
        $this->ultimoLivroLido = $this->db->getUltimoLivroLido();
        $this->livroAtual = $this->db->getLivroAtual();
        $this->proxLivroParaLer = $this->db->getProxLivroParaLer();
        $this->numLivrosLidos = $this->db->getNumLivrosLidos();
        $this->numLivrosNaoLidos = $this->db->getNumLivrosNaoLidos();
    }

    public function getLivrosNaoLidos()
    {
        return $this->livrosNaoLidos;
    }

    public function getLivroAtual()
    {
        return $this->livroAtual;
    }

    public function getLivrosLidos()
    {
        return $this->livrosLidos;
    }

    public function getUltimoLivroLido()
    {
        return $this->ultimoLivroLido;
    }

    public function getProximoLivroParaLer()
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

    public function addLivro($livro)
    {
        if ($this->livrosNaoLidos == null)
            $this->livrosNaoLidos = [$livro];
        else
            array_push($this->livrosNaoLidos, $livro);

        $this->proxLivroParaLer = count($this->livrosNaoLidos) > 1 ? $this->livrosNaoLidos[1] : null;
        $this->livroAtual = $this->livrosNaoLidos[0];
        $this->numLivrosNaoLidos++;
    }

    public function addLivros($livros)
    {
        foreach ($livros as $livro)
            $this->addLivro($livro);
    }

    public function concluirLivroAtual()
    {
        // Se não existir nenhum livro atual, simplesmente ignorar a chamada
        if ($this->livroAtual == null)
            return;

        // Concluir livro atual
        $this->livroAtual->concluir();

        // Adicionar livro atual na lista de livros concluídos
        if ($this->livrosLidos == null)
            $this->livrosLidos = [$this->livroAtual];
        else
            array_push($this->livrosLidos, $this->livroAtual);

        $this->numLivrosLidos++;

        // Definir livro atual como último livro lido

        $this->ultimoLivroLido = $this->livroAtual;

        // Remover livro atual da lista de livros não lidos

        $this->livrosNaoLidos = array_values(array_filter($this->livrosNaoLidos, function ($livro) {
            return $livro !== $this->livroAtual;
        }));

        $this->numLivrosNaoLidos--;

        // Atribuir o próximo livro atual e o próximo livro que será lido após o atual

        $this->livroAtual = count($this->livrosNaoLidos) > 0 ? $this->livrosNaoLidos[0] : null;
        $this->proxLivroParaLer = count($this->livrosNaoLidos) > 1 ? $this->livrosNaoLidos[1] : null;
    }

    public function salvar()
    {
        $this->db->salvarLivrosLidos($this->livrosLidos);
        $this->db->salvarLivrosNaoLidos($this->livrosNaoLidos);
        $this->db->salvarLivroAtual($this->livroAtual);
        $this->db->salvarUltimoLivroLido($this->ultimoLivroLido);
        $this->db->salvarProxLivro($this->proxLivroParaLer);
        $this->db->salvarNumLivrosNaoLidos($this->numLivrosNaoLidos);
        $this->db->salvarNumLivrosLidos($this->numLivrosLidos);
    }
}
?>