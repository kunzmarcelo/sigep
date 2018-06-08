<?php
echo "<script>alert('chegou');</script>";
include_once "../modell/FuncaoNovo.class.php";

$fun = new FuncaoNovo();

if (isset($_GET['id'])) {    
    $id = $_GET['id'];
    echo $fun->excluirFuncao($id);
//    $data_final = date('Y-m-d');
    //echo $data_final;
   echo "<script>alert('ERRO ao alerar o registro');</script>";
    //echo $fun->excluirFuncao($id);
    //echo $lote->alterar("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
    //  echo "<script>alert();</script>";
    //echo $atleta->ativarAtletaControll($valor,$cod);
    //var_dump($atle);
}