<?php

include_once "../../modell/BancoDadosPDO.class.php";

$lote = new BancoDadosPDO();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("config_salario", "status_config_salario='false'", "id_config_salario='$cod'"); 
}