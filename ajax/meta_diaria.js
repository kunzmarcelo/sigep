
function ativar(cod) {
    //var resposta = confirm("Deseja ativar?");

    //if (resposta === true) {
        $("#cod").focus();
        $("#cod").val(cod);
        $("#cod").blur();
        //alert(cod);
        //var cod = $("#cod").val();
        //alert(cod);
        $.ajax({       
            type: "GET",
            url: "../ajax/meta_diaria.php",
            data: "id=" + cod,
            success: function (doc) {
               // carregando();
                //window.location.assign('pgAdmin.php?pagina=listaAtleta&statusAtivo=true');
                location.reload();
                //alert('registro ativado');
            },
            error: function () {
                alert('Erro ao ativar o cadastro');
            }
        });
    //}
}
function finalizar(cod) {
    //var resposta = confirm("Deseja finalizar");

    //if (resposta === true) {
        $("#cod").focus();
        $("#cod").val(cod);
        $("#cod").blur();
        //alert(cod);
        //var cod = $("#cod").val();
        //alert(cod);
        $.ajax({       
            type: "GET",
            url: "../ajax/meta_diaria_finalizar.php",
            data: "id=" + cod,
            success: function (doc) {
               // carregando();
                //window.location.assign('pgAdmin.php?pagina=listaAtleta&statusAtivo=true');
                location.reload();
                //alert('registro ativado');
            },
            error: function () {
                alert('Erro ao ativar o cadastro');
            }
        });
    //}
}