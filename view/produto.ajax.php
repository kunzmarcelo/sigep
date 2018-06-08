<?php

include_once '../modell/Produto.class.php';

$funcao = new Produto();
$id_produto = $_REQUEST['id_produto'];
$matriz = $funcao->listarUm("produto","$id_produto");

$funcaoArray = array();

while ($dados = $matriz->fetchObject()) {
    $funcaoArray[] = array(
        "id_produto" => $dados->id_produto,
        "descricao" => $dados->descricao,
        
    );
    //echo "<option value=" . $cod . ">" . $nome . "</option>";
}
echo( json_encode($funcaoArray) );


