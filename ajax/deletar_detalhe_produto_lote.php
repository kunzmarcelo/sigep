<?php

include_once "../modell/DetalheLoteProduto.class.php";

$lote = new DetalheLoteProduto();

if (isset($_GET['id'])) {
    $cod = $_GET['id'];
    echo $lote->excluir("detalhe_lote_produto","id='$cod'");
    //  echo "<script>alert();</script>";
    //echo $atleta->ativarAtletaControll($valor,$cod);
    //var_dump($atle);
}