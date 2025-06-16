<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_pagina) ? $titulo_pagina : "Cooperativa" ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../assets/img/icons/cow.png" type="image/x-icon">
</head>
<body>

<header>
    <nav>
        <div class="container1">
            <a href="../public/index.php"><img src="../assets/img/icons/cow2.png" alt="Logo"></a>
            <h4><?= isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : ''?></h4>
        </div>
        
        <div class="container2">
            <form action="produtos.php" method="get">
                <input type="text" name="busca" id="busca" placeholder="Pesquisar..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>" required>
                <button type="submit" style="cursor: pointer;">🔍</button>
            </form>
            <a href="../public/lojas.php">Lojas</a>
            <a href="../public/produtos.php">Produtos</a>
            
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <?php if($_SESSION['usuario_tipo'] === 'cooperativa'): ?>
                    <a href="../public/cooperativa.php">Perfil</a>
                <?php elseif($_SESSION['usuario_tipo'] === 'produtor'):?>
                    <a href="../public/produtor.php">Perfil</a>
                <?php endif; ?>
                        
                <a href="../public/logout.php">Sair</a>
            <?php else :?>
                <a href="../public/login.php">Login</a>
                <a href="../public/registrar.php">Registrar</a>
            <?php endif;?>

        </div>
    </nav>
</header>