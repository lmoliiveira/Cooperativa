<?php
include_once("../config/autenticacao.php");

$titulo_pagina = "Produtos";
use banco\Database;


include_once("../includes/header.php");
$erros = [];

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$database = new Database(MYSQL_CONFIG);

$lojas = $database->execute_query("SELECT id, nome FROM lojas ORDER BY nome ASC")->results;

$lojas_produto = $database->execute_query(
    "SELECT loja_id FROM produto_loja WHERE produto_id = :id", [':id' => $_SESSION['usuario_id']]
)->results;
$ids_lojas_produto = array_map(fn($l) => $l->loja_id, $lojas_produto);


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = tratarNome($_POST['nome']);
    $imagem = tratarImage($errors);
    $lojas_params = $_POST['lojas'];

    if(empty($errors)) {
        $params = [
            ':nome' => $nome,
            ':categoria' => $_POST['categoria'],
            ':medida' => $_POST['medida'],
            ':quantidade' => $_POST['quantidade'],
            ':preco' => $_POST['preco'],
            ':imagem' => $imagem,
            ':produtor_id' => $_SESSION['usuario_id'],
        ];

        $result = $database->execute_non_query(
            "INSERT INTO produtos (nome, categoria, medida, quantidade, preco, imagem, produtor_id) 
            VALUES (:nome, :categoria, :medida, :quantidade, :preco, :imagem, :produtor_id)",
            $params
        );

        if ($result->status !== 'success') {
            $errors['insercao'] = "Erro ao inserir produto: " . $result->message;
        } else {
            $id_produto = $result->last_id;
        }

        if (!empty($id_produto)) {
            foreach($lojas_params as $id_loja) {
                $params = [
                    ':produto_id' => $id_produto,
                    ':loja_id' => $id_loja,
                ];
                $result = $database->execute_non_query(
                    "INSERT INTO produto_loja (produto_id, loja_id) 
                    VALUES (:produto_id, :loja_id)",
                    $params
                );
            }
        }

        header('Location: ../public/produtos.php');
        exit;

    } else {
        $_SESSION['form_erros'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../form/produtos_form.php');
        exit;
    }
}


?>
<main> 
    <div class="container-inserir">
        <form action="#" method="POST" enctype="multipart/form-data" class="form-inserir">
            <h3>Cadastrar Produto</h3>

            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" required>
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
                    <input type="number" name="quantidade" step="0.5" min="0" required>
                </div>

                <div>
                    <label for="preco">Preço</label>
                    <input type="number" name="preco" step="0.01" min="0" required placeholder="R$">
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
                <?php if(!empty($_SESSION['form_erros']['imagem'])): ?>
                    <p style="color:red"><?= $_SESSION['form_erros']['imagem'] ?></p>
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" accept="imagem/*" required>
            </div>
            
            <br>
            <div class="botoes">
                <a href="../public/produtos.php" class="cancelar">Cancelar</a>
                <input type="submit" value="Cadastrar" class="submit"></input>
            </div>
        </form>
    </div>
        <?php
            unset($_SESSION['form_erros'], $_SESSION['form_data']);
        ?>
</main>   

<?php
include_once("../includes/footer.php");
?>