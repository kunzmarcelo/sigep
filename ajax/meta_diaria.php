<?php

include_once "../modell/MetaDiaria.class.php";

$lote = new MetaDiaria();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->alterar("meta_diaria", "status=0", "id_meta='$cod'"); 
}