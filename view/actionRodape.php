
<script>
    document.getElementById('btn').onclick = function () {
        var conteudo = document.getElementById('impressao').innerHTML,
                tela_impressao = window.open('about');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    };
</script>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!--<script src="../bootstrap/assets/js/toucheffects.js"></script>-->


<script src="../startbootstrap/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../startbootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../startbootstrap/vendor/metisMenu/metisMenu.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="../startbootstrap/vendor/raphael/raphael.min.js"></script>
<!--<script src="../startbootstrap/vendor/morrisjs/morris.min.js"></script>-->
<!--<script src="../startbootstrap/data/morris-data.js"></script>-->

<!-- Custom Theme JavaScript -->
<script src="../startbootstrap/dist/js/sb-admin-2.js"></script>


