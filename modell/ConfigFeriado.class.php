<?php

include_once "../modell/BancoDadosPDO.class.php";

class ConfigFeriado extends BancoDadosPDO {

    protected $id_config_feriado;
    protected $descricao;
    protected $data;
    protected $numero;   
  

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraConfigFeriado($descricao,$data,$numero) {
        return $this->inserir("config_feriado", "descricao,data,numero", "'$descricao','$data','$numero'");
    }

    function listaConfigFeriado() {
        return $this->listarTodos("config_feriado ORDER BY data DESC");
    }
    function listaConfigFeriadoMes($mes) {
        return $this->listarTodos("config_feriado WHERE Month(data)= '$mes'  ORDER BY data DESC");
    }

    function excluirConfigFeriado($id) {
        return $this->excluir("config_feriado", "id_config_feriado='$id'");
    }

}

?>
