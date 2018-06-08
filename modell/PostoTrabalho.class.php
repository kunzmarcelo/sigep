<?php

include_once "../modell/BancoDadosPDO.class.php";

class PostoTrabalho extends BancoDadosPDO {

    protected $id_posto;
    protected $numero;
    protected $descricao;   
    protected $status;
    
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraPosto($numero,$descricao,$status) {
        return $this->inserir("posto_trabalho", "numero,descricao,status", "'$numero','$descricao','$status'");
    }

    function listaPosto() {
        return $this->listarTodos("posto_trabalho");
    }

    function excluirPosto($id) {
        return $this->excluir("posto_trabalho", "id_posto='$id'");
    }

}

?>
