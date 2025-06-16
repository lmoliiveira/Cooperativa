<?php
include_once("../config/autenticacao.php");
use banco\Database;
session_start();

require_once("../includes/header.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$database = new Database(MYSQL_CONFIG);

if(empty($_GET['id'])) {
    header('Location: ../public/index.php');
    exit;
}

$id = $_GET['id'];
$params = [':id' => $id];

if(empty($_GET['delete'])) {
    $query = $database->execute_query("SELECT * FROM produtos WHERE id = :id", $params);

    if(empty($query->results)) {
        echo "<p>Produto não encontrado.</p>";
    } else {
        $produto = $query->results[0];
    }

} else {
    $query = $database->execute_query("SELECT imagem FROM produtos WHERE id = :id", $params);

    if(!empty($query->results)) {
        $imagem = $query->results[0]->imagem;

        if(!empty($imagem)) {
            $caminhoImagem = realpath(__DIR__ . "/../" . $imagem);

            if($caminhoImagem && str_starts_with($caminhoImagem, realpath(__DIR__ . "/../assets/img/imagens/"))) {
                if(file_exists($caminhoImagem)) {
                    unlink($caminhoImagem);
                }
            }
        }
    }

    $query = $database->execute_non_query("DELETE FROM produtos WHERE id = :id", $params);


    $redirecionar = $_SESSION['url'] ?? '../public/index.php';
    unset($_SESSION['url']);
    header("Location: $redirecionar");
    exit;
}

require_once("../includes/footer.php");
?>
