
function finalizar(cod) {
    //var resposta = confirm("Deseja finalizar esse lote?");

    //if (resposta === true) {
        $("#cod").focus();
        $("#cod").val(cod);
        $("#cod").blur();
        //alert(cod);
        //var cod = $("#cod").val();
        //alert(cod);
        $.ajax({       
            type: "GET",
            url: "../ajax/menu/finalizar_menu_pai.php",
            data: "id=" + cod,
            success: function (doc) {              
                location.reload();               
            },
            error: function () {
                alert('Erro ao Finalizar o cadastro');
            }
        });
    //}
}