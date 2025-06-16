<?php
$titulo_pagina = "Cadastrar";
use banco\Database;

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("../includes/header.php");

require_once("../config/Database.php");
require_once("../config/config.php");
$database = new Database(MYSQL_CONFIG);

$cooperativas = $database->execute_query("SELECT id, nome FROM cooperativa ORDER BY nome ASC");
$cooperativas = $cooperativas->results ?? [];

?>

<main> 
    <div class="cadastro-container">

        <div class="cadastro-tabs">
            <button id="btnCooperativa" class="tab active">Cadastrar Cooperativa</button>
            <button id="btnProdutor" class="tab">Cadastrar Produtor</button>
        </div>
        
        <?php if (!empty($_SESSION['form_erros']['registro'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['registro'] ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['form_erros']['email'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['email'] ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['form_erros']['cnpj'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['cnpj'] ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['form_erros']['telefone'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['telefone'] ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['form_erros']['cpf'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['cpf'] ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['form_erros']['senha'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['senha'] ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['form_erros']['confirmar_senha'])): ?>
            <p style="color:red"><?= $_SESSION['form_erros']['confirmar_senha'] ?></p>
        <?php endif; ?>
        
        <!-- Form Cooperativa  -->
        <form id="formCooperativa" class="form-inserir active form-cadastro" action="../process/inserir_cooperativa.php" method="POST">

            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" required value="<?= $_SESSION['form_data']['nome'] ?? '' ?>">
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="email">E-mail</label>
                    <input type="email" name="email" required value="<?= $_SESSION['form_data']['email'] ?? '' ?>">
                </div>

                <div>
                    <label for="endereco">Endereço</label>
                    <input type="text" name="endereco" required value="<?= $_SESSION['form_data']['endereco'] ?? '' ?>">
                </div>
            </div>

            <div>
                <label for="cnpj">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj" required value="<?= $_SESSION['form_data']['cnpj'] ?? '' ?>">
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" required>
                </div>
    
                <div>
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" name="confirmar_senha" required>
                </div>
            </div>

            
            <div class="botoes">
                <a href="../public/index.php" class="cancelar">Cancelar</a>
                <input type="submit" value="Cadastrar" class="submit"></input>
            </div>
        </form>

        <!-- Form Produtor  -->
        <form id="formProdutor" class="form-inserir form-cadastro" action="../process/inserir_produtor.php" method="POST">
            <div class="dois-inputs">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" required value="<?= $_SESSION['form_data']['nome'] ?? '' ?>">
                </div>
    
                <div>
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" name="sobrenome" required value="<?= $_SESSION['form_data']['sobrenome'] ?? '' ?>">
                </div>
            </div>

            <div>
                <label for="email">E-mail</label>
                <input type="email" name="email" required value="<?= $_SESSION['form_data']['email'] ?? '' ?>">
            </div>

            <div class="dois-inputs">
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" required value="<?= $_SESSION['form_data']['telefone'] ?? '' ?>">
                </div>

                <div>
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" required value="<?= $_SESSION['form_data']['cpf'] ?? '' ?>">
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
                <a href="../public/index.php" class="cancelar">Cancelar</a>
                <input type="submit" value="Cadastrar" class="submit"></input>
            </div>
        </form>


    </div>
    <?php
        unset($_SESSION['form_erros'], $_SESSION['form_data']);
    ?>
</main>   

<script>
  const btnCooperativa = document.getElementById('btnCooperativa');
  const btnProdutor = document.getElementById('btnProdutor');
  const formCoop = document.getElementById('formCooperativa');
  const formProd = document.getElementById('formProdutor');

  btnCooperativa.addEventListener('click', () => {
    btnCooperativa.classList.add('active');
    btnProdutor.classList.remove('active');
    formCoop.classList.add('active');
    formProd.classList.remove('active');
  });

  btnProdutor.addEventListener('click', () => {
    btnProdutor.classList.add('active');
    btnCooperativa.classList.remove('active');
    formProd.classList.add('active');
    formCoop.classList.remove('active');
  });
</script>


<?php
include_once("../includes/footer.php");
?>
