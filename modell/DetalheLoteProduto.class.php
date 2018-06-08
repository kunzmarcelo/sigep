<?php

include_once "../modell/BancoDadosPDO.class.php";

class DetalheLoteProduto extends BancoDadosPDO {

    protected $id_lote;
    protected $id_produto;
    protected $data;
    protected $n_peca;
    protected $preco;
    protected $observacao;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraLoteProduto($id_lote, $id_produto, $data, $n_peca, $preco, $observacao) {
        return $this->inserir("detalhe_lote_produto", "id_lote,id_produto,data,n_peca,preco,observacao", "'$id_lote','$id_produto','$data','$n_peca','$preco','$observacao'");
    }

    function listaLoteProduto() {
        return $this->listarTodos("detalhe_lote_produto");
    }

    function listaLoteProdutoCondicaoLote($n_lote) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.*, produto.*, detalhe_lote_produto.data AS DATALOTE, detalhe_lote_produto.n_peca,detalhe_lote_produto.id AS IdDetalhe,detalhe_lote_produto.preco AS PRECOLOTE,detalhe_lote_produto.custo AS CUSTOPRODUCAO,detalhe_lote_produto.observacao", "lote,produto,detalhe_lote_produto", "lote.id_lote = detalhe_lote_produto.id_lote
				AND detalhe_lote_produto.id_produto = produto.id_produto
				AND lote.numero = '$n_lote'");
    }
   

    function listaLoteProdutoCondicaoData($data_ini, $data_fim) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.numero, lote.descricao, produto.*, detalhe_lote_produto.data AS DATALOTE, detalhe_lote_produto.n_peca,detalhe_lote_produto.id AS IdDetalhe, detalhe_lote_produto.preco AS PRECOLOTE, detalhe_lote_produto.custo AS CUSTOPRODUCAO ,detalhe_lote_produto.observacao", "lote,produto,detalhe_lote_produto", "
                            lote.id_lote = detalhe_lote_produto.id_lote
                AND detalhe_lote_produto.id_produto = produto.id_produto 
				AND detalhe_lote_produto.data between '$data_ini' 
				AND '$data_fim' 
				ORDER BY lote.numero ASC");
    }

    function listaLoteProdutoCondicaoLoteLote($n_lote1, $n_lote2) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.numero, lote.descricao, produto.*, detalhe_lote_produto.data AS DATALOTE, detalhe_lote_produto.n_peca,detalhe_lote_produto.id AS IdDetalhe, detalhe_lote_produto.preco AS PRECOLOTE, detalhe_lote_produto.custo AS CUSTOPRODUCAO,detalhe_lote_produto.observacao", "lote,produto,detalhe_lote_produto", "lote.id_lote = detalhe_lote_produto.id_lote
                AND detalhe_lote_produto.id_produto = produto.id_produto 
				AND lote.numero between '$n_lote1' AND '$n_lote2' 
				ORDER BY lote.numero ASC");
    }

    function listaLoteProdutoCondicaoLoteDataLote($data_ini, $data_fim, $lote1, $lote2) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.numero, lote.descricao, produto.*, detalhe_lote_produto.data AS DATALOTE, detalhe_lote_produto.n_peca,detalhe_lote_produto.id AS IdDetalhe, detalhe_lote_produto.preco AS PRECOLOTE, detalhe_lote_produto.custo AS CUSTOPRODUCAO,detalhe_lote_produto.observacao", "lote,produto,detalhe_lote_produto", "lote.id_lote = detalhe_lote_produto.id_lote
                AND detalhe_lote_produto.id_produto = produto.id_produto 
				AND detalhe_lote_produto.data between '$data_ini' and '$data_fim'
				AND lote.numero between '$lote1' AND '$lote2'
				ORDER BY lote.numero ASC");
    }

    function listaLoteProdutoCondicaoLoteDataLoteAgrupado($data_ini, $data_fim, $lote1, $lote2) {
        return $this->listarTodosMuitosParaMuitos(
                        "SUM(detalhe_lote_produto.preco * n_peca) AS RESULTADO, SUM(n_peca) AS NUMEROPECA, lote.numero, lote.descricao AS DESCRICAOLOTE, produto.*, detalhe_lote_produto.data AS DATALOTE, detalhe_lote_produto.n_peca,detalhe_lote_produto.id AS IdDetalhe, detalhe_lote_produto.preco AS PRECOLOTE, detalhe_lote_produto.custo AS CUSTOPRODUCAO", ""
                . "lote,produto,detalhe_lote_produto",
                "lote.id_lote = detalhe_lote_produto.id_lote
                AND detalhe_lote_produto.id_produto = produto.id_produto
				AND detalhe_lote_produto.data between '$data_ini' and '$data_fim'
				AND lote.numero between '$lote1' AND '$lote2'
				GROUP BY lote.numero 
				ORDER BY lote.numero ASC");
    }
    
    function graficoFaturamentoMes($mes,$ano){
        return $this->listarTodosMuitosParaMuitos("sum(n_peca*preco) AS VALOR", "detalhe_lote_produto"," Month(data) = '$mes' AND Year(data) = '$ano'");
    }
    function graficoPecasMes($mes,$ano){
        return $this->listarTodosMuitosParaMuitos("sum(n_peca) AS PECAS", "detalhe_lote_produto"," Month(data) = '$mes' AND Year(data) = '$ano'");
    }

    function excluirLoteProduto($id) {
        return $this->excluir("detalhe_lote_produto", "id_lote='$id'");
    }

}

?>
