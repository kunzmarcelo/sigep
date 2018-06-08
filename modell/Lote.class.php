<?php

include_once "../modell/BancoDadosPDO.class.php";

class Lote extends BancoDadosPDO {

    protected $id_lote;
    protected $numero;
    protected $descricao;
    protected $data;
    protected $status;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraLote($numero,$descricao,$data,$status) {
        return $this->inserir("lote", "numero,descricao,data,status", "'$numero','$descricao','$data','$status'");
    }

    function listaLote() {
        return $this->listarTodos("lote ORDER BY numero ASC");
    }

    function excluirLote($id) {
        return $this->excluir("lote", "id_lote='$id'");
    }

}

?>
