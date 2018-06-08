<?php

include_once "../modell/DiarioBordo.class.php";

$fun = new DiarioBordo();

if (isset($_GET['id'])) {    
    $id = $_GET['id'];
//    $data_final = date('Y-m-d');
    //echo $data_final;
    echo $fun->excluirDiarioBordo($id);
    //echo $lote->alterar("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
    //  echo "<script>alert();</script>";
    //echo $atleta->ativarAtletaControll($valor,$cod);
    //var_dump($atle);
}