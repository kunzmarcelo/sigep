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
<?php include_once "./actionCabecalho.php"; ?>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    </head>
    <body>
        <div id="wrapper">
<?php require_once './actionfonteMenu.php'; ?>
            <div id="page-wrapper">

                 <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php
                            $url = $_SERVER['REQUEST_URI'];
                            $part = explode("/", $url);
                            $part[3];

                            include_once '../modell/Produto.class.php';
                            $con = new BancoDadosPDO();
                            $titulo = $con->listarUm("menu_filho", "link like '$part[3]'");
                            $resultado = $titulo->fetchObject();
                            ?>

                            <?= $resultado->nome ?>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="panel-body">
                        <div class="col-lg-6">                                                                                          
                            <form class="form-horizontal" method="post">                            
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
                                                $cod = $dados->id_funcionario;
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
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="panel-body">
                        <div class="col-lg-6">  
                            <form action="gerarPDF2.php" class="form-horizontal">
                                <div class="form-group">
                                    <input type="submit" value="Exportar em PDF" class="btn btn-default">

                                </div>
                            </form>
                        </div>
                    </div>
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
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align: center">
                        Listagem
                    </div>                   
                    <table class="table table-hover" id="tblEditavel">
                        <thead>
                            <tr>
                                <th>Data da Produção</th>
                                <th>N° peças boas</th>
                                <th>N° peças ruins</th>                                
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
                                    $matriz = $diario->listaTodosDiarioCondicaoAgupado($data_ini, $data_fim, $id_funcionario);
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
                                        while ($dados = $matriz->fetchObject()) {
                                            $DATALOTE = explode("-", $dados->data_trabalhada);
                                            $DATALOTE = $DATALOTE[2] . '/' . $DATALOTE[1] . '/' . $DATALOTE[0];


                                            echo "<tr>
                                                    <td>" . $DATALOTE . "</td>
                                                    <td>" . $dados->SOMAPECABOA . "</td>
                                                    <td>" . $dados->SOMAPECARUIM . "</td>                                                           
                                                </tr>";
                                            $pecas_boas += $dados->SOMAPECABOA;
                                            $pecas_ruin += $dados->SOMAPECARUIM;
                                            $funcionario = $dados->nome;
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!--</div>-->



                    <div class="panel panel-default">
                        <table class="table table-hover" id="tblEditavel">
                            <thead>
                                <tr>
                                    <th>Funcionário</th>                                
                                    <th>Período</th> 
                                    <th>Total de Peças Boas</th>
                                    <th>Total de Peças Ruins</th>                                    
                                </tr>
                            </thead>                            
                            <tbody>
                                <tr>
                                    <td><?= $funcionario ?></td>
                                    <td><?= $data1 . ' à ' . $data2; ?></td>
                                    <td><?= $pecas_boas; ?></td>
                                    <td><?= $pecas_ruin; ?></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>                    
                </div>
                <?php
            }
            /* Captação de dados */
            /* Captação de dados */
            $buffer = ob_get_contents(); // Obtém os dados do buffer interno
            $filename = "code.html"; // Nome do arquivo HTML
            file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
            ?> 

        </div>

        <script src="../ajax/deletar_diario_bordo.js"></script>
<?php require_once "./actionRodape.php"; ?>
    </body>
</html>