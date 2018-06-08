<?php

session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php"; {
    include_once "../view/funcoes.php";
}
controlaAcessoUrl($url, $pagina);
//expiraSessao();
/*
 * 
 */
?>


<?php

$id = $_POST['id'];
$campo = $_POST['campo'];
$valor = $_POST['valor'];
//echo $id . '->' . $campo . '->' . $valor;
//var_dump($id);
if ($campo == 'status' && $valor >= '4') {
    echo "<script Language='JavaScript'>alert('ERRO: O valor informado está incorreto. Informe: 0 - Aberto; 1 - Em Produção; 2 - Finalizado; 3 - Proximo Dia;');</script>";
} else {


    include_once '../modell/DetalheCelulaProduto.class.php';
    $fun = new DetalheCelulaProduto();
    $status = $fun->alterar("detalhe_celula_produto", "$campo='$valor'", "id='$id'");
    if ($status == true) {
        echo "deu certo";
    } else {
        echo 'não deu';
    }
}
   //echo $("lote", "data_final='$data_final',status='$valor'", "id='$cod'");
