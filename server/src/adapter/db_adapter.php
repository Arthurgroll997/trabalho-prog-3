<?php
require_once "./entity/Livro.php";

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

    private function livroExiste($livro)
    {
        $stmt = $this->nativeDb->prepare("SELECT COUNT(*) FROM livro WHERE titulo=:t AND genero=:g AND autor=:a");

        $stmt->execute(["t" => $livro->titulo, "g" => $livro->genero, "a" => $livro->autor]);

        return $stmt->fetchColumn() > 0;
    }

    private function saveOrUpdateLivro($livro)
    {
        $stmt = $this->nativeDb->prepare("UPDATE livro SET lido=:l, data_conclusao=:dc WHERE titulo=:t AND genero=:g AND autor=:a");
        $stmt->execute(["l" => $livro->isConcluido() ? 1 : 0, "dc" => $livro->getDataConclusao(), "t" => $livro->titulo, "g" => $livro->genero, "a" => $livro->autor]);
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

        if (!$livro)
            return null;

        return new Livro($livro["titulo"], $livro["genero"], $livro["autor"],
                $livro["lido"] == 1, $livro["data_conclusao"], $this, $this->dateMgr);
    }

    public function getUltimoLivroLido()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=1 ORDER BY ID DESC LIMIT 1");
        $stmt->execute();

        $livro = $stmt->fetch();

        if (!$livro)
            return null;

        return $this->rowToLivro($livro);
    }

    public function getProxLivroParaLer()
    {
        $stmt = $this->nativeDb->prepare("SELECT * FROM livro WHERE lido=0 ORDER BY ID ASC LIMIT 1, 1");
        $stmt->execute();

        $livro = $stmt->fetch();

        if (!$livro)
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
        if ($livrosNaoLidos == null)
            return;

        foreach($livrosNaoLidos as $livroNaoLido)
        {
            if (!$this->livroExiste($livroNaoLido))
            {
                $stmt = $this->nativeDb->prepare("INSERT INTO livro(titulo, genero, autor, lido, data_conclusao) VALUES (:t, :g, :a, 0, NULL)");
                $stmt->execute(["t" => $livroNaoLido->titulo, "g" => $livroNaoLido->genero, "a" => $livroNaoLido->autor]);
            }
        }
    }

    public function salvarLivrosLidos($livrosLidos)
    {
        if ($livrosLidos == null)
            return;

        foreach($livrosLidos as $livroLido)
        {
            if ($this->livroExiste($livroLido))
            {
                $stmt = $this->nativeDb->prepare("UPDATE livro SET lido=1, data_conclusao=:dc WHERE titulo=:t AND genero=:g AND autor=:a");
                $stmt->execute(["dc" => $livroLido->getDataConclusao(), "t" => $livroLido->titulo, "g" => $livroLido->genero, "a" => $livroLido->autor]);
            }
            else
            {
                $stmt = $this->nativeDb->prepare("INSERT INTO livro(titulo, genero, autor, lido, data_conclusao) VALUES (:t, :g, :a, 1, :dc)");
                $stmt->execute(["t" => $livroLido->titulo, "g" => $livroLido->genero, "a" => $livroLido->autor, "dc" => $livroLido->getDataConclusao()]);
            }
        }
    }

    public function salvarLivroAtual($livro)
    {
        if ($livro == null)
            return;

        $this->saveOrUpdateLivro($livro);
    }

    public function salvarUltimoLivroLido($livro)
    {
        if ($livro == null)
            return;

        $this->saveOrUpdateLivro($livro);
    }

    public function salvarProxLivro($livro)
    {
        if ($livro == null)
            return;

        $this->saveOrUpdateLivro($livro);
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