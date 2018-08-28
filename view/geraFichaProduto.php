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
                                url: "alterarOperacao.php",
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

        <!-- Custom CSS -->


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

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
                    <div class="col-lg-6">
                        <div class="form-group">
                            <form role="form" method="post">                            
                                <div class="form-group">
                                    <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                    <select name="id_produto" id="id_produto" class="form-control" required="required">                                       
                                        <?php
                                        echo "<option value=''>Selecione ...</option>";
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
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="gerarFicha">Gerar Ficha</button>
                                </div>
                            </form>
                        </div>                              


                        <div class="form-group">
                            <form action="gerarPDF.php" class="form-horizontal">
                                <button type="submit" class="btn btn-default">Exportar para PDF</button>
                                <!--<input type="submit" value="Salvar em PDF" class="btn btn-default">-->
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


                <?php
                $id_produto = \filter_input(INPUT_POST, 'id_produto');
                $gerarFicha = \filter_input(INPUT_POST, 'gerarFicha');

                if (isset($gerarFicha)) {

                    include_once "../modell/Operacao.class.php";
                    $lote = new Operacao();
                    $matriz = $lote->listaOperacaoProduto($id_produto);

                    $matriz2 = $lote->listaOperacaoUmProduto($id_produto);
                    $dados2 = $matriz2->fetchObject();
                    ?>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="text-align: center">
                                Ficha do Produto <?= $dados2->descricao ?>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="col-lg-4">
                                    <div class="well well-lg">
                                        <?php
                                        if (empty($dados2->imagem)) {
                                            echo" <div class='alert alert-warning' role='alert'>
                                               <h5> <span class='glyphicon glyphicon-warning-sign'></span> Oops! A imagem não pode ser exibida.</h5>
                                            </div>";
                                            ?>
                                            <form method="post" role="form" method="post" enctype="multipart/form-data">
                                                <div class="form-group">                                        
                                                    <label for="imagem">Imagem:</label>
                                                    <input type="file" id="imagem" name="imagem">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                    <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                                </div>
                                            </form>

                                            <?php
                                            include_once "../modell/Produto.class.php";

//instancia a classe de controle
                                            $prod = new Produto();
                                            $foto = $_FILES['imagem'];
                                            if (!empty($foto["name"])) {

                                                // Largura máxima em pixels
                                                $largura = 15000;
                                                // Altura máxima em pixels
                                                $altura = 18000;
                                                // Tamanho máximo do arquivo em bytes
                                                $tamanho = 100000;

                                                $error = array();

                                                // Verifica se o arquivo é uma imagem
                                                if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])) {
                                                    $error[1] = "Isso não é uma imagem.";
                                                }

                                                // Pega as dimensões da imagem
                                                $dimensoes = getimagesize($foto["tmp_name"]);

                                                // Verifica se a largura da imagem é maior que a largura permitida
                                                if ($dimensoes[0] > $largura) {
                                                    $error[2] = "A largura da imagem não deve ultrapassar " . $largura . " pixels";
                                                }

                                                // Verifica se a altura da imagem é maior que a altura permitida
                                                if ($dimensoes[1] > $altura) {
                                                    $error[3] = "Altura da imagem não deve ultrapassar " . $altura . " pixels";
                                                }

                                                // Verifica se o tamanho da imagem é maior que o tamanho permitido
                                                if ($foto["size"] > $tamanho) {
                                                    $error[4] = "A imagem deve ter no máximo " . $tamanho . " bytes";
                                                }

                                                // Se não houver nenhum erro
                                                if (count($error) == 0) {

                                                    // Pega extensão da imagem
                                                    preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

                                                    // Gera um nome único para a imagem
                                                    $nome_imagem = md5(uniqid(time())) . "." . $ext[1];

                                                    // Caminho de onde ficará a imagem
                                                    $url = "../bibliotecas/produtos/";
                                                    $caminho_imagem = "../bibliotecas/produtos/" . $nome_imagem;
                                                    //sinclude '../bibliotecas/produtos/';
                                                    // Faz o upload da imagem para seu respectivo caminho
                                                    move_uploaded_file($foto["tmp_name"], $caminho_imagem);
                                                }
                                            } else {
                                                $nome_imagem = NULL;
                                            }
                                            $status = $prod->alterar("produto", "imagem='$url.$nome_imagem'", "id_produto=$dados2->id_produto");
                                        } else {
                                            ?>
                                            <img src='<?= $dados2->imagem ?>' class='img-thumbnail'>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="well well-lg">
                                        <table class="table table-hover">
                                            <thead style="text-align: right">
                                                <tr>                                            
                                                    <th>Operação</th>                                            
                                                    <th>Tempo de Ciclo</th>
                                                    <th>Setor de Operação</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                while ($dados = $matriz->fetchObject()) {
                                                    $tempo_producao2 = explode(":", $dados->tempo_operacao);

                                                    if ($dados->descricao_setor == 'Inicio') {
                                                        $setor = "<span class='label label-default'>$dados->descricao_setor</span>";
                                                    } elseif ($dados->descricao_setor == 'Costura') {
                                                        $setor = "<span class='label label-info'>$dados->descricao_setor</span>";
                                                    } elseif ($dados->descricao_setor == 'Acabamento') {
                                                        $setor = "<span class='label label-primary'>$dados->descricao_setor</span>";
                                                    } elseif ($dados->descricao_setor == 'Finalização') {
                                                        $setor = "<span class='label label-success'>$dados->descricao_setor</span>";
                                                    }

                                                    echo "<td title='operacao' class='editavel'><b> " . $dados->operacao . "</b></td>
                                                            <td title='tempo_operacao' class='editavel'> " . $dados->tempo_operacao . "</td>
                                                            <td title='setor_operacao' class='editavel'> " . $setor . "</td>
                                                        </tr>";
                                                }



                                                $matriz3 = $lote->somaTempoOperacao($id_produto);
                                                $dados3 = $matriz3->fetchObject();
                                                $total_segundos = $dados3->TOTAL;
                                                ?>

                                            </tbody>
                                        </table>

                                        <table class="table table-hover">
                                            <thead style="text-align: right">
                                                <tr>
                                                    <th>Soma do tempo de ciclo</th>                                            
                                                </tr>
                                            </thead>                                    
                                            <tbody>
                                                <tr>
                                                    <td><?= $dados3->TOTAL; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <?php
                                    }
                                    ?>


                                </div>
                            </div>
                            <!-- /.col-lg-4 -->

                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>

            <?php
            /* Captação de dados */
            $buffer = ob_get_contents(); // Obtém os dados do buffer interno
            $filename = "code.html"; // Nome do arquivo HTML
            file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
            ?>
        </div>

<?php require_once "./actionRodape.php"; ?>
    </body>
</html>