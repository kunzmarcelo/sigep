<?php

include_once "../modell/BancoDadosPDO.class.php";

class DetalheCelulaProduto extends BancoDadosPDO {

    protected $id;
    protected $id_celula;
    protected $id_produto;
    protected $data;
    protected $pecas_determinadas;
    protected $pecas_finalizadas;
    protected $tempo_unitario;
    protected $status;
    protected $obs;
    protected $falta;
    protected $motivo_falta;
    protected $margem_erro;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraCelulaProduto($id_celula, $id_produto, $data, $pecas_determinadas, $pecas_finalizadas, $tempo_unitario, $status, $obs, $falta, $motivo_falta, $margem_erro) {
        return $this->inserir("detalhe_celula_produto", "id_celula,id_produto,data,pecas_determinadas,pecas_finalizadas,tempo_unitario, status,obs,falta,motivo_falta,margem_erro", "'$id_celula','$id_produto','$data','$pecas_determinadas','$pecas_finalizadas','$tempo_unitario','$status','$obs','$falta','$motivo_falta','$margem_erro'");
    }

    function listaCelulaProduto() {
        return $this->listarTodos("detalhe_celula_produto");
    }

    function listaCelulaProdutoTodos($data_ini) {
        return $this->listarTodosMuitosParaMuitos("detalhe_celula_produto.*,detalhe_celula_produto.obs AS OBSERVACAO,detalhe_celula_produto.data AS DATACELULA,produto.descricao, celula_trabalho.*", "detalhe_celula_produto,produto,celula_trabalho", "detalhe_celula_produto.id_celula = celula_trabalho.id_celula
AND detalhe_celula_produto.id_produto = produto.id_produto AND detalhe_celula_produto.data ='$data_ini' ORDER BY celula_trabalho.funcionarios ASC");
    }

    function listaCelulaProdutoTodosCelula($data_ini, $id_celula) {
        return $this->listarTodosMuitosParaMuitos("detalhe_celula_produto.*,detalhe_celula_produto.data AS DATACELULA,detalhe_celula_produto.obs AS OBSERVACAO,produto.descricao, celula_trabalho.*", "detalhe_celula_produto,produto,celula_trabalho", "detalhe_celula_produto.id_celula = celula_trabalho.id_celula
AND detalhe_celula_produto.id_produto = produto.id_produto 
AND detalhe_celula_produto.data ='$data_ini'
AND celula_trabalho.id_celula='$id_celula'
ORDER BY detalhe_celula_produto.status DESC");
    }

    function listaCelulaProdutoPecasFantantes($data_ini, $data_fim, $status, $id_celula) {
        return $this->listarTodosMuitosParaMuitos("
            detalhe_celula_produto.data AS DATACELULA, 
            detalhe_celula_produto.obs AS OBSERVACAO, 
detalhe_celula_produto.*,
produto.descricao, celula_trabalho.funcionarios,celula_trabalho.pessoas_celula", "detalhe_celula_produto, produto,celula_trabalho", "detalhe_celula_produto.id_produto = produto.id_produto
AND detalhe_celula_produto.id_celula = celula_trabalho.id_celula
AND detalhe_celula_produto.data between '$data_ini' AND '$data_fim'
AND detalhe_celula_produto.status = $status
AND detalhe_celula_produto.id_celula = $id_celula ORDER BY detalhe_celula_produto.data ASC");
    }
    function listaCelulaProdutoPecasVelocidade($data_ini, $data_fim, $id_celula) {
        return $this->listarTodosMuitosParaMuitos("
            detalhe_celula_produto.data AS DATACELULA,
sum(detalhe_celula_produto.pecas_determinadas) AS pecas_determinadas,
sum(detalhe_celula_produto.pecas_finalizadas) AS pecas_finalizadas,
celula_trabalho.funcionarios",
                "detalhe_celula_produto, produto,celula_trabalho", 
                "detalhe_celula_produto.id_produto = produto.id_produto 
AND detalhe_celula_produto.id_celula = celula_trabalho.id_celula 
AND detalhe_celula_produto.data between '$data_ini' AND '$data_fim'
AND detalhe_celula_produto.id_celula = $id_celula GROUP BY detalhe_celula_produto.data");
    }

    function listaCelulaProdutodDiasProducao($data_ini, $data_fim, $id_celula, $id_produto) {
        return $this->listarTodosMuitosParaMuitos("
            detalhe_celula_produto.data AS DATACELULA, 
            detalhe_celula_produto.obs AS OBSERVACAO, 
detalhe_celula_produto.*,
produto.descricao, celula_trabalho.funcionarios,celula_trabalho.pessoas_celula", "detalhe_celula_produto, produto,celula_trabalho", "detalhe_celula_produto.id_produto = produto.id_produto
AND detalhe_celula_produto.id_celula = celula_trabalho.id_celula
AND detalhe_celula_produto.data between '$data_ini' AND '$data_fim'
AND celula_trabalho.id_celula='$id_celula'
AND produto.id_produto = '$id_produto'
ORDER BY detalhe_celula_produto.status DESC");
    }

    function graficoPecasDia($data) {
        return $this->listarTodosMuitosParaMuitos("detalhe_celula_produto.data, sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS, sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS ", "detalhe_celula_produto", "detalhe_celula_produto.data='$data'");
    }

    function graficoPecasDiaCelula($data, $id_celula) {
        return $this->listarTodosMuitosParaMuitos("celula_trabalho.funcionarios AS NOMES, detalhe_celula_produto.data, sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS, sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS ", "detalhe_celula_produto,celula_trabalho", "detalhe_celula_produto.id_celula = celula_trabalho.id_celula  AND detalhe_celula_produto.data='$data' AND detalhe_celula_produto.id_celula='$id_celula'");
    }

    function graficoPecasMesAno($data_ini) {
        return $this->listarTodosMuitosParaMuitos("detalhe_celula_produto.data,detalhe_celula_produto.id_celula sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS, sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS ", "detalhe_celula_produto", " detalhe_celula_produto.data= '$data_ini' ");
    }

    function somaMensal($mes, $ano) {
        return $this->listarTodosMuitosParaMuitos("detalhe_celula_produto.data, sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS, sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS ", "detalhe_celula_produto", " Month(detalhe_celula_produto.data) = '$mes' AND Year(data) = '$ano'");
    }

    function graficoFaturamentoMes($mes, $ano) {
        return $this->listarTodosMuitosParaMuitos("sum(pecas_determinadas) AS NPECAS, sum(pecas_finalizadas) AS NFEITAS", "detalhe_celula_produto", " Month(data) = '$mes' AND Year(data) = '$ano'");
    }

    function somaMensalProdutoCelula($data_ini, $data_fim,$id_celula) {
        return $this->listarTodosMuitosParaMuitos(" sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS ,
                sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS,
                produto.*,celula_trabalho.funcionarios", 
                "detalhe_celula_produto, produto,celula_trabalho", 
                "detalhe_celula_produto.id_produto = produto.id_produto
                AND detalhe_celula_produto.id_celula = celula_trabalho.id_celula
                AND detalhe_celula_produto.data between '$data_ini' AND '$data_fim'
                AND detalhe_celula_produto.id_celula = $id_celula    
                GROUP BY produto.descricao");
    }

    function graficoMensalProdutoCelula($mes,$ano, $id_celula) {
        return $this->listarTodosMuitosParaMuitos(
               "sum(detalhe_celula_produto.pecas_determinadas) AS NPECAS ,
		sum(detalhe_celula_produto.pecas_finalizadas) AS NFEITAS,
		sum(detalhe_celula_produto.pecas_determinadas*preco)AS TOTALNPECAS,
		sum(detalhe_celula_produto.pecas_finalizadas*preco)AS TOTALNFEITAS,
                detalhe_celula_produto.data,
		produto.preco, produto.descricao,celula_trabalho.funcionarios", "detalhe_celula_produto, produto,celula_trabalho", "detalhe_celula_produto.id_produto = produto.id_produto
                AND detalhe_celula_produto.id_celula = celula_trabalho.id_celula
                AND MONTH(detalhe_celula_produto.data)='$mes' 
                AND YEAR(detalhe_celula_produto.data)='$ano'
                AND celula_trabalho.pessoas_celula='$id_celula'
                GROUP BY MONTH(detalhe_celula_produto.data);");
    }

//AND detalhe_celula_produto.status = '$status'
    function excluirCelulaProduto($id) {
        return $this->excluir("detalhe_celula_produto", "id='$id'");
    }

}
