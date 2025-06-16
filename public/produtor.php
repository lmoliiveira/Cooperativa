<?php
include_once("../config/autenticacao.php");

$titulo_pagina = "Meu perfil";
use banco\Database;

include_once("../includes/header.php");
require_once("../includes/tratar_dados.php");
require_once("../config/Database.php");
require_once("../config/config.php");
$database = new Database(MYSQL_CONFIG);

$produtores = $database->execute_query("SELECT * FROM produtor WHERE id = ?", [$_SESSION['usuario_id']]);
$produtores = $produtores->results[0];

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
WHERE produtos.produtor_id = ?
GROUP BY produtos.id, nome_produtor, sobrenome_produtor", [$_SESSION['usuario_id']]);

$produtos = $produtos->results;

function mensagemRegistro($lista, $singular, $plural) {
  $qtd = count($lista);
  if ($qtd === 0) return "Nenhum $singular registrado";
  if ($qtd === 1) return "1 $singular registrado";
  return "$qtd $plural registrados";
}
?>

<main class="perfil">

  <nav class="sidebar">
    <div class="dados">
      <div>
        <h4><?= $produtores->nome . ' ' . $produtores->sobrenome?></h4>
      </div>

      <div>
        <p>E-mail</p>
        <p><?= $produtores->email ?></p>
      </div>

      <div>
        <p>Telefone</p>
        <p><?= formatarTelefone($produtores->telefone) ?></p>
      </div>

      <div>
        <p>CPF</p>
        <p><?= formatarCPF($produtores->cpf) ?></p>
      </div>
      
      <div>
        <br>
        <h5><i><?= mensagemRegistro($produtos, 'produto', 'produtos') ?></i></h5>
      </div>
    </div>

    <div class="botao-perfil">
      <a href="#" onclick="abrirModal('<?= $produtores->id ?>', 'perfil')">Deletar</a >
      <a href="../process/atualizar_produtor.php?id=<?= $produtores->id ?>">Atualizar</a >
    </div>
  </nav>

  <section class="dashboard">
    <div class="tabela">
      <div class="botao_tabela">
        <br>
        <a href="../process/inserir_produtos.php">Adicionar Produto</a>
      </div>
      <br>

      <div>
        <?php if(count($produtos) != 0): ?>
          <br>
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
                  <td><img src="../<?= $produto->imagem ?>" alt="Imagem produto" width="50"></td>
                  <td><?= $produto->nome_produtor .' ' . $produto->sobrenome_produtor?></td>
                  <td><?= $produto->nome_lojas ?></td>
                  <td>
                    <a href="../process/atualizar_produtos.php?id=<?= $produto->id ?>">
                      <img src="../assets/img/icons/edit.png" title="Atualizar dado">
                    </a>
                    <a href="#" onclick="abrirModal('<?= $produto->id ?>', 'produto')">
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
      `Deseja eliminar seu ${tipo}`;
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
      case 'produto':
        window.location.href = `${urlBase}deletar_produtos.php?id=${idSelecionado}&delete=1`;
        break;
      case 'perfil':
        window.location.href = `${urlBase}deletar_produtor.php?id=${idSelecionado}&delete=1`;
        break;
    }
  }
</script>


<?php
include_once("../includes/footer.php");
?>