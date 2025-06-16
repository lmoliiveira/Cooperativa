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
}

$id = $_GET['id'];
$params = [
    ':id' => $id
];

if(empty($_GET['delete'])){
    $query = $database->execute_query("SELECT * FROM produtor WHERE id = :id", $params);
    $produtor = $query->results[0];
} else {
    $database->execute_non_query("DELETE FROM produtor WHERE id = :id", $params);

    if ($_SESSION['usuario_tipo'] === 'admin') {
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        session_destroy();
        header("Location: ../public/index.php");
        exit;
    }   
}
?>

<?php
include_once("../includes/footer.php");
?>