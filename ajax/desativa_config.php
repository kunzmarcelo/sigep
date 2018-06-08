<?php

include_once "../modell/Config.class.php";

$lote = new Config();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("config", "status='false'", "id_config='$cod'"); 
}