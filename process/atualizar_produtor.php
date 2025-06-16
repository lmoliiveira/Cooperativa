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

$errors = [];
$database = new Database(MYSQL_CONFIG);

$id = $_GET['id'];
$params = [
    ':id' => $id
];

$query = $database->execute_query("SELECT * FROM produtor WHERE id = :id", $params);
$produtor = $query->results[0];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $sobrenome = tratarNome($_POST['sobrenome']);
    $email = validarEmailAtualizacao($_POST['email'], $id, "produtor", $database, $errors);
    $telefone = tratarTelefoneAtualizacao($_POST['telefone'], $id, $database, $errors);
    $senha = confirmarSenha($_POST['senha'], $_POST['confirmar_senha'], $errors);
    $cpf = validarCPFAtualizacao($_POST['cpf'], $id, $database, $errors);

    if(empty($errors)) {
        $params = [
            'id' => $id,
            ':nome' => $nome,
            ':sobrenome' => $_POST['sobrenome'],
            ':email' => $_POST['email'],
            ':telefone' => $_POST['telefone'],
            ':senha' => $_POST['senha'],
            ':cpf' => $_POST['cpf'],
        ];

        $query = $database->execute_non_query(
            "UPDATE produtor " .
            "SET nome = :nome, sobrenome = :sobrenome, email = :email, telefone = :telefone, senha = :senha, cpf = :cpf " .
            "WHERE id = :id", $params
        );

        $_SESSION['usuario_nome'] = $nome;

        $redirecionar = $_SESSION['url'] ?? '../public/index.php';
        unset($_SESSION['url']);
        header("Location: $redirecionar");
    }else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}
?>

<main> 
    <div class="container-inserir">
        <form action="" method="POST" class="form-inserir">
            <h3>Atualizar dados</h3>

            <?php if (!empty($_SESSION['form_erros']['email'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['email'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['form_erros']['cpf'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['cpf'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['form_erros']['telefone'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['telefone'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['form_erros']['senha'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['senha'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['form_erros']['confirmar_senha'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['confirmar_senha'] ?></p>
            <?php endif; ?>

            <div class="dois-inputs">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" required value="<?= $produtor->nome ?>">
                </div>
    
                <div>
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" name="sobrenome" required value="<?= $produtor->sobrenome ?>">
                </div>
            </div>

            <div>
                <label for="email">E-mail</label>
                <input type="email" name="email" required value="<?= $produtor->email ?>">
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" required value="<?= $produtor->telefone ?>">
                </div>

                <div>
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" required value="<?= $produtor->cpf ?>">
                </div>
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" required minlength="6">
                </div>

                <div>
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" name="confirmar_senha" required minlength="6">
                </div>
            </div>

            <br>
            <div class="botoes">
                <a href="../public/produtor.php" class="cancelar">Cancelar</a>
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