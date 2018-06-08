<?php

include_once "../modell/BancoDadosPDO.class.php";

class DetalheFuncionarioProduto extends BancoDadosPDO {

    protected $id;
    protected $id_funcionario;
    protected $id_produto;
    protected $id_operacao;
    protected $id_producao;
    protected $peca_produzida;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraDetalheFuncionarioProduto($id_funcionario, $id_produto, $id_operacao, $id_producao, $peca_produzida) {
        return $this->inserir("detalhe_funcionario_produto", "id_funcionario,id_produto,id_operacao,id_producao,peca_produzida", "'$id_funcionario','$id_produto','$id_operacao','$id_producao','$peca_produzida'");
    }

    function listaDetalheFuncionarioProduto($id_operacao, $id_produto) {
        return $this->listarTodos("detalhe_funcionario_produto WHERE id_operacao = '$id_operacao' AND id_produto='$id_produto'");
    }

    function listaDetalheFuncionarioProdutoCondicao($numero) {
        return $this->listarTodosMuitosParaMuitos("
            detalhe_funcionario_produto.id,
            detalhe_funcionario_produto.peca_produzida,
            produto.descricao,
            operacao.operacao,
            funcionario.nome,
            producao.* ", "funcionario,operacao,produto,detalhe_funcionario_produto,producao", "detalhe_funcionario_produto.id_funcionario = funcionario.id_funcionario AND
            detalhe_funcionario_produto.id_produto = produto.id_produto AND
            detalhe_funcionario_produto.id_operacao = operacao.id_operacao AND
            detalhe_funcionario_produto.id_producao = producao.id_producao AND
            producao.numero = '$numero' ORDER BY detalhe_funcionario_produto.id DESC ;");
    }

    function listaDetalheFuncionarioProdutoCondicaoDatas($id_funcionario, $data_ini, $data_fim) {
        return $this->listarTodosMuitosParaMuitos("
            detalhe_funcionario_produto.id,
            detalhe_funcionario_produto.peca_produzida,
produto.descricao,
operacao.*,
funcionario.nome,
producao.* ", 
             "funcionario,operacao,produto,detalhe_funcionario_produto,producao", 
             "detalhe_funcionario_produto.id_funcionario = funcionario.id_funcionario AND
detalhe_funcionario_produto.id_produto = produto.id_produto AND
detalhe_funcionario_produto.id_operacao = operacao.id_operacao AND
detalhe_funcionario_produto.id_producao = producao.id_producao AND
            detalhe_funcionario_produto.id_funcionario = '$id_funcionario' AND
            producao.data_ini >= '$data_ini' AND producao.data_fim<='$data_fim';");
    }

    function excluirDetalheFuncionarioProduto($id) {
        return $this->excluir("detalhe_funcionario_produto", "id_detalhe_funcionario_produto='$id'");
    }

}

?>
