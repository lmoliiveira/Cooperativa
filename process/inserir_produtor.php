<?php
use banco\Database;
$titulo_pagina = "Cadastrar Produtor";

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
    $sobrenome = tratarNome($_POST['sobrenome']);
    $email = tratarEmail($_POST['email'], $database, $errors);
    $telefone = tratarTelefone($_POST['telefone'], $database, $errors);
    $senha = confirmarSenha($_POST['senha'], $_POST['confirmar_senha'], $errors);
    $cpf = tratarCPF($_POST['cpf'], $database, $errors);

    if(empty($errors)) {
        $params = [
            ':nome' => $nome,
            ':sobrenome' => $sobrenome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':cpf' => $cpf,
            ':senha' => $senha
        ];

        $query = $database->execute_non_query("INSERT INTO produtor (nome, sobrenome, email, telefone, cpf, senha) VALUES (:nome, :sobrenome, :email, :telefone, :cpf, :senha)", $params);
        
        if($query->status === 'success') {
            $params_email = [':email' => $email];
            $id_query = $database->execute_query("SELECT id FROM produtor WHERE email = :email", $params_email);

            $produtor = $id_query->results[0];

            $_SESSION['usuario_id'] = $produtor->id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_tipo'] = 'produtor';

            header('Location: ../public/index.php');
            exit;

        } else {
            $errors['registro'] = "Erro ao registrar. Tente novamente.";
            $_SESSION['form_erros'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: ../public/registrar.php');
            exit;
        }

    }else {
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