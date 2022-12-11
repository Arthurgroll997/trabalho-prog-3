<?php
$url_parts = explode($_SERVER["REQUEST_URI"]);

switch ($url_parts[count($url_parts) - 1])
{
    case "getLivrosLidos":
    {
        break;
    }
    default:
        echo json_encode(["status" => "error", "content" => "Página inválida"]);
        break;
}

?>