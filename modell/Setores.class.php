<?php

include_once "../modell/BancoDadosPDO.class.php";

class Setores extends BancoDadosPDO {

    protected $id_setor;  
    protected $descricao_setor;
    protected $ordem;
    protected $status_setor;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraSetor($descricao_setor,$ordem,$status_setor) {
        return $this->inserir("setores", "descricao_setor,ordem,status_setor", "'$descricao_setor','$ordem','$status_setor'");
    }

    function listaSetor() {
        return $this->listarTodos("setores ORDER BY ordem ASC");
    }

    function excluirSetor($id) {
        return $this->excluir("setores", "id_setor='$id'");
    }

}

?>
