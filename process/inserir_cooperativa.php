<?php
use banco\Database;

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];

include_once("../includes/header.php");

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$database = new Database(MYSQL_CONFIG);


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $endereco = tratarEndereco($_POST['endereco']);
    $email = tratarEmail($_POST['email'], $database, $errors);
    $senha = confirmarSenha($_POST['senha'], $_POST['confirmar_senha'], $errors);
    $cnpj = tratarCNPJ($_POST['cnpj'], $database, $errors);
    
    if(empty($errors)) {
        $params = [
            ':nome' => $nome,
            ':endereco' => $endereco,
            ':email' => $email,
            ':senha' => $senha,
            ':cnpj' => $cnpj
        ];

        $query = $database->execute_non_query("INSERT INTO cooperativa (nome, endereco, email, senha, cnpj) VALUE (:nome, :endereco, :email, :senha, :cnpj)", $params);

        if($query->status === 'success') {
            $params_email = [':email' => $email];
            $id_query = $database->execute_query("SELECT id FROM cooperativa WHERE email = :email", $params_email);

            $cooperativa = $id_query->results[0];

            $_SESSION['usuario_id'] = $cooperativa->id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_tipo'] = 'cooperativa';

            header('Location: ../public/index.php');
            exit;

        } else {
            $errors['registro'] = "Erro ao registrar. Tente novamente.";
            $_SESSION['form_erros'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: ../public/registrar.php');
            exit;
        }

    } else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../public/registrar.php');
        exit;
    }
}
?>


<?php
include_once("../includes/footer.php");
?>