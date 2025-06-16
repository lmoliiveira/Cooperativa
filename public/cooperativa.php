<?php
include_once("../config/autenticacao.php");

$titulo_pagina = "Meu perfil";
use banco\Database;

include_once("../includes/header.php");
require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");
$database = new Database(MYSQL_CONFIG);

$cooperativas = $database->execute_query(
  "SELECT * FROM cooperativa WHERE id = ?",
  [$_SESSION['usuario_id']]
);
$cooperativas = $cooperativas->results[0];


$lojas = $database->execute_query(
    "SELECT lojas.*, cooperativa.nome AS nome_cooperativa 
     FROM lojas 
     JOIN cooperativa ON lojas.cooperativa_id = cooperativa.id 
     WHERE cooperativa.id = ?",
    [$_SESSION['usuario_id']]
);
$lojas = $lojas->results;


function mensagemRegistro($lista, $singular, $plural) {
    $qtd = count($lista);
    if ($qtd === 0) return "Nenhuma $singular registrada";
    if ($qtd === 1) return "1 $singular registrada";
    return "$qtd $plural registradas";
}


?>

<main class="perfil">

  <nav class="sidebar">
    <div class="dados">
      <div>
        <h4><?= $cooperativas->nome ?></h4>
      </div>

      <div>
        <p>CNPJ</p>
        <p><?= formatarCNPJ($cooperativas->cnpj) ?></p>
      </div>
      <div>
        <p>Endereço</p>
        <p><?= $cooperativas->endereco ?></p>
      </div>
      <div>
        <p>E-mail</p>
        <p><?= $cooperativas->email ?></p>
      </div>

      <div>
        <br>
        <h5><i><?= mensagemRegistro($lojas, 'loja', 'lojas') ?></i></h5>
      </div>
    </div>

    <div class="botao-perfil">
      <a href="#" onclick="abrirModal(<?= $cooperativas->id ?>, 'cooperativa')">Deletar</a >
      <a href="../process/atualizar_cooperativa.php?id=<?= $cooperativas->id ?>">Atualizar</a >
    </div>
  </nav>

  <section class="dashboard">
    <div class="tabela">
        <div class="botao_tabela">
            <br>
            <a href="../process/inserir_lojas.php">Adicionar Loja</a>
        </div>
        <br>
        
        <div>
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
                                <a href="#" onclick="abrirModal('<?= $loja->id ?>', 'loja')">
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
    </div>
  </section>


</main>   
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

<script>
  let idSelecionado = null;
  let tipoSelecionado = null;

  function abrirModal(id, tipo) {
    idSelecionado = id;
    tipoSelecionado = tipo;
    document.getElementById('mensagem-modal').innerText =
      `Deseja eliminar sua ${tipo}`;
    document.getElementById('modal').style.display = 'flex';
  }

  function fecharModal() {
    document.getElementById('modal').style.display = 'none';
    tipoSelecionado = null;
    idSelecionado = null;
  }

  function confirmar() {
    if (!idSelecionado || !tipoSelecionado) return;

    let urlBase = '../process/';
    switch (tipoSelecionado) {
      case 'cooperativa':
        window.location.href = `${urlBase}deletar_cooperativa.php?id=${idSelecionado}&delete=1`;
        break;
      case 'loja':
        window.location.href = `${urlBase}deletar_loja.php?id=${idSelecionado}&delete=1`;
        break;
    }
  }
</script>

<?php
include_once("../includes/footer.php");
?>