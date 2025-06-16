<?php

use banco\Database;

require_once("../config/Database.php");
require_once("../config/config.php");
include_once("../includes/header.php");

$database = new Database(MYSQL_CONFIG);


$busca = $_GET['busca'] ?? '';
$categoria = $_GET['categoria'] ?? '';

$where = [];
$params = [];

if (!empty($busca)) {
  $where[] = "produtos.nome LIKE :busca";
  $params[':busca'] = "%$busca%";
}

if (!empty($categoria)) {
  $where[] = "produtos.categoria = :categoria";
  $params[':categoria'] = $categoria;
}


$sql = "SELECT 
  produtos.*, 
  lojas.nome AS nome_loja
FROM produtos
JOIN produto_loja ON produtos.id = produto_loja.produto_id
JOIN lojas ON produto_loja.loja_id = lojas.id";

if (count($where) > 0) {
  $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY produtos.nome ASC";


$resultados = $database->execute_query($sql, $params)->results;

// echo'<pre>';
// print_r($resultados);
// var_dump($resultados);
// die();

?>

<main>
  <div class="wrapper">
      <h2>Produtos</h2>

      <form method="GET" class="search-form">
          <input type="text" name="busca" placeholder="Buscar produto..." value="<?= htmlspecialchars($busca) ?>" />
          <select name="categoria">
              <option value="">Todas Categorias</option>
              <option value="Laticínio" <?= $categoria === 'Laticínio' ? 'selected' : '' ?>>Laticínios</option>
              <option value="Hortaliça" <?= $categoria === 'Hortaliça' ? 'selected' : '' ?>>Hortaliças</option>
              <option value="Fruta" <?= $categoria === 'Fruta' ? 'selected' : '' ?>>Frutas</option>
              <option value="Grão" <?= $categoria === 'Grão' ? 'selected' : '' ?>>Grãos</option>
              <option value="Carne" <?= $categoria === 'Carne' ? 'selected' : '' ?>>Carnes</option>
              <option value="Café" <?= $categoria === 'Café' ? 'selected' : '' ?>>Café</option>
              <option value="Outros" <?= $categoria === 'Outros' ? 'selected' : '' ?>>Outros</option>
          </select>
          <button type="submit">Filtrar</button>
      </form>

      <p>Total de produtos: <?=count($resultados) ?></p>
      <div class="container">
          <?php if (empty($resultados)): ?>
              <p>Nenhum produto encontrado.</p>
          <?php else: ?>
              <?php foreach ($resultados as $produto): ?>
                  <div class="card">
                      <img src="../<?= htmlspecialchars($produto->imagem) ?>" alt="<?= htmlspecialchars($produto->nome) ?>" />
                      <div class="category"><?= htmlspecialchars($produto->categoria) ?></div>
                      <div class="title"><?= htmlspecialchars($produto->nome) ?></div>
                      <div class="price">
                        R$<?= number_format($produto->preco, 2, ',', '.') ?> - <?= htmlspecialchars($produto->medida) ?>
                      </div>
                      <div class="shop"><?= htmlspecialchars($produto->nome_loja) ?></div>
                  </div>
              <?php endforeach; ?>
          <?php endif; ?>
      </div>
  </div>

</main>
<?php
include_once("../includes/footer.php");
?>