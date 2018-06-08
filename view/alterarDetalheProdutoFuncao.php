<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
     include_once "../view/funcoes.php"; {
    include_once "../view/funcoes.php";
}
controlaAcessoUrl($url, $pagina);
//expiraSessao();
/*
 * 
 */
?>
<?php

$id = $_POST['id'];
$campo = $_POST['campo'];
$valor = $_POST['valor'];
echo $id.'->'.$campo .'->'.$valor;
//var_dump($id);

include_once '../modell/DetalheProdutoFuncao.php';
$fun = new DetalheProdutoFuncao();
   $status = $fun->alterar("detalhe_produto_funcao", "$campo='$valor'", "id='$id'");
   if($status == true){
       echo "deu certo";
   }else{
       echo 'não deu';
   }
   //echo $("lote", "data_final='$data_final',status='$valor'", "id='$cod'");