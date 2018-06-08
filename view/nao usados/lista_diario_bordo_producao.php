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
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kunz, Marcelo 2014">

        <title>Bem Vindos</title>

        <!-- Bootstrap Core CSS -->
        <link href="../bootstrap/assets/css/bootstrap.css" rel="stylesheet">
        <link href="../bootstrap/assets/css/picture.css" rel="stylesheet">


        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/default.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/assets/css/component.css">

        <script src="../bootstrap/assets/js/modernizr.js"></script>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#tblEditavel tbody tr td.editavel').dblclick(function () {
                    if ($('td > input').length > 0) {
                        return;
                    }
                    var conteudoOriginal = $(this).text();
                    var novoElemento = $('<input/>', {type: 'text', value: conteudoOriginal});
                    $(this).html(novoElemento.bind('blur keydown', function (e) {
                        var keyCode = e.which;
                        var conteudoNovo = $(this).val();
                        if (keyCode == 13 && conteudoNovo != '' && conteudoNovo != conteudoOriginal) {
                            var objeto = $(this);
                            $.ajax({
                                type: "POST",
                                url: "alterarDiarioBordo.php",
                                data: {
                                    id: $(this).parents('tr').children().first().text(),
                                    campo: $(this).parent().attr('title'),
                                    valor: conteudoNovo
                                },
                                success: function (result) {
                                    objeto.parent().html(conteudoNovo)
                                    $('body').append(result);
                                    location.reload();
                                },
                            })
                        }
                        if (e.type == "blur") {
                            $(this).parent().html(conteudoOriginal);
                        }
                    }));

                    $(this).children().select();
                })
            })
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Listagem Diário de Bordo</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!--<div class="row">-->

            <div class="panel-heading">
                <a href="../index.php" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Voltar Inicio</a>
                <a href="cadastra_diario_bordo_producao.php" class="btn btn-info"><span class='glyphicon glyphicon-plus'></span> Adicionar</a>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="post">                            
                    <div class="col-lg-6">                                                                           
                        <div class="form-group"> 
                            <label for="data">Data inicial:</label>
                            <input type="date" name="data" value="data" class="form-control" required="required" >
                        </div>
                        <div class="form-group">

                            <label for="data2">Data final:</label>
                            <input type="date" name="data2" value="data2" class="form-control" required="required" >
                        </div>
                        <div class="form-group">

                            <label for="id_funcionario" class="tamanho-fonte">Funcionário:</label><small> (Campo Obrigatório)</small>
                            <select name="id_funcionario" class="form-control" required="required" >                                       
                                <?php
                                echo "<option value='Selecione'>Selecione ...</option>";
                                include_once '../modell/Funcionario.class.php';
                                $fun = new Funcionario();
                                $matriz = $fun->listaFuncionario();

                                while ($dados = $matriz->fetchObject()) {
                                    if ($dados->ativo == true && $dados->departamento != 'Escritório') {
                                        $cod = $dados->id;
                                        $nome = $dados->nome;
                                        echo "<option value=" . $cod . ">" . $nome . "</option>";
                                    }
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <button name="enviar" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Enviar</button>
                            <button name="limpar" class="btn btn-danger" value="reset">Limpar</button>

                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-body">
                <div class="col-lg-6">  
                    <div class="form-group">
                        <form action="gerarPDF.php" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6 col-sm-4">
                                    <input type="submit" value="Salvar em PDF" class="btn btn-default">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary">            
                <div class="panel-heading" style="text-align: center;">
                    Diário de Bordo
                </div>
                <div class="table-responsive">                    
                    <table class="table table-hover" id="tblEditavel">
                        <thead>
                            <tr>
                                <th>Turno</th>
                                <th>Máquina</th>
                                <th>Produto</th>
                                <th>Função</th>
                                <th>Total Peças</th>
                                <th>N° boa</th>
                                <th>N° ruim</th>
                                <th>Valor</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['enviar'])) {
                                //echo 'chegou';
                                //$id_funcionario = $_POST['id_funcionario'];
                                $id_funcionario = $_POST['id_funcionario'];
                                $data_ini = $_POST['data'];
                                $data_fim = $_POST['data2'];
                                $data1 = explode("-", $data_ini);
                                $data2 = explode("-", $data_fim);
                                $data1 = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                $data2 = $data2[2] . '/' . $data2[1] . '/' . $data2[0];

                                //$data1 = $_POST['data1'];
                                if ($id_funcionario == '') {
                                    echo "<div class='alert alert-danger alert-dismissable'>
                                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                Você deve selecionar os campos acima.
                                            </div>";
                                } else {
                                    include_once "../modell/DiarioBordo.class.php";
                                    $diario = new DiarioBordo();
                                    $matriz = $diario->listaTodosDiarioCondicaoDataProduzidos($data_ini, $data_fim, $id_funcionario);
                                    if (empty($matriz)) {
                                        echo "<div class='alert alert-info alert-dismissable'>
                                                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                                        Não possui nenhum registro armazenado.
                                                    </div>";
                                    } else {
                                        $pecas_fabricadas = 0;
                                        $pecas_boas = 0;
                                        $pecas_ruin = 0;
                                        $pecas_fabricadasM = 0;
                                        $pecas_boasM = 0;
                                        $pecas_ruinM = 0;
                                        $funcionario = 0;
                                        $total = 0;

                                        while ($dados = $matriz->fetchObject()) {
                                            $DATALOTE = explode("-", $dados->data);
                                            $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];
                                            if ($dados->turno == 1) {
                                                $turno = 'Manhã';
                                            } else {
                                                $turno = 'Tarde';
                                            }

                                            $array = explode(";", $dados->funcao);

                                            echo "<tr>                                                            
                                                            <td>" . $turno . "</td>
                                                            <td title='id_maquina' class='editavel'>" . $dados->maquina . "</td>
                                                            <td title='id_produto' class='editavel'>" . $dados->descricao . "</td>
                                                            <td title='id_funcao' class='editavel'>";
                                            ?>
                                            <?php
                                            foreach ($array as $valores) {
                                                echo "<span class='label label-info'>" . $valores . "</span>";
                                            }
                                            ?>
                                            <?php
                                            echo "</td>                                                           
                                                            <td title='total_peca' class='editavel'><b>" . $dados->total_peca . "</b></td>
                                                            <td title='n_peca_boa' class='editavel'><b>" . $dados->n_peca_boa . "</b></td>
                                                            <td title='n_peca_ruim' class='editavel'><b>" . $dados->n_peca_ruim . "</b></td>
                                                            <td title='obs' class='editavel'>R$ " . number_format($dados->custo_funcao * $dados->total_peca, 2, ',', '') . "</td>                                                                                                                        
                                                            
                                                            
                                                        </tr>";
                                            if ($dados->turno == 2) {
                                                $turno = $turno;
                                                $pecas_fabricadas += $dados->total_peca;
                                                $pecas_boas += $dados->n_peca_boa;
                                                $pecas_ruin += $dados->n_peca_ruim;
                                            } else {
                                                $turnoM = $turno;
                                                $pecas_fabricadasM += $dados->total_peca;
                                                $pecas_boasM += $dados->n_peca_boa;
                                                $pecas_ruinM += $dados->n_peca_ruim;
                                            }
                                            $funcionario = $dados->nome;
                                            $total += $dados->custo_funcao * $dados->total_peca;
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!--</div>-->
                </div>
                <?php
                ob_start(); // Ativa o buffer de saida do PHP

                function CriaCodigo() { //Gera numero aleatorio
                    for ($i = 0; $i < 40; $i++) {
                        $tempid = strtoupper(uniqid(rand(), true));
                        $finalid = substr($tempid, -12);
                        return $finalid;
                    }
                }
                ?>
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="text-align: center;">
                        Relátorio Final
                    </div>
                    <div class="panel panel-primary">                                
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Turno</th>
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>                                    
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>                                    
                                    <td><?= $funcionario ?></td>                                    
                                    <td><?= $turno; ?></td>
                                    <td><?= $pecas_fabricadas; ?></td>                                    
                                    <td><?= $pecas_boas; ?></td>
                                    <td><?= $pecas_ruin; ?></td>

                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <div class="panel panel-primary">                                
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Turno</th>
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>                                    
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>                                    
                                    <td><?= $funcionario ?></td>                                    
                                    <td><?= $turnoM; ?></td>
                                    <td><?= $pecas_fabricadasM; ?></td>                                    
                                    <td><?= $pecas_boasM; ?></td>                                    
                                    <td><?= $pecas_ruinM; ?></td>                                    
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <div class="panel panel-primary">                                
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th>Período</th>                                    
                                    <th>Total de Peças</th>
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>
                                    <th>Total</th>
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>                                    
                                    <td><?= $data1 . ' à ' . $data2; ?></td>                                    
                                    <td><?= $pecas_fabricadas + $pecas_fabricadasM; ?></td>                                    
                                    <td><?= $pecas_boas + $pecas_boasM; ?></td>                                    
                                    <td><?= $pecas_ruin + $pecas_ruinM; ?></td>
                                    <td><b>R$ <?= number_format($total, 2, ',', ''); ?></b></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            }
            /* Captação de dados */
            $buffer = ob_get_contents(); // Obtém os dados do buffer interno
            $filename = "code.html"; // Nome do arquivo HTML
            file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
            ?>  

        </div>
        <script src="../ajax/deletar_diario_bordo.js"></script>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/bootstrap.js"></script>
    </body>
</html>