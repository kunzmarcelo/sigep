<?php

include_once "../modell/BancoDadosPDO.class.php";

class MaoObra extends BancoDadosPDO {

    protected $id;
    protected $descricao;
    protected $valor;
    //protected $tempo_medio;
    //protected $tempo_producao;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraFuncao($descricao,$valor) {
        return $this->inserir("custo_mao_obra", "descricao,valor", "'$descricao','$valor'");
    }

    function listaFuncao() {
        return $this->listarTodos("custo_mao_obra ORDER BY descricao ASC");
    }

    function excluirFuncao($id) {
        return $this->excluir("custo_mao_obra", "id='$id'");
    }

}

?>
