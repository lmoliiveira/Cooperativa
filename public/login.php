<?php
$titulo_pagina = "Login";
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("../includes/header.php");

$emailSalvo = $_COOKIE['lembrar_email'] ?? '';
$senhaSalva = $_COOKIE['lembrar_senha'] ?? '';
?>

<main>
    <div class="container-inserir">
        
        <form action="../form/login_coop.php" method="POST" class="form-inserir">
            <h3>Login</h3>
            
            <?php if (!empty($_SESSION['form_erros']['login'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['login'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['form_erros']['email'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['email'] ?></p>
            <?php endif; ?>
            <?php if (!empty($_SESSION['form_erros']['senha'])): ?>
                <p style="color:red"><?= $_SESSION['form_erros']['senha'] ?></p>
            <?php endif; ?>

            <div>
                <label for="email">E-mail:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($emailSalvo) ?>" required>
            </div>

            <div>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required value="<?= htmlspecialchars($senhaSalva) ?>">
            </div>

            <div>
                <label>
                    <input type="checkbox" name="lembrar" <?= $emailSalvo ? 'checked' : '' ?>> Lembrar-me
                </label>
            </div>

            <br>
            <div class="botoes">
                <a href="../public/index.php" class="cancelar">Cancelar</a>
                <input type="submit" value="Entrar" class="submit"></input>
            </div>
        </form>
    </div>

    <?php
        unset($_SESSION['form_erros']);
    ?>
</main>

<?php
include_once("../includes/footer.php");
?>
