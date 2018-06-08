<?php

include_once "../modell/BancoDadosPDO.class.php";

class ConfigSalario extends BancoDadosPDO {

    protected $id_config_salario;
    protected $valor;
    protected $n_funcionario;
    protected $hora_semanal;
    protected $data;
    protected $dias_semana;
    protected $status_config_salario;
  

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraConfigSalario($valor, $n_funcionario, $hora_semanal, $data,$dias_semana,$status_config_salario) {
        return $this->inserir("config_salario", "valor,n_funcionario,hora_semanal,data,dias_semana,status_config_salario", "'$valor','$n_funcionario','$hora_semanal','$data','$dias_semana','$status_config_salario'");
    }

    function listaConfigSalario() {
        return $this->listarTodos("config_salario ORDER BY valor ASC");
    }

    function excluirConfigSalario($id) {
        return $this->excluir("config_salario", "id_config_salario='$id'");
    }

}
