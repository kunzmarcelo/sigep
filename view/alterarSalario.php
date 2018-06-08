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

include_once '../modell/BancoDadosPDO.class.php';
$fun = new BancoDadosPDO();
   $status = $fun->alterar("controle_salarial", "$campo='$valor'", "id_salario='$id'");
   if($status == true){
       echo "<script>alert('Registro alterado com sucesso');</script>";
   }else{
       echo "<script>alert('Erro ao alterar o registro $id '->'.$campo '->' $valor');</script>";
   }
   //echo $("lote", "data_final='$data_final',status='$valor'", "id='$cod'");