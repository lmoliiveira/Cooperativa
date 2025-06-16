<?php
include_once("../config/autenticacao.php");
use banco\Database;

require_once("../includes/header.php");

if(empty($_GET['id'])) {
    header('Location: ../public/index.php');
}

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$redirecionar = $_SESSION['url'] ?? '../public/index.php';

$id = $_GET['id'];
$errors = [];
$database = new Database(MYSQL_CONFIG);

$params = [
    ':id' => $id
];
$query = $database->execute_query("SELECT * FROM cooperativa WHERE id = :id", $params);
$cooperativa = $query->results[0];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $endereco = tratarEndereco($_POST['endereco']);
    $email = validarEmailAtualizacao($_POST['email'], $id, "cooperativa", $database, $errors);
    $senha = confirmarSenha($_POST['senha'], $_POST['confirmar_senha'], $errors);
    $cnpj = validarCNPJAtualizacao($_POST['cnpj'], $id, $database, $errors);

    if(empty($errors)) {
        $params = [
            ':id' => $id,
            ':nome' => $nome,
            ':endereco' => $endereco,
            ':email' => $email,
            ':senha' => $senha,
            ':cnpj' => $cnpj
        ];

        $query = $database->execute_non_query(
            "UPDATE cooperativa " .
            "SET nome = :nome, endereco = :endereco, email = :email, senha = :senha, cnpj = :cnpj " .
            "WHERE id = :id", $params
        );

        $_SESSION['usuario_nome'] = $nome;
        header('Location: ../public/cooperativa.php');

    } else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}
?>

<main> 
    <div class="container-inserir">
        <form action="../process/atualizar_cooperativa.php?id=<?= $id ?>" method="POST"  class="form-inserir">
            <h3>Atualizar Cooperativa</h3>
            <div>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" required value="<?= $cooperativa->nome ?>">
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="email">E-mail:</label>
                    <?php if (!empty($_SESSION['form_erros']['email'])): ?>
                        <p style="color:red"><?= $_SESSION['form_erros']['email'] ?></p>
                    <?php endif; ?>
                    <input type="email" name="email" required value="<?= $cooperativa->email ?>">
                </div>

                <div>
                    <label for="endereco">Endereço:</label>
                    <input type="text" name="endereco" required value="<?= $cooperativa->endereco ?>">
                </div>
            </div>

            <div>
                <label for="cnpj">CNPJ:</label>
                <?php if (!empty($_SESSION['form_erros']['cnpj'])): ?>
                    <p style="color:red"><?= $_SESSION['form_erros']['cnpj'] ?></p>
                <?php endif; ?>
                <input type="text" id="cnpj" name="cnpj" required value="<?= htmlspecialchars($cooperativa->cnpj) ?>">
            </div>


            <div class="dois-inputs">
                <div>
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" required>
                </div>

                <div>
                    <label for="confirmar_senha">Confirmar senha:</label>
                    <input type="password" name="confirmar_senha" required>
                </div>
            </div>

           <div class="botoes">
                <a href="<?= $redirecionar ?>" class="cancelar">Cancelar</a>
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