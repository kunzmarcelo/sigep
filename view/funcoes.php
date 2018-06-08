
<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<?php

function expiraSessao() {
    $temposessao = 3600; //em segundos
    //session_start();

    if ($_SESSION["sessiontime"]) {
        if ($_SESSION["sessiontime"] < (time() - $temposessao)) {
            session_unset();
            echo "<script>alert('Seu tempo Expirou. Faça login novamente!');window.location.href='./login.html';</script>";
        }
    } else {
        session_unset();
    }

    $_SESSION["sessiontime"] = time();
}

//include './login.html';
function controlaAcessoUrl($url, $pagina) {
    if ($_SESSION['ativo'] == TRUE) {
        //echo "<div class='row-fluid'> <legend>";
        //echo 'Bem vindo ' . $_SESSION['login'];
        // echo '</legend></div>';   
    } else {
        try {
            if ($url == $pagina) {
                throw new Exception("<script>alert('Voce tentou acessar a página diretamente, é necessário fazer login!');window.location.href='./login.html';</script>");
            }
        } catch (Exception $e) {
            /*
             * 
             */
            echo "ERRO " . $e->getMessage();
            ?>




            <?php

            echo "<br>";

//            echo "<section id='error' class='container'>
//                <h1>404, Page not found</h1>
//                <p>The Page you are looking for doesn't exist or an other error occurred.</p>
//                <a class='btn btn-success' href='index.html'>GO BACK TO THE HOMEPAGE</a>
//            </section>";
            echo '<a href="./login.html">Voltar Inicio</a>';
            exit;
        }
    }
}
//
//include_once "../modell/FuncaoNovo.class.php";
//$lote = new FuncaoNovo();
//$matriz = $lote->listaFuncao();
////$matriz = $lote->listaTdosFuncaoLigacao();
//
//while ($dados = $matriz->fetchObject()) {
//
//
//    $nomeProduto = $dados->descricao;
//
//
//    $dados2 = array(
//        "id_funcao" => $dados->id_funcao,
//        "funcao" => $dados->funcao,);
//// Esta é a array que deseja que seja retornada
//
//
//    $jsonAux[$nomeProduto][] = $dados2;
//}
//
//$fp = fopen('produtos.json', 'w');
//fwrite($fp, json_encode($jsonAux));
//fclose($fp);
?>
