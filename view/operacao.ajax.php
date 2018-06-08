<?php

include_once '../modell/Operacao.class.php';

$funcao = new Operacao();
$id_produto = $_REQUEST['id_produto'];
$matriz = $funcao->listaOperacaoProduto($id_produto);

$funcaoArray = array();

while ($dados = $matriz->fetchObject()) {
    $funcaoArray[] = array(
        "id_operacao" => $dados->id_operacao,
        "operacao" => $dados->operacao,
        "id_produto" => $dados->id_produto,
    );
    //echo "<option value=" . $cod . ">" . $nome . "</option>";
}
echo( json_encode($funcaoArray) );


