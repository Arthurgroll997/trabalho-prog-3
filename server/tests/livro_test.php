<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "\\..\\src\\entity\\livro.php";
require_once __DIR__ . "\\..\\src\\mock\\date_mgr_mock.php";
require_once __DIR__ . "\\..\\src\\mock\\db_mock.php";

final class LivroTest extends TestCase
{
    public $db;
    public $dateMgr;

    protected function setUp(): void
    {
        $this->db = new DbMock();
        $this->dateMgr = new DateManagerMock(1337);
    }

    public function testeCriarLivro(): void
    {
        $livro = new Livro("Título", "Gênero", "Autor", false, null, $this->db, $this->dateMgr);

        $this->assertEquals("Título", $livro->titulo);
        $this->assertEquals("Gênero", $livro->genero);
        $this->assertEquals("Autor", $livro->autor);
        $this->assertEquals(false, $livro->isConcluido());
        $this->assertEquals(null, $livro->getDataConclusao());
    }

    public function testeConcluirLivro(): void
    {
        $livro = new Livro("Título", "Gênero", "Autor", false, null, $this->db, $this->dateMgr);

        $this->assertFalse($livro->isConcluido());
        $this->assertEquals(null, $livro->getDataConclusao());

        $livro->concluir();

        $this->assertTrue($livro->isConcluido());
        $this->assertEquals(1337, $livro->getDataConclusao());
    }

    
}

?>