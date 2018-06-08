<?php

include_once "../modell/BancoDadosPDO.class.php";

$lote = new BancoDadosPDO();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("controle_salarial", "status_salario=1", "id_salario='$cod'"); 
}