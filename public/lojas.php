<?php
$titulo_pagina = "Lojas";
use banco\Database;

include_once("../includes/header.php");

require_once("../config/Database.php");
require_once("../config/config.php");
$database = new Database(MYSQL_CONFIG);

$lojas = $database->execute_query(
    "SELECT lojas.*,
        cooperativa.nome AS nome_cooperativa 
    FROM lojas 
    JOIN cooperativa ON lojas.cooperativa_id = cooperativa.id
");
$lojas = $lojas->results;

?>

<main>
  <div class="tabela">
    <div>
      <br>
        <?php if(count($lojas) != 0): ?>
          <table>
            <thead>
              <tr>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Descrição</th>
                <th>Foto</th>
                <th>Cooperativa</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($lojas as $loja): ?>
                  <tr>
                    <td><?= $loja->nome ?></td>
                    <td><?= $loja->endereco?></td>
                    <td><?= $loja->descricao ?></td>
                    <td><img src="../<?= $loja->foto ?>" alt="Foto da loja" width="150"></td>
                    <td><?= $loja->nome_cooperativa ?></td>
                  </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>Não foram encontrados dados.</p>
        <?php endif; ?>
      </div>
  </div>
</main>   

<?php
include_once("../includes/footer.php");
?>