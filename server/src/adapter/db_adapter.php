<?php
require "../entity/Livro.php";

class DbAdapter
{
    private $nativeDb;
    private $dateMgr;

    public function __construct($nativeDb)
    {
        $this->nativeDb = $nativeDb;
    }

    public function setDateMgr($dateMgr)
    {
        $this->dateMgr = $dateMgr;
    }

    private function rowToLivro($livro)
    {
        return new Livro($livro["titulo"], $livro["genero"], $livro["autor"],
            $livro["lido"] == 1, $livro["data_conclusao"], $this, $this->dateMgr);
    }

    public function getLivrosNaoLidos()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=0 ORDER BY ID ASC");
        $stmt->execute();

        $livros = $stmt->fetchAll();

        $livrosNaoLidos = [];

        if (count($livros) == 0)
            return null;

        foreach ($livros as $livro)
        {
            array_push($livrosNaoLidos, new Livro($livro["titulo"], $livro["genero"], $livro["autor"],
                $livro["lido"] == 1, $livro["data_conclusao"], $this, $this->dateMgr));
        }

        return $livrosNaoLidos;
    }

    public function getLivrosLidos()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=1 ORDER BY ID ASC");
        $stmt->execute();

        $livros = $stmt->fetchAll();

        $livrosLidos = [];

        if (count($livros) == 0)
            return null;

        foreach ($livros as $livro)
        {
            array_push($livrosLidos, new Livro($livro["titulo"], $livro["genero"], $livro["autor"],
                $livro["lido"] == 1, $livro["data_conclusao"], $this, $this->dateMgr));
        }

        return $livrosLidos;
    }

    public function getLivroAtual()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=0 ORDER BY ID ASC LIMIT 1");
        $stmt->execute();

        $livro = $stmt->fetch();

        if (!isset($livro))
            return null;

        return new Livro($livro["titulo"], $livro["genero"], $livro["autor"],
                $livro["lido"] == 1, $livro["data_conclusao"], $this, $this->dateMgr);
    }

    public function getUltimoLivroLido()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=1 ORDER BY ID DESC LIMIT 1");
        $stmt->execute();

        $livro = $stmt->fetch();

        if (!isset($livro))
            return null;

        return $this->rowToLivro($livro);
    }

    public function getProxLivroParaLer()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=0 ORDER BY ID ASC LIMIT 1, 1");
        $stmt->execute();

        $livro = $stmt->fetch();

        if (!isset($livro))
            return null;

        return $this->rowToLivro($livro);
    }

    public function getNumLivrosNaoLidos()
    {
        $stmt = $this->nativeDb->prepare("SELECT COUNT(*) FROM livro WHERE lido=0");
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getNumLivrosLidos()
    {
        $stmt = $this->nativeDb->prepare("SELECT COUNT(*) FROM livro WHERE lido=1");
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function salvarLivrosNaoLidos($livrosNaoLidos)
    {
        // TODO: IMPLEMENTAR
    }

    public function salvarLivrosLidos($livrosLidos)
    {
        // TODO: IMPLEMENTAR
    }

    public function salvarLivroAtual($livro)
    {
        // TODO: IMPLEMENTAR
    }

    public function salvarUltimoLivroLido($livro)
    {
        // TODO: IMPLEMENTAR
    }

    public function salvarProxLivro($livro)
    {
        // TODO: IMPLEMENTAR
    }

    public function salvarNumLivrosNaoLidos($numLivrosNaoLidos)
    {
        // Não precisa de implementação. É feito contando as rows.
    }

    public function salvarNumLivrosLidos($numLivrosLidos)
    {
        // Não precisa de implementação. É feito contando as rows.
    }
}

?>