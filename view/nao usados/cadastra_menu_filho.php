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


    </head>
    <body>
        <div id="wrapper">
<?php require_once './actionfonteMenu.php'; ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Cadastro de Sub Menus</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">

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
                                            <div class="form-group">
                                                <label for="icone">Icone:</label>
                                                <!--                                        <div class="input-group">
                                                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>-->
                                                <select name="icone" id="icone" class="form-control multiselect multiselect-icon" role="multiselect" required="required">
                                                    <option value="glyphicon glyphicon-saved" data-icon="glyphicon glyphicon-saved"> Icone</option>
                                                    <option value="glyphicon glyphicon-ok" data-icon="glyphicon glyphicon-ok"> Icone</option>
                                                    <option value="glyphicon glyphicon-plus" data-icon="glyphicon glyphicon-plus"> Icone</option>
                                                    <option value="glyphicon glyphicon-signal" data-icon="glyphicon glyphicon-signal"> Icone</option>
                                                    <option value="glyphicon glyphicon-list" data-icon="glyphicon glyphicon-list"> Icone</option>                                                
                                                    <option value="fa fa-table fa-fw" data-icon="fa fa-table fa-fw"> Icone</option>                                                
                                                </select>
                                                <!--<input type="text" id="icone" name="icone" class="form-control" placeholder="descrição do lote"/>-->
                                                <!--</div>-->
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
                                                        <option value="2">Comum</option>
                                                        <option value="1">Administrador</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                                <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#anotacoes">
                                                    <span class='glyphicon glyphicon-list-alt'></span> Menu Pai
                                                </button>
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
                            <!-- MODAL DE ANOTAÇÕES -->
                            <div class="modal fade" id="anotacoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            </div>
                        </div>
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
<?php require_once "./actionRodape.php"; ?>
        <script src="../bootstrap/assets/js/toucheffects.js"></script>
        <script src="../bootstrap/assets/js/mulstiselect.js"></script>
        <!-- For the demo ad only -->   


    </body>
</html>