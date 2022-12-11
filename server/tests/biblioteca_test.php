<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "\\..\\src\\mock\\db_mock.php";
require_once __DIR__ . "\\..\\src\\entity\\Livro.php";
require_once __DIR__ . "\\..\\src\\entity\\Biblioteca.php";

final class BibliotecaTest extends TestCase
{
    protected $db;
    protected $biblioteca;
    protected $dateMgr;

    protected function setUp(): void
    {
        $this->db = new DbMock();
        $this->dateMgr = new DateManagerMock(1337);
        $this->biblioteca = new Biblioteca($this->db, $this->dateMgr);
    }

    public function testeInicializarLista(): void
    {
        $biblioteca = new Biblioteca($this->db, $this->dateMgr);

        $this->assertEquals(null, $biblioteca->getLivrosNaoLidos());
        $this->assertEquals(null, $biblioteca->getLivroAtual());
        $this->assertEquals(null, $biblioteca->getLivrosLidos());
        $this->assertEquals(null, $biblioteca->getUltimoLivroLido());
        $this->assertEquals(null, $biblioteca->getProximoLivroParaLer());
        $this->assertEquals(0, $biblioteca->getNumLivrosLidos());
        $this->assertEquals(0, $biblioteca->getNumLivrosNaoLidos());
    }

    public function testeInserirNovoLivroNaLista(): void
    {
        $livro = new Livro("Título", "Gênero", "Autor", false, null, $this->db, $this->dateMgr);

        $this->biblioteca->addLivro($livro);

        $this->assertEquals($this->biblioteca->getLivrosNaoLidos(), [$livro]);
    }

    public function testeCriacaoListaLivros(): void
    {
        $livros = [
            new Livro("Título 1", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
            new Livro("Título 2", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
            new Livro("Título 3", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
        ];

        $this->biblioteca->addLivros($livros);
        
        $this->assertEquals($livros[0], $this->biblioteca->getLivroAtual());
        $this->assertEquals(null, $this->biblioteca->getUltimoLivroLido());
        $this->assertEquals($livros, $this->biblioteca->getLivrosNaoLidos());
        $this->assertEquals(null, $this->biblioteca->getLivrosLidos());
        $this->assertEquals($livros[1], $this->biblioteca->getProximoLivroParaLer());
        $this->assertEquals(3, $this->biblioteca->getNumLivrosNaoLidos());
        $this->assertEquals(0, $this->biblioteca->getNumLivrosLidos());
    }

    public function testeConcluirLivroAtual(): void
    {
        $livros = [
            new Livro("Título 1", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
            new Livro("Título 2", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
            new Livro("Título 3", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
        ];

        $this->biblioteca->addLivros($livros);

        $this->biblioteca->concluirLivroAtual();

        $this->assertEquals(true, $livros[0]->isConcluido());
        $this->assertEquals(1337, $livros[0]->getDataConclusao());
        $this->assertEquals($livros[1], $this->biblioteca->getLivroAtual());
        $this->assertEquals($livros[0], $this->biblioteca->getUltimoLivroLido());
        $this->assertEquals($livros[2], $this->biblioteca->getProximoLivroParaLer());
        $this->assertEquals(1, $this->biblioteca->getNumLivrosLidos());
        $this->assertEquals(2, $this->biblioteca->getNumLivrosNaoLidos());
        $this->assertEquals([$livros[1], $livros[2]], $this->biblioteca->getLivrosNaoLidos());
        $this->assertEquals([$livros[0]], $this->biblioteca->getLivrosLidos());
    }

    public function testeSalvarBiblioteca(): void
    {
        $livros = [
            new Livro("Título 1", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
            new Livro("Título 2", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
            new Livro("Título 3", "Gênero", "Autor", false, null, $this->db, $this->dateMgr),
        ];

        $this->biblioteca->addLivros($livros);

        $this->biblioteca->concluirLivroAtual();

        $this->biblioteca->salvar();

        $biblioteca2 = new Biblioteca($this->db, $this->dateMgr);

        $this->assertEquals(true, $biblioteca2->getLivrosLidos()[0]->isConcluido());
        $this->assertEquals(1337, $biblioteca2->getLivrosLidos()[0]->getDataConclusao());
        $this->assertEquals($livros[1], $biblioteca2->getLivroAtual());
        $this->assertEquals($livros[0], $biblioteca2->getUltimoLivroLido());
        $this->assertEquals($livros[2], $biblioteca2->getProximoLivroParaLer());
        $this->assertEquals(1, $biblioteca2->getNumLivrosLidos());
        $this->assertEquals(2, $biblioteca2->getNumLivrosNaoLidos());
        $this->assertEquals([$livros[1], $livros[2]], $biblioteca2->getLivrosNaoLidos());
        $this->assertEquals([$livros[0]], $biblioteca2->getLivrosLidos());
    }
}