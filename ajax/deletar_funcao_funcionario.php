<?php

include_once "../modell/FuncaoFuncionario.class.php";

$fun = new FuncaoFuncionario();

if (isset($_GET['id'])) {    $cod = $_GET['id'];
//    $data_final = date('Y-m-d');
    //echo $data_final;
    echo $fun->excluir("funcao_funcionario", "id='$cod'");
    //echo $lote->alterar("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
    //  echo "<script>alert();</script>";
    //echo $atleta->ativarAtletaControll($valor,$cod);
    //var_dump($atle);
}