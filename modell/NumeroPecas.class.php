<?php

include_once "../modell/BancoDadosPDO.class.php";

class NumeroPecas extends BancoDadosPDO {

    protected $id;
    protected $data_ini;
    protected $data_fim;
    protected $n_peca;
    protected $salario_producao;
    protected $id_funcionario;
    protected $id_salario;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraNumeroPecas($data_ini, $data_fim, $n_peca, $salario_producao, $id_funcionario, $id_salario) {
        return $this->inserir("numero_pecas", "data_ini,data_fim,n_peca,salario_producao,id_funcionario,id_salario", "'$data_ini','$data_fim','$n_peca','$salario_producao','$id_funcionario','$id_salario'");
    }

    function listaNumeroPecas() {
        return $this->listarTodos("numero_pecas ORDER BY numero_pecas ASC");
    }

    function litaTodosNumeroPecas($id_funcionario) {
        return $this->listarTodosMuitosParaMuitos("numero_pecas.*, funcionario.nome, salario.valor",
"numero_pecas, funcionario, salario",
"numero_pecas.id_funcionario = funcionario.id_funcionario
AND numero_pecas.id_salario = salario.id_salario
AND numero_pecas.id_funcionario = '$id_funcionario'
ORDER BY numero_pecas.data_ini, funcionario.nome ASC ");
    }

    function excluirNumeroPecas($id) {
        return $this->excluir("numero_pecas", "id='$id'");
    }

}

?>
