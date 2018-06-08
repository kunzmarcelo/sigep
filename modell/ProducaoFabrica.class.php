<?php

include_once "../modell/BancoDadosPDO.class.php";

class ProducaoFabrica extends BancoDadosPDO {

    protected $id_producao;
    protected $numero;
    protected $descricao;
    protected $data_ini;
    protected $data_fim;
    protected $n_peca;
    protected $meta_peca;
    protected $valor;
    protected $meta_faturamento;
    protected $status;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraProducao($numero,$descricao,$data_ini,$data_fim,$n_peca,$meta_peca,$valor,$meta_faturamento,$status) {
        return $this->inserir("producao_fabrica", "numero,descricao,data_ini,data_fim,n_peca,meta_peca,valor,meta_faturamento,status", "'$numero','$descricao','$data_ini','$data_fim','$n_peca','$meta_peca','$valor','$meta_faturamento','$status'");
    }

    function listaProducao() {
        return $this->listarTodos("producao_fabrica ORDER BY data_ini, data_fim, numero ASC");
    }
    
    function listaAgrupado(){
        return $this->listarTodosMuitosParaMuitos("data_ini,data_fim, sum(producao_fabrica.n_peca) AS SOMAPECAS ,sum(producao_fabrica.valor) AS SOMAVALOR, meta_peca,meta_faturamento", "producao_fabrica","id_producao >0 GROUP by data_ini");
    }

    function excluirProducao($id) {
        return $this->excluir("producao_fabrica", "id='$id'");
    }

}

?>
