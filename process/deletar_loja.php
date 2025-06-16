<?php
include_once("../config/autenticacao.php");
use banco\Database;
session_start();

include_once("../includes/header.php");

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
    $query = $database->execute_query("SELECT * FROM lojas WHERE id = :id", $params);
    $lojas = $query->results[0];

} else {
    $query = $database->execute_query("SELECT foto FROM lojas WHERE id = :id", $params);

    if(!empty($query->results)) {
        $foto = $query->results[0]->foto;

        if(!empty($foto)) {
            $caminhoImagem = realpath(__DIR__ . "/../" . $foto);
            $pastaBase = realpath(__DIR__ . "/../assets/img/imagens");

            if($caminhoImagem && str_starts_with(str_replace('\\', '/', $caminhoImagem), str_replace('\\', '/', $pastaBase))) {
                if(file_exists($caminhoImagem)) {
                    unlink($caminhoImagem);
                }
            }
        }
    }

    $database->execute_non_query("DELETE FROM lojas WHERE id = :id", $params);

    $redirecionar = $_SESSION['url'] ?? '../public/index.php';
    unset($_SESSION['url']);
    header("Location: $redirecionar");
    exit;
}
?>

<?php include_once("../includes/footer.php"); ?>
