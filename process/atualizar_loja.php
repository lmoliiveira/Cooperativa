<?php
include_once("../config/autenticacao.php");
$titulo_pagina = "Lojas";
use banco\Database;

require_once("../includes/header.php");

if(empty($_GET['id'])) {
    header('Location: ../public/index.php');
}

$urlAdmin = "../admin/dashboard.php";
$urlComum = "../public/index.php";

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$database = new Database(MYSQL_CONFIG);
$errors = [];

$id = $_GET['id'];
$params = [
    ':id' => $id
];
$query = $database->execute_query("SELECT * FROM lojas WHERE id = :id", $params);
$lojas = $query->results[0];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $endereco = tratarEndereco($_POST['endereco']);
    $foto = tratarImage($errors);
    $descricao = trim($_POST['descricao']);

    if(empty($errors)) {
        $params = [
            ':id' => $id,
            ':nome' => $nome,
            ':endereco' => $endereco,
            ':descricao' => $descricao,
            ':foto' => $foto,
        ];

        $query = $database->execute_non_query("UPDATE lojas SET nome = :nome, endereco = :endereco, descricao = :descricao, foto = :foto WHERE id = :id", $params);

        if($_SESSION['usuario_tipo'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../public/index.php');
        }

    } else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}
?>

<main> 
    <div class="container-inserir">
        <form action="" method="POST" enctype="multipart/form-data" class="form-inserir">
            <h3>Atualizar Loja</h3>
            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" required value="<?= htmlspecialchars($lojas->nome) ?>">
            </div>

            <div>
                <label for="endereco">Endereço</label>
                <input type="text" name="endereco" required value="<?= $lojas->endereco ?>">
            </div>

            <br>
            <label for="imagem">Envie a imagem da fachada</label>
            <?php if (!empty($_SESSION['form_erros']['imagem'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['imagem'] ?></p>
            <?php endif; ?>
            <input type="file" id="imagem" name="imagem" accept="imagem/*" required>

            <div>
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" required value="<?= $lojas->descricao ?>">
            </div>
            
            <br>
            <div class="botoes">
                <a href="<?= ($_SESSION['usuario_tipo'] === 'admin') ? $urlAdmin : $urlComum ?>" class="cancelar">Cancelar</a>
                <input type="submit" value="Atualizar" class="submit"></input>
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
