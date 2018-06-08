<?php
session_start();

$url = basename($_SERVER['SCRIPT_FILENAME']);
$pagina = basename(__FILE__);
if ($url != 'index.php')
    include_once "../view/funcoes.php";
{
    include_once "../view/funcoes.php";
}
controlaAcessoUrl($url, $pagina);
//expiraSessao();
/*
 * 
 */
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kunz, Marcelo 2016">

        <title>Bem Vindos</title>

        <!-- Bootstrap Core CSS -->
        <link href="../bootstrap/assets/css/bootstrap.css" rel="stylesheet">
        <link href="../bootstrap/assets/css/picture.css" rel="stylesheet">


        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/default.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/component.css">

        <script src="../bootstrap/assets/js/modernizr.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>

    </head>
    <body>
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h1 class="page-header">Controle de Faturamento da Fábrica</h1>-->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="panel-heading">
                    <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                    <a href="lista_producao_fabrica.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Cadastro de Lote
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                <div class="col-lg-6">                                                                           
                                    <div class="form-group">
                                        <label for="lote1" class="tamanho-fonte">Lote:</label><small> (Campo Obrigatório)</small>
                                        <select name="lote1" class="form-control" required="required" >                                       
                                            <?php
                                            echo "<option value=''>Selecione ...</option>";
                                            include_once '../modell/Lote.class.php';
                                            $lote = new Lote();
                                            $matriz = $lote->listaLote();

                                            while ($dados = $matriz->fetchObject()) {
                                                if ($dados->status == false) {
                                                    $cod = $dados->id_lote;
                                                    $n_lote = $dados->numero;
                                                    $descricao = $dados->descricao;
                                                    echo "<option value=" . $n_lote . ">" . $n_lote . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="n_peca">Número de peças do lote:</label>
                                        <input type="number" id="n_peca" name="n_peca" class="form-control"  required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="valor">Faturamento do lote:</label>
                                        <input type="text" id="valor" name="valor" class="form-control" placeholder="" required="required"  />
                                    </div>
                                    <div class="form-group">
                                        <label for="descricao">Descrição do lote:</label>                                                                                    
                                        <input type="text" id="descricao" name="descricao" class="form-control" placeholder="descrição"   />                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="data_ini">Data inicial do lote:</label>                                                                                    
                                        <input type="date" id="data_ini" name="data_ini" class="form-control" required="required"/>                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="data_fim">Data final do lote:</label>                                                                                    
                                        <input type="date" id="data_fim" name="data_fim" class="form-control" required="required"/>                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_peca">Meta de peças mês:</label>                                                                                    
                                        <input type="text" id="meta_peca" name="meta_peca" value="1500" class="form-control"  required="required"/>                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_faturamento">Meta de faturamento mês:</label>                                                                                    
                                        <input type="text" id="meta_faturamento" name="meta_faturamento" value="100000" class="form-control" required="required"/>                                        
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <?php
                        include_once "../modell/ProducaoFabrica.class.php";

//instancia a classe de controle
                        $prod = new ProducaoFabrica();

                        $descricao = \filter_input(INPUT_POST, 'descricao');
                        $numero = \filter_input(INPUT_POST, 'lote1');
                        $data_ini = \filter_input(INPUT_POST, 'data_ini');
                        $data_fim = \filter_input(INPUT_POST, 'data_fim');
                        $n_peca = \filter_input(INPUT_POST, 'n_peca');
                        $valor = \filter_input(INPUT_POST, 'valor');
                        $meta_peca = \filter_input(INPUT_POST, 'meta_peca');
                        $meta_faturamento = \filter_input(INPUT_POST, 'meta_faturamento');

                        $status = true;
//                       
                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {
                            if (empty($numero)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                $status = $prod->cadastraProducao($numero, $descricao, $data_ini, $data_fim, $n_peca, $meta_peca, $valor, $meta_faturamento, $status);
                                if ($status == true) {
                                    echo "<div class='alert alert-info alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Registro inserido com sucesso.
                                            </div>";
                                } else {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                    Erro ao inserir o resgistro.
                                                </div>";
                                }
                            }
                        }
                        ?>                       
                    </div>
                </div>
            </div>
        </div>

        <script src="../bootstrap/assets/js/jquery.mask.min.js"></script>

        <script>
            $(document).ready(function () {
                //                $('.date').mask('11/11/1111');
                $('#tempo_producao').mask('00:00:00');
                $('#mao_obra').mask('0.00');
                //                $('.date_time').mask('99/99/9999 00:00:00');
                //                $('.cep').mask('99999-999');
                //                $('.phone').mask('9999-9999');
                //                $('.phone_with_ddd').mask('(99) 9999-9999');
                //                $('.phone_us').mask('(999) 999-9999');
                //                $('.mixed').mask('AAA 000-S0S');
            });
        </script>

        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <!-- For the demo ad only -->   

        <script src="../bootstrap/assets/js/bootstrap.js"></script>       
    </body>
</html>