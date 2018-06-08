

<?php
include_once "../modell/BancoDadosPDO.class.php";

$con = new BancoDadosPDO();
$login = $_SESSION['login'];
$resultado = $con->listarUm("usuario", "login = '$login'");
$dados = $resultado->fetchObject();
?>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="../index.php" style="font-family: Franklin Gothic Heavy">
                        <!--<h1 style="font-family: Franklin Gothic Heavy;">-->
                            <span style="color: #034ea1;">SIGE</span><span style="color: #0088cf;">P</span>
                        <!--</h1>-->
                    </a>
    </div>
    <!-- /.navbar-header -->



    <ul class='nav navbar-top-links navbar-right'>
        <?php
        require_once "../modell/BancoDadosPDO.class.php";

        $menu_paiBD = new BancoDadosPDO();
        $matriz2 = $menu_paiBD->listarTodos("menu_pai WHERE ordem_pai = '1000' ORDER BY ordem_pai ASC");

        foreach ($matriz2->fetchAll() as $res2) {
            if ($res2['status_pai'] == TRUE) {
                $id_pai = $res2['id_menu'];
                $icone_pai = $res2["icone_pai"];
                ?>
                <li class='dropdown'>
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                        <i class='<?= $icone_pai ?>'  aria-hidden='true'></i> 
                        <?= $dados->nome . ' ' . $dados->sobrenome ?> <span class='caret'></span>
                    </a>
                    <ul class='dropdown-menu'>
                        <?php
                        $matriz = $menu_paiBD->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai' AND menu_filho.permissao = '$dados->permissao'");
                        foreach ($matriz->fetchall() as $res) {
                            if ($res['status'] == TRUE) {
                                $icone = $res["icone"];
                                ?>

                                <li>
                                    <a href="<?= $res['link'] ?>">
                                        <i class="<?= $icone ?>"  aria-hidden='true'></i>
                                        <?= $res['nome'] ?>
                                    </a>
                                </li>
                                <li role='separator' class='divider'></li>
                                <?php
                            }
                        }
                        ?>
                        <li role='separator' class='divider'></li>
                        <li>
                            <a href='logout.php'><span class='glyphicon glyphicon-log-out'></span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
                <?php
            }
        }
        ?>
    </ul>


    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class='nav' id='side-menu'>
                <?php
                require_once "../modell/BancoDadosPDO.class.php";

                $menu_paiBD = new BancoDadosPDO();
                $matriz2 = $menu_paiBD->listarTodos("menu_pai WHERE ordem_pai != '1000' ORDER BY ordem_pai ASC");

                foreach ($matriz2->fetchAll() as $res2) {
                    if ($res2['status_pai'] == TRUE) {
                        $icone_pai = $res2["icone_pai"];
                        $id_pai = $res2['id_menu'];
                        ?>
                        <li>
                            <a href='#'>
                                <i class='<?= $icone_pai ?>' aria-hidden='true'></i>&nbsp;
                                <?= $res2['nome_pai'] ?><span class='fa arrow'></span>
                            </a>
                            <ul class='nav nav-second-level'>
                                <?php
                                $matriz = $menu_paiBD->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai'");
                                foreach ($matriz->fetchall() as $res) {
                                    if ($res['status'] == TRUE) {
                                        $icone = $res["icone"];
                                        ?>
                                        <li>
                                            <a href="<?= $res['link'] ?>"><span class= '<?= $icone ?>'  > </span>
                                                <?= $res['nome'] ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>

        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>


