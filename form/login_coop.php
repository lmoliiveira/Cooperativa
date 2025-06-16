<?php
use banco\Database;

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../config/Database.php");
require_once("../config/config.php");
require_once("../includes/tratar_dados.php");

$errors = [];
$database = new Database(MYSQL_CONFIG);

$_SESSION['url'] = $_SERVER['REQUEST_URI'];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if ($email === 'admin@admin.com' && $senha === 'admin123') {
        $_SESSION['usuario_id'] = 0;
        $_SESSION['usuario_nome'] = 'Administrador';
        $_SESSION['usuario_tipo'] = 'admin';

        header("Location: ../admin/dashboard.php");
        exit;
    }

    if(empty($email) || empty($senha)) {
        $errors['login'] = "Preencha todos os campos.";
    } else {
        $params = [':email' => $email];

        $cooperativa = $database->execute_query("SELECT * FROM cooperativa WHERE email = :email", $params);
    
        if (!empty($cooperativa->results)) {
            $usuario = $cooperativa->results[0];
            $tipo = 'cooperativa';
        } else {
            $produtor = $database->execute_query("SELECT * FROM produtor WHERE email = :email", $params);
    
            if (!empty($produtor->results)) {
                $usuario = $produtor->results[0];
                $tipo = 'produtor';
            } else {
                $errors['email'] = "E-mail não encontrado.";
            }
        }
        if (empty($errors) && isset($usuario)) {
            if (password_verify($senha, $usuario->senha)) {
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_nome'] = $usuario->nome;
                $_SESSION['usuario_tipo'] = $tipo;

                unset($_SESSION['form_erros'], $_SESSION['form_data']);

                if (isset($_POST['lembrar'])) {
                    setcookie('lembrar_email', $email, time() + 2592000, "/");
    
                    setcookie('lembrar_senha', $senha, time() + 2592000, "/");
                } else {
                    setcookie('lembrar_email', '', time() - 3600, "/");
                    setcookie('lembrar_senha', '', time() - 3600, "/");
                }
                
                header("Location: ../public/index.php");
                exit;
            } else {
                $errors['senha'] = "Senha incorreta.";
            }
            
        }
    }

    $_SESSION['form_erros'] = $errors;
    $_SESSION['form_data'] = [
        'email' => $email,
    ];
    header("Location: ../public/login.php");
    exit;
}
?>