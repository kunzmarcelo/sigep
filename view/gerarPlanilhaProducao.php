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
<!--        <script type="text/javascript">
        window.print();
        //window.close(); Só descomente esta linha se tiver o modo kiosk habilitado
    </script>-->


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
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center">
                                Gerar Planilha de Produção
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" role="form">
                                            <div class="form-group"> 
                                                <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                                <select name="id_produto" id="id_produto" class="form-control" required="required" onchange="this.form.submit()">
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    include_once '../modell/Produto.class.php';
                                                    $fun = new Produto();
                                                    $matriz = $fun->listaProduto();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status == true) {
                                                            $cod = $dados->id_produto;
                                                            $nome = $dados->descricao;
                                                            echo "<option value=" . $cod . ">" . $nome . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                        </form>
                                    </div>                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group"> 
                                            <button type="button" value="Imprimir" class="btn btn-info" id="btn"> Imprimir</button>
                                            <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->

                                        </div>
                                    </div>
                                </div>

                                <?php
                                $id_produto = \filter_input(INPUT_POST, 'id_produto');
//                                   
                                if (empty($id_produto)) {
                                    echo" <div class='alert alert-warning' role='alert'>
                                            <h4> <span class='glyphicon glyphicon-warning-sign'></span> Oops! Selecione um produto.</h4>
                                        </div>";
                                } else {
                                    include_once "../modell/Produto.class.php";
                                    $listaUm = new Produto();
                                    $resultado = $listaUm->listarUm("produto", "id_produto = $id_produto");
                                    $dados = $resultado->fetchObject();
                                    $produto = $dados->descricao;
                                    ?>


                                    <div id="impressao">
                                        <div class="panel panel-default">
                                            <h2>Ficha de produção Lüger Confecções</h2>
                                            <div class="panel-heading">
                                                Produto:<b><i>( <?= $produto; ?> )</i></b> Lote:________________ Quantidade:___________________ Data:______/______/_______
                                            </div>
                                            <div class="table-responsive">                       
                                                <table class="table table-hover table-bordered table-condensed" width="100%" id="tblEditavel" border="1px solid">
                                                    <thead>
                                                        <tr>
                                                            <th>Operação</th>                                                            
                                                            <?php
                                                            include_once "../modell/Funcionario.class.php";
                                                            $fun = new Funcionario();
                                                            $matriz = $fun->listaFuncionarioStatus('1');
                                                            $resultadoNome = $matriz->fetchAll();
                                                            $contadorNomes = count($resultadoNome);

                                                            foreach ($resultadoNome as $res) {
                                                                $nome = explode(" ", $res['nome']);
                                                                echo "<th style='width:80px'>$nome[0]</th>";
                                                            }

                                                            //echo $contadorNomes
                                                            ?>                                                          
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <?php
                                                        include_once "../modell/Operacao.class.php";
                                                        $lote = new Operacao();
                                                        $matriz = $lote->listaOperacaoProduto($id_produto);

                                                        $resultado = $matriz->fetchAll();

                                                        //echo $contador;
                                                        foreach ($resultado as $res) {
                                                            if ($res['descricao_setor'] == 'Costura') {
                                                                echo"  <td>" . mb_strtoupper($res['operacao']) . "</td>";
                                                                for ($i = 1; $i <= $contadorNomes; $i++) {
                                                                    echo "<td></td>";
                                                                }
                                                            }
                                                            echo "</tr>";                                                            
                                                            echo "<tr>";
                                                            if ($res['descricao_setor'] == 'Acabamento') {
                                                                echo"<td>" . mb_strtoupper($res['operacao']) . "</td>";
                                                                for ($i=1; $i <= $contadorNomes; $i++) {
                                                                    echo "<td></td>";
                                                                }
                                                            }
                                                            echo "</tr>";
                                                            echo "<tr>";
                                                            if ($res['descricao_setor'] == 'Finalização') {
                                                                echo"<td>" . mb_strtoupper($res['operacao']) . "</td>";
                                                                for ($i=1; $i <= $contadorNomes; $i++) {                                                                                                                                       
                                                                    echo "<td></td>";

                                                                }
                                                            }
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>



<?php require_once "./actionRodape.php"; ?>
    </body>
</html>