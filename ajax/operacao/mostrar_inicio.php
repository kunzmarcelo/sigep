<?php

include_once "../../modell/BancoDadosPDO.class.php";

$lote = new BancoDadosPDO();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("operacao", "status_operacao ='1'","id_produto ='$cod' AND setor_operacao='Inicio'"); 
}