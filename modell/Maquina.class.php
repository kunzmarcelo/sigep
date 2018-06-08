<?php

include_once "../modell/BancoDadosPDO.class.php";

class Maquina extends BancoDadosPDO {

    protected $id_maquina;    
    protected $numero;
    protected $descricao;
    protected $status;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraMaquina($numero,$descricao,$status) {
        return $this->inserir("maquina", "numero,descricao,status", "'$numero','$descricao','$status'");
    }

    function listaMaquina() {
        return $this->listarTodos("maquina ORDER BY numero ASC");
    }
    function listaUmMaquina($id_maquina) {
        return $this->listarUm("maquina","id_maquina='$id_maquina' ORDER BY numero ASC");
    }
    
    function excluirDiarioBordo($id) {
        return $this->excluir("maquina", "id_maquina='$id'");
    }

}

?>
