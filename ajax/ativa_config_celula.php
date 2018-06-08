<?php

include_once "../modell/ConfigCelula.class.php";

$lote = new ConfigCelula();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("config_celula", "status='1'", "id_config='$cod'"); 
}