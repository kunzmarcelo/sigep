<?php

include_once "../../modell/BancoDadosPDO.class.php";

$lote = new BancoDadosPDO();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("producao", "status='1'", "id_producao='$cod'"); 
}