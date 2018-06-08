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
                                url: "alterarMenuPai.php",
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
                                Fomulário de cadastro
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" role="form">
                                            <div class="form-group">
                                                <label for="descricao_pai">Descrição de Categoria:</label>
                                                <input type="text" id="descricao_pai" name="descricao_pai" class="form-control" required="required" placeholder="Ex: Produtos"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="ordem_pai">Ordem de exibição:</label>
                                                <input type="text" id="ordem_pai" name="ordem_pai" class="form-control" required="required" placeholder="Ex: 1,2,3"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="icone_pai">Icone da Categoria:</label>
                                                <input type="text" id="icone_pai" name="icone_pai" class="form-control" required="required" placeholder="Ex: fa fa-archive ou glyphicon glyphicon-barcode"/>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                            </div> 
                                        </form>
                                    </div>
                                </div>
                                <?php
                                include_once "../modell/MenuPai.class.php";

//instancia a classe de controle
                                $prod = new MenuPai();

                                $nome_pai = \filter_input(INPUT_POST, 'descricao_pai');
                                $ordem_pai = \filter_input(INPUT_POST, 'ordem_pai');
                                $icone_pai = \filter_input(INPUT_POST, 'icone_pai');
                                $status_pai = true;
                                $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                if (isset($cadastro)) {
                                    if (empty($nome_pai)) {
                                        echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                        //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                    } else {
                                        $status1 = $prod->cadastraMenuPai($nome_pai, $status_pai, $ordem_pai,$icone_pai);
                                        if ($status1 == true) {
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


                        <div class="row">
                            <div class="col-lg-12">
                                <!-- /.panel-heading -->


                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align: center">
                                        Listagem
                                    </div>                  
                                    <table class="table table-hover" id="tblEditavel">
                                        <thead>
                                            <tr>
                                                <th>#</th>                                        
                                                <th>Nome</th>
                                                <th>Ordem</th>
                                                <th>Icone</th>
                                                <th><i class="fa fa-low-vision"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>                       

                                            <?php
                                            include_once "../modell/MenuPai.class.php";
                                            $lote = new MenuPai();
                                            $matriz = $lote->listaMenuPai();

                                            while ($dados = $matriz->fetchObject()) {

                                                if ($dados->status_pai == FALSE) {
                                                    echo "<tr>
                                                    <td title='id_menu'>" . $dados->id_menu . "</td>                                                   
                                                    <td title='nome_pai' class='editavel'>" . $dados->nome_pai . "</td>                                                   
                                                    <td title='ordem_pai' class='editavel'>" . $dados->ordem_pai . "</td>                                                   
                                                    <td title='icone_pai' class='editavel'><span class= '$dados->icone_pai'  ></span></td>                                                  
                                                    
                                                    <td>
                                                       <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar' onclick='ativar(" . $dados->id_menu . ");'></span>                                                       
                                                    </td>
                                              </tr>";
                                                } else {
                                                    echo "<tr>
                                                   <td title='id_menu'>" . $dados->id_menu . "</td>                                                   
                                                    <td title='nome_pai' class='editavel'>" . $dados->nome_pai . "</td>  
                                                    <td title='ordem_pai' class='editavel'>" . $dados->ordem_pai . "</td>  
                                                    <td title='icone_pai' class='editavel'><span class= '$dados->icone_pai'  ></span></td> 
                                                    <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'   onclick='finalizar(" . $dados->id_menu . ");'></span> 													
                                                    </td>
                                              </tr>";
                                                }
                                            }//<button title='finalizar lote' type='submit' id='ativar' value='ativar'  onclick='ativar(" . $dados->id . ");' class='btn btn-default'> </button>
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!--</div>-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="../ajax/menu/finalizar_menu_pai.js"></script>
        <script src="../ajax/menu/ativa_menu_pai.js"></script>

        <?php require_once "./actionRodape.php"; ?>

    </body>
</html>