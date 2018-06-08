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

$id_funcao = $_POST['id'];
$campo = $_POST['campo'];
$valor = $_POST['valor'];
//echo $id_funcao.'->'.$campo .'->'.$valor;
//var_dump($id);

include_once '../modell/Operacao.class.php';
$fun = new Operacao();
   $status = $fun->alterar("operacao", "$campo='$valor'", "id_operacao='$id_funcao'");
   if($status == true){
       
       echo "<script>alert('Registro alterado com sucesso');</script>";
       //echo "deu certo";
   }else{
       echo "<script>alert('ERRO ao alerar o registro');</script>";
   }
   //echo $("lote", "data_final='$data_final',status='$valor'", "id='$cod'");