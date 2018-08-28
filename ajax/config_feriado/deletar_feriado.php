<?php

include_once "../../modell/BancoDadosPDO.class.php";

$lote = new BancoDadosPDO();
if(isset($_GET['id'])){
   $cod = $_GET['id'];
   echo $lote->excluir("config_feriado", "id_config_feriado='$cod'");
}