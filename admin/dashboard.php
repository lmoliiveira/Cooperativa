<?php

$titulo_pagina = "Dashboard";

session_start();
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../public/login.php');
    exit;
}


use banco\Database;

require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");

$database = new Database(MYSQL_CONFIG);

$cooperativas = $database->execute_query("SELECT * FROM cooperativa");
$cooperativas = $cooperativas->results;

$lojas = $database->execute_query(
    "SELECT lojas.*,
        cooperativa.nome AS nome_cooperativa 
    FROM lojas 
    JOIN cooperativa ON lojas.cooperativa_id = cooperativa.id
");
$lojas = $lojas->results;

$produtores = $database->execute_query("SELECT * FROM produtor");
$produtores = $produtores->results;

$produtos = $database->execute_query(
    "SELECT 
        produtos.*, 
        produtor.nome AS nome_produtor, 
        produtor.sobrenome AS sobrenome_produtor, 
        GROUP_CONCAT(lojas.nome ORDER BY lojas.nome SEPARATOR ', ') AS nome_lojas
    FROM produtos 
    INNER JOIN produtor ON produtos.produtor_id = produtor.id
    INNER JOIN produto_loja ON produtos.id = produto_loja.produto_id
    INNER JOIN lojas ON lojas.id = produto_loja.loja_id
    GROUP BY produtos.id, nome_produtor, sobrenome_produtor"
);
$produtos = $produtos->results;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_pagina) ? $titulo_pagina : "Cooperativa" ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="shortcut icon" href="../assets/img/icons/cow.png" type="image/x-icon">
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <a href="../admin/dashboard.php"><img src="../assets/img/icons/cow2.png" alt="Logo"></a>
        </div>
        
        <div class="logout">
            <a href="../public/logout.php">Sair</a>
        </div>
    </nav>
</header>

<main>
    <nav class="sidebar">
        <div class="menu">
            <button data-target="tableCooperativa" class="btn active">Cooperativa</button>
            <button data-target="tableProdutor" class="btn">Produtor</button>
            <button data-target="tableProdutos" class="btn">Produtos</button>
            <button data-target="tableLojas" class="btn">Lojas</button>
        </div>
    </nav>

    <section class="dashboard">

        <div class="tabela active" id="tableCooperativa">

            <?php if(count($cooperativas) != 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th>E-mail</th>
                            <th>CNPJ</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cooperativas as $cooperativa): ?>
                            <tr>
                                <td><?= $cooperativa->nome ?></td>
                                <td><?= $cooperativa->endereco?></td>
                                <td><?= $cooperativa->email ?></td>
                                <td><?= formatarCNPJ($cooperativa->cnpj) ?></td>
                                <td>
                                    <a href="../process/atualizar_cooperativa.php?id=<?= $cooperativa->id ?>">
                                        <img src="../assets/img/icons/edit.png" title="Atualizar dado">
                                    </a>
                                    <a href="#" onclick="abrirModal(<?= $cooperativa->id ?>, 'cooperativa')">
                                        <img src="../assets/img/icons/cross.png" title="Deletar dado">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Não foram encontrados dados.</p>
            <?php endif; ?>
        </div>

        <div class="tabela" id="tableProdutor">
            <?php if(count($produtores) != 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>CPF</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtores as $produtor): ?>
                            <tr>
                                <td><?= $produtor->nome . ' ' . $produtor->sobrenome ?></td>
                                <td><?= $produtor->email ?></td>
                                <td><?= formatarTelefone(($produtor->telefone)) ?></td>
                                <td><?= formatarCPF($produtor->cpf) ?></td>
                                <td>
                                <a href="../process/atualizar_produtor.php?id=<?= $produtor->id ?>">
                                    <img src="../assets/img/icons/edit.png" title="Atualizar dado">
                                </a>
                                <a href="#" onclick="abrirModal(<?= $produtor->id ?>, 'produtor')">
                                    <img src="../assets/img/icons/cross.png" title="Deletar dado">
                                </a>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Não foram encontrados dados.</p>
            <?php endif; ?>
        </div>

        <div class="tabela" id="tableProdutos">
            <?php if(count($produtos) != 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Medida</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Imagem</th>
                            <th>Produtor</th>
                            <th>Lojas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtos as $produto): ?>
                            <tr>
                                <td><?= $produto->nome ?></td>
                                <td><?= $produto->categoria?></td>
                                <td><?= $produto->medida ?></td>
                                <td><?= $produto->quantidade ?></td>
                                <td><?= $produto->preco ?></td>
                                <td><img src="../<?= $produto->imagem ?>" alt="Imagem produto" width="150"></td>
                                <td><?= $produto->nome_produtor .' ' . $produto->sobrenome_produtor?></td>
                                <td><?= $produto->nome_lojas ?></td>
                                <td>
                                    <a href="../process/atualizar_produtos.php?id=<?= $produto->id ?>">
                                        <img src="../assets/img/icons/edit.png" title="Atualizar dado">
                                    </a>
                                    <a href="#" onclick="abrirModal(<?= $produto->id ?>, 'produto')">
                                        <img src="../assets/img/icons/cross.png" title="Deletar dado">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Não foram encontrados dados.</p>
            <?php endif; ?>
        </div>

        <div class="tabela" id="tableLojas">
            <?php if(count($lojas) != 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th>Descrição</th>
                            <th>Foto</th>
                            <th>Cooperativa</th>
                            <th>Ações</th>
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
                                <td>
                                    <a href="../process/atualizar_loja.php?id=<?= $loja->id ?>">
                                        <img src="../assets/img/icons/edit.png" title="Atualizar dado">
                                    </a>
                                    <a href="#" onclick="abrirModal(<?= $loja->id ?>, 'loja')">
                                        <img src="../assets/img/icons/cross.png" title="Deletar dado">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Não foram encontrados dados.</p>
            <?php endif; ?>
        </div>

    </section>

<!-- Modal -->
<div id="modal" class="modal">
  <div class="modal-conteudo">
    <p id="mensagem-modal">Tem certeza que deseja deletar?</p>
    <div class="modal-acoes">
      <button onclick="confirmar()">Confirmar</button>
      <button onclick="fecharModal()">Cancelar</button>
    </div>
  </div>
</div>

</main>

<script>
  const buttons = document.querySelectorAll(".btn");
  const tables = document.querySelectorAll(".tabela");

  function activateSection(targetId) {
    buttons.forEach(btn => btn.classList.remove('active'));
    tables.forEach(tbl => tbl.classList.remove('active'));

    const activeButton = document.querySelector(`[data-target="${targetId}"]`);
    const activeTable = document.getElementById(targetId);

    if (activeButton) activeButton.classList.add('active');
    if (activeTable) activeTable.classList.add('active');

    localStorage.setItem('abaAtiva', targetId);
  }

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-target');
      activateSection(targetId);
    });
  });

  window.addEventListener('DOMContentLoaded', () => {
    const ultimaAba = localStorage.getItem('abaAtiva') || 'tableCooperativa';
    activateSection(ultimaAba);
  });
</script>



<script>
let idSelecionado = null;
let tipoSelecionado = null;

function abrirModal(id, tipo) {
  idSelecionado = id;
  tipoSelecionado = tipo;

  document.getElementById('mensagem-modal').innerText =
    `Deseja eliminar ${tipo}?`;
  document.getElementById('modal').style.display = 'flex';
}

function fecharModal() {
  document.getElementById('modal').style.display = 'none';
  idSelecionado = null;
  tipoSelecionado = null;
}

function confirmar() {
  if (!idSelecionado || !tipoSelecionado) return;

  let urlBase = '../process/';
  switch (tipoSelecionado) {
    case 'cooperativa':
      window.location.href = `${urlBase}deletar_cooperativa.php?id=${idSelecionado}&delete=1`;
      break;
    case 'produtor':
      window.location.href = `${urlBase}deletar_produtor.php?id=${idSelecionado}&delete=1`;
      break;
    case 'produto':
      window.location.href = `${urlBase}deletar_produto.php?id=${idSelecionado}&delete=1`;
      break;
    case 'loja':
      window.location.href = `${urlBase}deletar_loja.php?id=${idSelecionado}&delete=1`;
      break;
  }
}
</script>


<?php 

?>