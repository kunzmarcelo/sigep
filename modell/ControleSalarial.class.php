<?php

include_once "../modell/BancoDadosPDO.class.php";

class ControleSalarial extends BancoDadosPDO {

    protected $id_salario;
    protected $valor;
    protected $n_funcionarios;
    protected $dias_trabalhados;
    protected $hora_inicio;
    protected $hora_fim;
    protected $status_salario;
    protected $data_cadastro;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraSalario($valor, $n_funcionarios, $dias_trabalhados, $hora_inicio,$hora_fim,$status_salario,$data_cadastro) {
        return $this->inserir("controle_salarial", "valor,n_funcionarios,dias_trabalhados,hora_inicio,hora_fim,status_salario,data_cadastro", "'$valor','$n_funcionarios','$dias_trabalhados','$hora_inicio','$hora_fim','$status_salario','$data_cadastro'");
    }

    function listaSalario() {
        return $this->listarTodos("controle_salarial ORDER BY valor ASC");
    }

    function excluirSalario($id) {
        return $this->excluir("controle_salarial", "id_salario='$id'");
    }

}
