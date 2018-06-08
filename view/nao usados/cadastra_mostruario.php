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
                    <a href="lista_mostruario.php" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-th-list"></span> Listar</a>

<!--    <a href="pgAdmin.php?pagina=cadastraEquipe" class="btn btn-info"> <span class='glyphicon glyphicon-plus'></span> Adicionar</a>-->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading centraliza">
                            Cadastro de mostruário
                        </div>        
                        <div class="panel-body">
                            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                <div class="col-lg-6">                                                                           
                                    <div class="form-group">
                                        <label for="descricao">Descrição:</label>
                                        <input type="text" id="descricao" name="descricao" class="form-control" placeholder="descrição da imagem" required="required"/>
                                    </div>                                    
                                    <div class="form-group"> 
                                        <label for="id_produto" class="tamanho-fonte">Produto:</label><small> (Campo Obrigatório)</small>
                                        <select name="id_produto" id="id_produto" class="form-control" required="required">
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
                                    <div class="form-group">
                                        <label for="cor">Cor:</label>

                                        <select name="cor" id="cor" class="form-control" required="required">
                                            <option value="">Selecione...</option>
                                            <?php
                                            for ($i = 1; $i <= 30; $i++) {
                                                echo "<option value='cor $i'>Cor: $i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="imagem">Imagem:</label>
                                        <input type="file" name="imagem" class="form-control" required="required"/>
                                    </div>                                    
                                    <div class="form-group">
                                        <button type="submit" name="cadastrar" value="cadastrar" class="btn btn-info">Cadastrar</button>
                                        <button type="reset" name="cancelar" value="cancelar" class="btn btn-inverse">Cancelar</button>                    
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <?php
                        include_once "../modell/Mostruario.class.php";

//instancia a classe de controle
                        $prod = new Mostruario();


                        $cadastro = \filter_input(INPUT_POST, 'cadastrar');

                        if (isset($cadastro)) {

                            $descricao = \filter_input(INPUT_POST, 'descricao');
                            $produto = \filter_input(INPUT_POST, 'id_produto');
                            $cor = \filter_input(INPUT_POST, 'cor');
                            $foto = $_FILES["imagem"];
                            //$imagem = $_FILES['imagem']['tmp_name'];
                            $data_cadastro = date('Y-m-d H:i:s');
                            $status = TRUE;
                            if (empty($descricao)) {
                                echo "<div class='alert alert-danger alert-dismissable'>
                                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                            Algum dos campos acima não foi preenchido corretamente.
                                        </div>";
                                //echo "<div class='alert alert-danger' role='alert'>Algum dos campos acima não foi preenchido corretamente.</div>";
                            } else {
                                $pasta_dir = "../bibliotecas/imagens/"; //diretorio dos arquivos
// Pega extensão da imagem
                                preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

                                // Gera um nome único para a imagem
                                $nome_imagem = md5(uniqid(time())) . "." . $ext[1];

                                // Caminho de onde ficará a imagem
                                $caminho_imagem = $pasta_dir . $nome_imagem;

                                // Faz o upload da imagem para seu respectivo caminho
                                move_uploaded_file($foto["tmp_name"], $caminho_imagem);
                                //var_dump($descricao, $produto, $cor, $imagem, $data_cadastro, $status);
                                //break;
                                $status = $prod->cadastraMostruario($descricao, $produto, $cor, $nome_imagem, $data_cadastro, $status);
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