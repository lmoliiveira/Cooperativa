<?php
include_once("../config/autenticacao.php");
$titulo_pagina = "Produtos";
use banco\Database;

require_once("../includes/header.php");

if (empty($_GET['id'])) {
    header('Location: ../public/index.php');
    exit;
}

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$errors = [];

$database = new Database(MYSQL_CONFIG);


$id = $_GET['id'];
$params = [':id' => $id];

$query = $database->execute_query("SELECT * FROM produtos WHERE id = :id", $params);
$produto = $query->results[0];

$lojas = $database->execute_query("SELECT id, nome FROM lojas ORDER BY nome ASC")->results;


$lojas_produto = $database->execute_query(
    "SELECT loja_id FROM produto_loja WHERE produto_id = :id", [':id' => $id]
)->results;
$ids_lojas_produto = array_map(fn($l) => $l->loja_id, $lojas_produto);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $imagem = tratarImage($errors);
    $lojas_params = $_POST['lojas'] ?? [];

    if (empty($errors)) {
        $params = [
            ':id' => $id,
            ':nome' => $nome,
            ':categoria' => $_POST['categoria'],
            ':medida' => $_POST['medida'],
            ':quantidade' => $_POST['quantidade'],
            ':preco' => $_POST['preco'],
            ':imagem' => $imagem
        ];

        $query = $database->execute_non_query(
            "UPDATE produtos SET nome = :nome, categoria = :categoria, medida = :medida, 
            quantidade = :quantidade, preco = :preco, imagem = :imagem WHERE id = :id", $params
        );

        $query = $database->execute_non_query("DELETE FROM produto_loja WHERE produto_id = :id", [':id' => $id]);

        foreach ($lojas_params as $id_loja) {
            $params = [
                ':produto_id' => $id,
                ':loja_id' => $id_loja
            ];
            $query = $database->execute_non_query(
                "INSERT INTO produto_loja (produto_id, loja_id) VALUES (:produto_id, :loja_id)", $params
            );
        }

        if($_SESSION['usuario_tipo'] === 'admin') {
            header('Location: ../admin/dashboard.php');
            exit;
        } else {
            header('Location: ../public/produtor.php');
        }

    } else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
    }
}
?>

<main>
    <div class="container-inserir">
        <form action="#" method="POST" enctype="multipart/form-data" class="form-inserir">
            <h3>Atualizar produto</h3>

            <div>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?= $produto->nome ?>" required>
            </div>


            <div class="dois-inputs">
                <div>
                    <label for="categoria">Categoria</label>
                    <select name="categoria" id="categoria" required>
                        <option value="">Selecione</option>
                        <option value="Grão">Grão</option>
                        <option value="Laticínio">Laticínio</option>
                        <option value="Hortaliça">Hortaliça</option>
                        <option value="Fruta">Fruta</option>
                        <option value="Café">Café</option>
                        <option value="Carne">Carne</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>

                <div>
                    <fieldset class="medida">
                        <legend>Medida</legend>

                        <input type="radio" name="medida" value="KG" id="KG"> 
                        <label for="KG" class="radio-box">Kg</label>

                        <input type="radio" name="medida" value="Sacas" id="Sacas">
                        <label for="Sacas" class="radio-box">Sacas</label>

                        <input type="radio" name="medida" value="Litros" id="Litros">
                        <label for="Litros" class="radio-box">Litros</label>
                    </fieldset>
                </div>
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade" step="0.5" min="0" value="<?= $produto->quantidade ?>" required>
                </div>
    
                <div>
                    <label for="preco">Preço</label>
                    <input type="number" name="preco" step="0.01" min="0" value="<?= $produto->preco ?>" required placeholder="R$">
                </div>
            </div>

            <div>
                <label for="lojas">Adicionar a lojas</label>
                <select name="lojas[]" multiple required>
                    <?php foreach ($lojas as $loja): ?>
                        <option value="<?= $loja->id ?>" <?= in_array($loja->id, $ids_lojas_produto) ? 'selected' : '' ?>>
                            <?= $loja->nome ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="imagem">Envie a imagem do produto</label>
                <?php if (!empty($_SESSION['form_erros']['imagem'])): ?>
                    <p style="color:red"><?= $_SESSION['form_erros']['imagem'] ?></p>
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" accept="image/*" required>
            </div>
            
            <div class="botoes">
                <a href="../public/produtor.php" class="cancelar">Cancelar</a>
                <input type="submit" value="Atualizar" class="submit"></input>
            </div>
        </form>
    </div>
        <?php
            unset($_SESSION['form_erros'], $_SESSION['form_data']);
        ?>
</main>

<?php include_once("../includes/footer.php"); ?>
