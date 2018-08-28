
function deletar(cod) {
    var resposta = confirm("Deseja excluir esse registro?");

    if (resposta === true) {
        $("#cod").focus();
        $("#cod").val(cod);
        $("#cod").blur();
        //alert(cod);
        //var cod = $("#cod").val();
        //alert(cod);
        $.ajax({       
            type: "GET",
            url: "../ajax/falta/deletar_falta.php",
            data: "id=" + cod,
            success: function (doc) {
               // carregando();
                //window.location.assign('pgAdmin.php?pagina=listaAtleta&statusAtivo=true');
                location.reload();
                //alert('registro ativado');
            },
            error: function () {
                alert('Erro ao excluir registro');
            }
        });
    }
}

