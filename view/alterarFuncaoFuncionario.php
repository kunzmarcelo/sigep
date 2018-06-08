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

include_once '../modell/FuncaoFuncionarioNovo.class.php';
$fun = new FuncaoFuncionarioNovo();
   $status = $fun->alterar("funcao_funcionario", "$campo='$valor'", "id='$id'");
   if($status == true){
       echo "deu certo";
   }else{
       echo 'não deu';
   }
   //echo $("lote", "data_final='$data_final',status='$valor'", "id='$cod'");