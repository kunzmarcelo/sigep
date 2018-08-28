<?php

include_once "../modell/BancoDadosPDO.class.php";

class DetalheLoteFuncionario extends BancoDadosPDO {

    protected $id_lote;
    protected $id_funcionario;
    protected $id_produto;
    protected $id_operacao;
    protected $n_peca;
    protected $data;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraLoteProduto($id_lote, $id_funcionario, $id_produto, $id_operacao, $n_peca, $data) {
        return $this->inserir("detalhe_lote_funcionario", "id_lote,id_funcionario,id_produto,id_operacao,n_peca,data", "'$id_lote','$id_funcionario','$id_produto','$id_operacao','$n_peca','$data'");
    }

    function listaLoteProduto() {
        return $this->listarTodos("detalhe_lote_funcionario");
    }

/*    function listaLoteProdutoCondicaoLote($n_lote) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.*, produto.*, detalhe_lote_funcionario.id_produto AS DATALOTE, detalhe_lote_funcionario.id_operacao,detalhe_lote_funcionario.id AS IdDetalhe,detalhe_lote_funcionario.n_peca AS PRECOLOTE,detalhe_lote_funcionario.custo AS CUSTOPRODUCAO,detalhe_lote_funcionario.data", "lote,produto,detalhe_lote_funcionario", "lote.id_lote = detalhe_lote_funcionario.id_lote
				AND detalhe_lote_funcionario.id_funcionario = produto.id_funcionario
				AND lote.numero = '$n_lote'");
    }
   

    function listaLoteProdutoCondicaoData($id_produto_ini, $id_produto_fim) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.numero, lote.descricao, produto.*, detalhe_lote_funcionario.id_produto AS DATALOTE, detalhe_lote_funcionario.id_operacao,detalhe_lote_funcionario.id AS IdDetalhe, detalhe_lote_funcionario.n_peca AS PRECOLOTE, detalhe_lote_funcionario.custo AS CUSTOPRODUCAO ,detalhe_lote_funcionario.data", "lote,produto,detalhe_lote_funcionario", "
                            lote.id_lote = detalhe_lote_funcionario.id_lote
                AND detalhe_lote_funcionario.id_funcionario = produto.id_funcionario 
				AND detalhe_lote_funcionario.id_produto between '$id_produto_ini' 
				AND '$id_produto_fim' 
				ORDER BY lote.numero ASC");
    }

    function listaLoteProdutoCondicaoLoteLote($n_lote1, $n_lote2) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.numero, lote.descricao, produto.*, detalhe_lote_funcionario.id_produto AS DATALOTE, detalhe_lote_funcionario.id_operacao,detalhe_lote_funcionario.id AS IdDetalhe, detalhe_lote_funcionario.n_peca AS PRECOLOTE, detalhe_lote_funcionario.custo AS CUSTOPRODUCAO,detalhe_lote_funcionario.data", "lote,produto,detalhe_lote_funcionario", "lote.id_lote = detalhe_lote_funcionario.id_lote
                AND detalhe_lote_funcionario.id_funcionario = produto.id_funcionario 
				AND lote.numero between '$n_lote1' AND '$n_lote2' 
				ORDER BY lote.numero ASC");
    }

    function listaLoteProdutoCondicaoLoteDataLote($id_produto_ini, $id_produto_fim, $lote1, $lote2) {
        return $this->listarTodosMuitosParaMuitos(
                        "lote.numero, lote.descricao, produto.*, detalhe_lote_funcionario.id_produto AS DATALOTE, detalhe_lote_funcionario.id_operacao,detalhe_lote_funcionario.id AS IdDetalhe, detalhe_lote_funcionario.n_peca AS PRECOLOTE, detalhe_lote_funcionario.custo AS CUSTOPRODUCAO,detalhe_lote_funcionario.data", "lote,produto,detalhe_lote_funcionario", "lote.id_lote = detalhe_lote_funcionario.id_lote
                AND detalhe_lote_funcionario.id_funcionario = produto.id_funcionario 
				AND detalhe_lote_funcionario.id_produto between '$id_produto_ini' and '$id_produto_fim'
				AND lote.numero between '$lote1' AND '$lote2'
				ORDER BY lote.numero ASC");
    }

    function listaLoteProdutoCondicaoLoteDataLoteAgrupado($id_produto_ini, $id_produto_fim, $lote1, $lote2) {
        return $this->listarTodosMuitosParaMuitos(
                        "SUM(detalhe_lote_funcionario.n_peca * id_operacao) AS RESULTADO, SUM(id_operacao) AS NUMEROPECA, lote.numero, lote.descricao AS DESCRICAOLOTE, produto.*, detalhe_lote_funcionario.id_produto AS DATALOTE, detalhe_lote_funcionario.id_operacao,detalhe_lote_funcionario.id AS IdDetalhe, detalhe_lote_funcionario.n_peca AS PRECOLOTE, detalhe_lote_funcionario.custo AS CUSTOPRODUCAO", ""
                . "lote,produto,detalhe_lote_funcionario",
                "lote.id_lote = detalhe_lote_funcionario.id_lote
                AND detalhe_lote_funcionario.id_funcionario = produto.id_funcionario
				AND detalhe_lote_funcionario.id_produto between '$id_produto_ini' and '$id_produto_fim'
				AND lote.numero between '$lote1' AND '$lote2'
				GROUP BY lote.numero 
				ORDER BY lote.numero ASC");
    }
    
    function graficoFaturamentoMes($mes,$ano){
        return $this->listarTodosMuitosParaMuitos("sum(id_operacao*n_peca) AS VALOR", "detalhe_lote_funcionario"," Month(id_produto) = '$mes' AND Year(id_produto) = '$ano'");
    }
    function graficoPecasMes($mes,$ano){
        return $this->listarTodosMuitosParaMuitos("sum(id_operacao) AS PECAS", "detalhe_lote_funcionario"," Month(id_produto) = '$mes' AND Year(id_produto) = '$ano'");
    }
 * 
 */

    function excluirLoteProduto($id) {
        return $this->excluir("detalhe_lote_funcionario", "id_lote='$id'");
    }

}

?>
