<?php

include_once "../../modell/BancoDadosPDO.class.php";

$fun = new BancoDadosPDO();

if (isset($_GET['id'])) {    
    $id = $_GET['id'];
//    $data_final = date('Y-m-d');
    //echo $data_final;
    echo $fun->excluir("detalhe_funcionario_produto","id = $id");
    //echo $lote->alterar("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
    //  echo "<script>alert();</script>";
    //echo $atleta->ativarAtletaControll($valor,$cod);
    //var_dump($atle);
}