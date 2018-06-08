<?php

include_once "../modell/Lote.class.php";

$lote = new Lote();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("lote", "status='0'", "id_lote='$cod'");
}