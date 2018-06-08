<?php

include_once "../modell/BancoDadosPDO.class.php";

class Calendario extends BancoDadosPDO {

    protected $id_calendario;
    protected $descricao;
    protected $data;
    protected $numero;   
  

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraCalendario($descricao,$data,$numero) {
        return $this->inserir("calendario", "descricao,data,numero", "'$descricao','$data','$numero'");
    }

    function listaCalendario() {
        return $this->listarTodos("calendario ORDER BY data DESC");
    }
    function listaCalendarioMes($mes) {
        return $this->listarTodos("calendario WHERE Month(data)= '$mes'  ORDER BY data DESC");
    }

    function excluirCalendario($id) {
        return $this->excluir("calendario", "id_calendario='$id'");
    }

}

?>
