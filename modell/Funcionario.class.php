<?php

include_once "../modell/BancoDadosPDO.class.php";

class Funcionario extends BancoDadosPDO {

    protected $id;
    protected $nome;
    protected $departamento;
    protected $ativo;
    protected $sexo;
    protected $update_date;
    protected $id_setor;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraFuncionario($nome,$departamento,$ativo,$update_date,$sexo,$id_setor) {
        return $this->inserir("funcionario", "nome,ativo,departamento,sexo,update_date,id_setor", "'$nome','$ativo','$departamento','$sexo','$update_date','$id_setor'");
    }

    function listaFuncionario() {
        return $this->listarTodos("funcionario, setores WHERE funcionario.id_setor = setores.id_setor ORDER BY nome ASC");
    }
    function listaFuncionarioStatus($status) {
        return $this->listarTodos("funcionario, setores WHERE funcionario.id_setor = setores.id_setor AND funcionario.ativo ='$status ' AND setores.descricao_setor != 'Geral' ORDER BY setores.ordem, funcionario.nome ASC");
    }

    function excluirFuncionario($id) {
        return $this->excluir("funcionario", "id='$id'");
    }

}

?>
