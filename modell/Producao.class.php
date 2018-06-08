<?php

include_once "../modell/BancoDadosPDO.class.php";

class Producao extends BancoDadosPDO {

    protected $id_producao;
    protected $numero;
    protected $quantidade;
    protected $produto;
    protected $data_ini;
    protected $data_fim;
    protected $hora_ini;
    protected $hora_fim;
    protected $desconto;
    protected $status;
   
    
                function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraProducao($numero,$quantidade,$produto,$data_ini,$data_fim,$hora_ini,$hora_fim,$desconto,$status) {
        $number = date("m-Y");
        return $this->inserir("producao", "quantidade,numero,produto,data_ini,data_fim,hora_ini,hora_fim,desconto,status", "'$quantidade','$numero/$number','$produto','$data_ini','$data_fim','$hora_ini','$hora_fim','$desconto','$status'");
    }

    function listaProducao() {
        return $this->listarTodos("producao ORDER BY status DESC");
    }
    function listaProducaoAgrupado() {
        return $this->listarTodos("producao GROUP BY numero ORDER BY numero ASC");
    }

    function excluirProducao($id) {
        return $this->excluir("producao", "id_producao='$id'");
    }

}

?>
