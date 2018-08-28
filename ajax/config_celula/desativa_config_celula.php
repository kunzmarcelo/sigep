<?php

include_once "../../modell/BancoDadosPDO.class.php";

$lote = new BancoDadosPDO();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("config_celula", "status='0'", "id_config='$cod'"); 
}