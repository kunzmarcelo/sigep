<?php

include_once "../modell/Funcionario.class.php";

$fun = new Funcionario();
$ativo = 0;
if (isset($_GET['id'])) {
    $cod = $_GET['id'];
    $data_final = date('Y-m-d');
    //echo $data_final;
    echo $fun->alterar("funcionario", "ativo='$ativo',update_date='$data_final'", "id_funcionario='$cod'");
    //echo $lote->alterar("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
    //  echo "<script>alert();</script>";
    //echo $atleta->ativarAtletaControll($valor,$cod);
    //var_dump($atle);
}