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
$update_date = date('Y-m-d H:i:s');
//var_dump($id);

include_once '../modell/Produto.class.php';
$prod = new Produto();
   echo $status = $prod->alterar("produto", "$campo='$valor',update_date='$update_date'", "id_produto='$id'");
   if($status == true){
       echo "alert('Deu Certo a alteração');";
   }else{
       echo "alert('Naõa deu Certo a alteração');";
   }
   //echo $("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
