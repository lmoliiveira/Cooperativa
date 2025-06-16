<?php

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$titulo_pagina = "Cadastrar loja";
use banco\Database;

$errors = [];

include_once("../includes/header.php");

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$database = new Database(MYSQL_CONFIG);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $endereco = tratarEndereco($_POST['endereco']);
    $foto = tratarImage($errors);
    $descricao = trim($_POST['descricao']);

    if(empty($errors)) {
        $params = [
            ':nome' => $nome,
            ':endereco' => $endereco,
            ':descricao' => $descricao,
            ':foto' => $foto,
            ':cooperativa_id' => $_SESSION['usuario_id']
        ];

        $query = $database->execute_non_query("INSERT INTO lojas (nome, endereco, descricao, foto, cooperativa_id) VALUES (:nome, :endereco, :descricao, :foto, :cooperativa_id)", $params);

        header('Location: ../public/lojas.php');

    } else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../process/inserir_lojas.php');
        exit;
    }
}
?>

<main> 
    <div class="container-inserir">
        <form action="#" method="POST" enctype="multipart/form-data" class="form-inserir">

            <h3>Cadastrar Loja</h3>

            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" required>
            </div>

            <div>
                <label for="endereco">Endereço</label>
                <input type="text" name="endereco" required>
            </div>
            <br>

            <label for="imagem">Envie a imagem da fachada</label>
            <?php if (!empty($_SESSION['form_erros']['imagem'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['imagem'] ?></p>
            <?php endif; ?>
            <input type="file" id="imagem" name="imagem" accept="imagem/*" required>

            <div>
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" required>
            </div>
            
            <br>
            <div class="botoes">
                <a href="../public/lojas.php" class="cancelar">Cancelar</a>
                <input type="submit" value="Cadastrar" class="submit"></input>
            </div>
        </form>
        <?php
            unset($_SESSION['form_erros'], $_SESSION['form_data']);
        ?>
    </div>
</main>   


<?php
include_once("../includes/footer.php");
?>