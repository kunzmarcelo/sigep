function fechar_todos(cod) {
   // var resposta = confirm("Deseja finalizar esse lote?");

    //if (resposta === true) {
        $("#cod").focus();
        $("#cod").val(cod);
        $("#cod").blur();
       // alert(cod);
        //var cod = $("#cod").val();
        //alert(cod);
        $.ajax({       
            type: "GET",
            url: "../ajax/operacao/fechar_todos.php",
            data: "id=" + cod,
            success: function (doc) {              
                location.reload();
            },
            error: function () {
                alert('Erro ao ativar o cadastro');
            }
        });
    //}
}