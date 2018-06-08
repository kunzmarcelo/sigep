<?php

include_once '../modell/FuncaoNovo.class.php';

$funcao = new FuncaoNovo();
$id_celula = $_REQUEST['id_celula']; 
$id_produto = $_REQUEST['id_produto'];

if($id_celula == '1'){
    $setor = 0;
}
if($id_celula == '4'){
    $setor = 1;
}
if($id_celula == '2'){
    $setor = 2;
}
if($id_celula == '8'){
    $setor = 3;
}

$matriz = $funcao->listaFuncaoProdutoSetor($id_produto, $setor);

$funcaoArray = array();

while ($dados = $matriz->fetchObject()) {
    if ($dados->status_funcao == true) {
        $funcaoArray[] = array(
            "id_funcao" => $dados->id_funcao,
            "funcao" => $dados->funcao,
            "tempo" => $dados->tempo,
            "id_produto" => $dados->id_produto,
            "setor" => $dados->setor,
            "status" => $dados->status_funcao,
        );
    }
    //echo "<option value=" . $cod . ">" . $nome . "</option>";
}
echo( json_encode($funcaoArray) );
