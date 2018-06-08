<?php

include_once "../modell/BancoDadosPDO.class.php";

class Anotacao extends BancoDadosPDO {

    protected $id_anotacao;
    protected $descricao;
    protected $data;
    protected $id_celula;   
    protected $status;    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraAnotacao($descricao,$data,$id_celula,$status) {
        return $this->inserir("anotacao", "descricao,data,id_celula,status", "'$descricao','$data','$id_celula','$status'");
    }

    function listaAnotacao($id_celula,$data) {
        return $this->listarTodos("anotacao WHERE id_celula = '$id_celula' AND data='$data'");
    }

    function excluirAnotacao($id) {
        return $this->excluir("anotacao", "id_anotacao='$id'");
    }

}

?>
