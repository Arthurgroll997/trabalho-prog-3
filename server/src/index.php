<?php
require "./entity/Biblioteca.php";
require "./entity/Livro.php";
require "./adapter/db_adapter.php";
require "./adapter/date_mgr.php";
require "./native/DB.php";
require "./mock/db_mock.php";

$db = new DbAdapter($nativeDb->db);
// $db = new DbMock();
$bib = new Biblioteca($db, new DateManager());

switch ($_POST["action"])
{
    case "addLivro":
    {
        $livro = new Livro($_POST["titulo"], $_POST["genero"], $_POST["autor"],
                    false, null, $db, new DateManager());

        $bib->addLivro($livro);

        $bib->salvar();

        echo json_encode(["status" => "success", "error" => ""]);

        break;
    }
    case "lerLivro":
    {
        $bib->concluirLivroAtual();
        $bib->salvar();

        echo json_encode(["status" => "success", "error" => ""]);

        break;
    }
    case "getInfo":
    {
        echo json_encode([
            "status" => "success",
            "error" => "",
            "info" => [
                "livroAtual" => $bib->getLivroAtual(),
                "ultimoLivroLido" => $bib->getUltimoLivroLido(),
                "proxLivroParaLer" => $bib->getProximoLivroParaLer(),
                "livrosNaoLidos" => $bib->getLivrosNaoLidos(),
                "livrosLidos" => $bib->getLivrosLidos(),
                "numLivrosNaoLidos" => $bib->getNumLivrosNaoLidos(),
                "numLivrosLidos" => $bib->getNumLivrosLidos(),
            ]
        ]);
        
        break;
    }
    default:
        echo json_encode(["status" => "error", "content" => "Página inválida"]);
        break;
}

?>