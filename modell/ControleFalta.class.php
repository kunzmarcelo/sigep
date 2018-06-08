<?php

include_once "../modell/BancoDadosPDO.class.php";

class ControleFalta extends BancoDadosPDO {

    protected $id_falta;
    protected $id_funcionario;
    protected $data;
    protected $hora_ini;
    protected $hora_fim;
    protected $motivo;
    protected $status;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraFalta($id_funcionario,$data,$hora_ini,$hora_fim,$motivo,$status) {
        return $this->inserir("controle_falta", "id_funcionario,data,hora_ini,hora_fim,motivo,status", "'$id_funcionario','$data','$hora_ini','$hora_fim','$motivo','$status'");
    }

    function listaFalta() {
        return $this->listarTodosCondicao("controle_falta,funcionario ","controle_falta.id_funcionario = funcionario.id_funcionario");
    }

    function excluirFalta($id) {
        return $this->excluir("controle_falta", "id_falta='$id'");
    }

}

?>
