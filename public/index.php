<?php
$titulo_pagina = "Cooperativa";

include_once("../includes/header.php");
?>


<main class="container-categorias-circulares">
  <h3 class="titulo-categorias">Categorias</h3>
  <div class="categorias-circulares-grid">

    <a href="produtos.php?categoria=Laticínio" class="categoria-item">
      <img src="../assets/img/templates/laticinios.png" alt="Laticínios">
      <span>Laticínios</span>
    </a>

    <a href="produtos.php?categoria=Hortaliça" class="categoria-item">
      <img src="../assets/img/templates/hortalicas.jpg" alt="Hortaliças">
      <span>Hortaliças</span>
    </a>

    <a href="produtos.php?categoria=Fruta" class="categoria-item">
      <img src="../assets/img/templates/medifrutas.jpg" alt="Frutas">
      <span>Frutas</span>
    </a>

    <a href="produtos.php?categoria=Grão" class="categoria-item">
      <img src="../assets/img/templates/graos.png" alt="Grãos">
      <span>Grãos</span>
    </a>

    <a href="produtos.php?categoria=Carne" class="categoria-item">
      <img src="../assets/img/templates/carnes.jpg" alt="Carnes">
      <span>Carnes</span>
    </a>

    <a href="produtos.php?categoria=Café" class="categoria-item">
      <img src="../assets/img/templates/cafejpg.jpg" alt="Café">
      <span>Café</span>
    </a>

  </div>
</main>


<?php
include_once("../includes/footer.php");
?>