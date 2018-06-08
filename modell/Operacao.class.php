<?php

include_once "../modell/BancoDadosPDO.class.php";

class Operacao extends BancoDadosPDO {

    protected $id_operacao;
    protected $operacao;
    protected $custo_operacao;
    protected $tempo_operacao;
    protected $status_operacao;
    protected $id_produto;
    protected $setor_operacao;

    //protected $tempo_producao;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraOperacao($operacao, $custo_operacao, $tempo_operacao, $status_operacao, $id_produto, $setor_operacao) {
        return $this->inserir("operacao", "operacao,custo_operacao,tempo_operacao,status_operacao,id_produto,setor_operacao", "'$operacao','$custo_operacao','$tempo_operacao','$status_operacao','$id_produto','$setor_operacao'");
    }

    function listaOperacao() {
        return $this->listarTodos("operacao, produto where operacao.id_produto = produto.id_produto ORDER BY produto.descricao, operacao.operacao ASC");
    }

    function listaOperacaoProduto($id_produto) {
        return $this->listarTodos("operacao,produto where operacao.id_produto = produto.id_produto AND operacao.id_produto = $id_produto ORDER BY operacao.setor_operacao ASC");
    }

    function listaOperacaoProdutoSetor($id_produto, $setor_operacao) {
        return $this->listarTodos("operacao,produto where operacao.id_produto = produto.id_produto AND operacao.id_produto = $id_produto AND operacao.setor_operacao='$setor_operacao' ORDER BY operacao.setor_operacao ASC");
    }

    function listaTdosOperacaoLigacao() {
        return $this->listarTodos("operacao,produto.descricao 
where operacao.id_produto = produto.id_produto");
    }

    function listaOperacaoDiario() {
        return $this->listarTodos("operacao ORDER BY id_operacao ASC");
    }

    function listaOperacaoUmProduto($id_produto) {
        return $this->listarUm("produto", "id_produto = '$id_produto'");
    }

    function somaTempoOperacao($id_produto) {
        return $this->listarTodosMuitosParaMuitos("time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( tempo_operacao ) ) ),'%H:%i:%s') 
AS TOTAL", " operacao,produto", "operacao.id_produto = produto.id_produto AND operacao.id_produto = '$id_produto'");
    }

    function excluirOperacao($id) {
        return $this->excluir("operacao", "id_operacao='$id'");
    }

}

?>
