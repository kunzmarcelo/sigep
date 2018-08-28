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
                                url: "alterarMenuFilho.php",
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

                <div class="panel panel-default">

                    <div class="panel-heading" style="text-align: center">
                        Fomulário de cadastro
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="post" role="form">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_menu" class="tamanho-fonte">Menu Pai:</label><small> (Campo Obrigatório)</small>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-road"></i></span>
                                                <select name="id_menu" class="form-control" required="required" >                                       
                                                    <?php
                                                    echo "<option value=''>Selecione ...</option>";
                                                    include_once '../modell/MenuPai.class.php';
                                                    $lote = new MenuPai();
                                                    $matriz = $lote->listaMenuPai();

                                                    while ($dados = $matriz->fetchObject()) {
                                                        if ($dados->status_pai == TRUE) {
                                                            $cod = $dados->id_menu;
                                                            $descricao = $dados->nome_pai;
                                                            echo "<option value=" . $cod . ">" . $descricao . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="descricao">Descrição:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-road"></i></span>
                                                <input type="text" id="descricao" name="descricao" class="form-control" required="required" placeholder="Ex: Cadastro de Produtos"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="link">Link:</label><small> (Somente o nome do arquivo e extensão.)</small>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                <input type="text" id="link" name="link" class="form-control" required="required" placeholder="Ex: cadastro_produto.php"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="icone">Icone:</label>
                                            <select name="icone" id="icone" class="form-control" style="font-family: FontAwesome, sans-serif;" required="required">
                                                <option  aria-hidden='true' value="" >Selecione...</option>
                                                <option  aria-hidden='true' value="fa fa-list" >&#xf03a; Listagem</option>
                                                <option  aria-hidden='true' value="fa fa-edit">&#xf044;	Cadastro</option>
                                                <option  aria-hidden='true' value="fa fa-line-chart">&#xf201; Gráficos</option>
                                                <option  aria-hidden='true' value="fa fa-table">&#xf0ce; Listagem</option>
                                                <option  aria-hidden='true' value="fa fa-cogs">&#xf085; Configurações</option>
                                                <option  aria-hidden='true' value="fa fa-object-group">&#xf247; Agrupamentos</option>
                                                <option  aria-hidden='true' value="fa fa-calculator">&#xf1ec; Cálculos</option>

                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label for="ordem">Ordem Menu:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-road"></i></span>                                            
                                                <input type="number" id="ordem" name="ordem" class="form-control" required="required" placeholder="descrição do lote"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ordem">Permissão de uso:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-sort-by-order"></i></span>
                                                <select name="permissao" id="permissao" class="form-control" required="required">
                                                    <option value="1">Administrador</option>
                                                    <option value="2">Comum</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                            <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>
                                            <!--                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#anotacoes">
                                                                                            <span class='glyphicon glyphicon-list-alt'></span> Menu Pai
                                                                                        </button>-->
                                        </div>
                                    </div>
                                </form>

                                <?php
                                include_once "../modell/MenuFilho.class.php";

//instancia a classe de controle
                                $prod = new MenuFilho();

                                $descricao = \filter_input(INPUT_POST, 'descricao');
                                $ordem = \filter_input(INPUT_POST, 'ordem');
                                $id_menu = \filter_input(INPUT_POST, 'id_menu');
                                $link = \filter_input(INPUT_POST, 'link');
                                $icone = \filter_input(INPUT_POST, 'icone');
                                $permissao = \filter_input(INPUT_POST, 'permissao');
                                $status = true;
                                $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                                if (isset($cadastro)) {
                                    if (empty($descricao)) {
                                        echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                        //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                                    } else {
                                        $status = $prod->cadastraMenuFilho($descricao, $ordem, $id_menu, $status, $link, $icone, $permissao);
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
                                        <th>Descrição Pai</th>
                                        <th>Nome</th>
                                        <th>Link</th>
                                        <th>Ordem</th>
                                        <th>Icone</th>
                                        <th>Permissão</th>
                                        <th><i class="fa fa-low-vision"></i></th>
                                        <th><i class="fa fa-trash"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include_once "../modell/MenuFilho.class.php";
                                    $lote = new MenuFilho();
                                    $matriz = $lote->listaMenuFilhoTodos();

                                    while ($dados = $matriz->fetchObject()) {

                                        if ($dados->status == TRUE) {
                                            echo "<tr>
                                                    <td title='id_menu_filho'>" . $dados->id_menu_filho . "</td>                                                   
                                                    <td title='id_menu' class='editavel'>" . $dados->nome_pai . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>                                                   
                                                    <td title='link' class='editavel'><a href='$dados->link'>" . $dados->link . "</a></td>
                                                    <td title='ordem' class='editavel'>" . $dados->ordem . "</td>
                                                    <td title='icone' class='editavel'><span class= '$dados->icone'  ></span></td>
                                                    <td title='permissao' class='editavel'>" . $dados->permissao . "</td>
                                                    <td>     
							<span class='glyphicon glyphicon-eye-open' id='finalizar' value='finalizar'  onclick='finalizar(" . $dados->id_menu_filho . ");'></span> 													
                                                    </td>
                                                    <td>     
							<span class='glyphicon glyphicon-trash' id='deletarMenuFilho' value='deletarMenuFilho'  onclick='deletarMenuFilho(" . $dados->id_menu_filho . ");'></span> 													
                                                    </td>
                                              </tr>";
                                        } else {
                                            echo "<tr>
                                                    <td title='id_menu_filho'>" . $dados->id_menu_filho . "</td>                                                   
                                                    <td title='id_menu' class='editavel'>" . $dados->nome_pai . "</td>
                                                    <td title='nome' class='editavel'>" . $dados->nome . "</td>
                                                    <td title='link' class='editavel'><a href='$dados->link'>" . $dados->link . "</a></td>
                                                    <td title='ordem' class='editavel'>" . $dados->ordem . "</td>
                                                    <td title='icone' class='editavel'><span class= '$dados->icone'  > </span></td>
                                                    <td title='permissao' class='editavel'>" . $dados->permissao . "</td>
                                                        <td>
                                                       <span class='glyphicon glyphicon-eye-close' id='ativar' value='ativar'  onclick='ativar(" . $dados->id_menu_filho . ");'></span>                                                       
                                                    </td>
                                                        <td>
                                                       <span class='glyphicon glyphicon-trash' id='deletarMenuFilho' value='deletarMenuFilho'  onclick='deletarMenuFilho(" . $dados->id_menu_filho . ");'></span>                                                       
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
                    <!-- MODAL DE ANOTAÇÕES -->
                    <!--                    <div class="modal fade" id="anotacoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Cadastro de Menu Pai</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" class="form-horizontal">
                                                            <div class="panel-body">                                                
                                                                <div class="form-group">
                                                                    <label for="descricao_pai">Descrição:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-road"></i></span>
                                                                        <input type="text" id="descricao_pai" name="descricao_pai" class="form-control" required="required" placeholder="descrição do lote"/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="ordem_pai">Ordem:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-sort-by-order"></i></span>
                                                                        <input type="number" id="ordem_pai" name="ordem_pai" class="form-control" required="required" min="0" max="99" placeholder="descrição do lote"/>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="inserir" value="inserir" class="btn btn-info">Cadastrar</button>                 
                                                                </div>
                                                            </div>
                                                        </form>
                    
                    <?php
                    include_once "../modell/MenuPai.class.php";

//instancia a classe de controle
                    $menu_pai = new MenuPai();

                    $nome_pai = \filter_input(INPUT_POST, 'descricao_pai');
                    $ordem_pai = \filter_input(INPUT_POST, 'ordem_pai');
                    $status_pai = true;
                    $cadastro = \filter_input(INPUT_POST, 'inserir');

                    if (isset($cadastro)) {
                        if (empty($nome_pai)) {
                            echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                            //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                        } else {
                            $status1 = $menu_pai->cadastraMenuPai($nome_pai, $status_pai, $ordem_pai);
                            if ($status1 == true) {
                                echo "<script> alert('Registro inserido com sucesso.');</script>";
                            } else {
                                echo "<script> alert('Erro ao inserir registro.');</script>";
                            }
                        }
                    }
                    ?>   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                </div>
            </div>
        </div>
        <script src="../ajax/menu/finalizar_menu_filho.js"></script>
        <script src="../ajax/menu/ativa_menu_filho.js"></script>
        <script src="../ajax/menu/deletar_menus.js"></script>

        <?php require_once "./actionRodape.php"; ?>

    </body>
</html>
