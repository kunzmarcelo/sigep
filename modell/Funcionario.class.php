<?php

include_once "../modell/BancoDadosPDO.class.php";

class Funcionario extends BancoDadosPDO {

    protected $id;
    protected $nome;
    protected $departamento;
    protected $ativo;
    protected $sexo;
    protected $update_date;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraFuncionario($nome,$departamento,$ativo,$update_date,$sexo) {
        return $this->inserir("funcionario", "nome,ativo,departamento,update_date,sexo", "'$nome','$ativo','$departamento','$update_date','$sexo'");
    }

    function listaFuncionario() {
        return $this->listarTodos("funcionario ORDER BY nome ASC");
    }

    function excluirFuncionario($id) {
        return $this->excluir("funcionario", "id='$id'");
    }

}

?>
