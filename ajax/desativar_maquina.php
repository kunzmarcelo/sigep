<?php

include_once "../modell/Maquina.class.php";

$lote = new Maquina();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("maquina", "status='0'", "id_maquina='$cod'");
}