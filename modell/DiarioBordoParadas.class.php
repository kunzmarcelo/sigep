<?php

include_once "../modell/BancoDadosPDO.class.php";

class DiarioBordoParadas extends BancoDadosPDO {

    protected $id_diario_paradas;
    protected $id_posto;
    protected $data_trabalhada;
    protected $id_funcionario;
    protected $turno;
    protected $id_produto;
    protected $id_operacao;
    protected $hora_ini;
    protected $hora_fim;    
    protected $motivo;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraDiarioBordoParadas($id_posto, $data_trabalhada, $id_funcionario, $turno, $id_produto, $id_operacao, $hora_ini, $hora_fim, $motivo) {
        return $this->inserir("diario_bordo_paradas", "id_posto,data_trabalhada,id_funcionario,turno,id_produto,id_operacao,hora_ini,hora_fim,motivo", "'$id_posto','$data_trabalhada','$id_funcionario','$turno','$id_produto','$id_operacao','$hora_ini','$hora_fim','$motivo'");
    }

    function listaDiarioBordoParadas() {
        return $this->listarTodos("diario_bordo_paradas ORDER BY id_diario_paradas ASC");
    }

    function litaTodosDiarioBordoParadas() {
        return $this->listarTodosMuitosParaMuitos("diario_bordo_paradas.*, produto.descricao, funcionario.nome", "diario_bordo_paradas, produto,funcionario", "diario_bordo_paradas.id_produto = produto.id
and diario_bordo_paradas.id_funcionario = funcionario.id
and diario_bordo_paradas.id ORDER BY id ASC");
    }

    function listaTodosDiarioCondicaoData($data_trabalhada_ini, $data_trabalhada_fim, $id_funcionario) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo_paradas.*, produto.descricao, operacao.*, posto_trabalho.numero,funcionario.nome", "diario_bordo_paradas, produto,operacao,posto_trabalho,funcionario", "diario_bordo_paradas.id_posto = posto_trabalho.id_posto
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario
AND diario_bordo_paradas.id_operacao = operacao.id_operacao
AND diario_bordo_paradas.id_produto = produto.id_produto
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
				AND funcionario.id_funcionario = '$id_funcionario'
				ORDER BY diario_bordo_paradas.data_trabalhada, diario_bordo_paradas.turno, posto_trabalho.numero, produto.descricao, operacao.operacao ASC");
    }

    function listaTodosDiarioCondicaoDataMaquina($data_trabalhada_ini, $data_trabalhada_fim, $id_posto) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo_paradas.*, produto.descricao, operacao.*, posto_trabalho.numero,funcionario.nome", "diario_bordo_paradas, produto,operacao,posto_trabalho,funcionario", "diario_bordo_paradas.id_posto = posto_trabalho.id_posto
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario
AND diario_bordo_paradas.id_operacao = operacao.id_operacao
AND diario_bordo_paradas.id_produto = produto.id_produto
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
AND posto_trabalho.id_posto = $id_posto
ORDER BY diario_bordo_paradas.data_trabalhada,diario_bordo_paradas.turno, funcionario.nome,produto.descricao,operacao.operacao ASC;");
    }

    function listaTodosDiarioCondicaoDataProduzidos($data_trabalhada_ini, $data_trabalhada_fim, $id_funcionario) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo_paradas.*, produto.descricao, funcionario.nome,posto_trabalho.posto_trabalho, operacao.*", "diario_bordo_paradas, produto,funcionario, posto_trabalho, operacao", "diario_bordo_paradas.id_produto = produto.id
AND diario_bordo_paradas.id_posto = posto_trabalho.id
AND diario_bordo_paradas.id_produto = produto.id 
AND diario_bordo_paradas.id_funcionario = funcionario.id
AND diario_bordo_paradas.id_operacao = operacao.id
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
				AND funcionario.id = '$id_funcionario'
                                    AND total_peca != 0
				ORDER BY diario_bordo_paradas.data_trabalhada ASC");
    }

    function listaTodosDiarioCondicaoAgupado($data_trabalhada_ini, $data_trabalhada_fim, $id_funcionario) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo_paradas.data_trabalhada,funcionario.nome,sum(diario_bordo_paradas.hora_ini) AS SOMAPECABOA,sum(diario_bordo_paradas.hora_fim) AS SOMAPECARUIM", "diario_bordo_paradas, produto,funcionario, posto_trabalho,operacao", "diario_bordo_paradas.id_produto = produto.id_produto
AND diario_bordo_paradas.id_posto = posto_trabalho.id_posto
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario
AND diario_bordo_paradas.id_operacao = operacao.id_operacao
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
				AND funcionario.id_funcionario = '$id_funcionario' 
                                    GROUP BY diario_bordo_paradas.data_trabalhada
ORDER BY diario_bordo_paradas.data_trabalhada ASC");
    }

    function calculaSalario($data_trabalhada_ini, $data_trabalhada_fim, $id_funionario) {
        return $this->listarTodosMuitosParaMuitos("
            diario_bordo_paradas.data_trabalhada,
diario_bordo_paradas.hora_ini,
diario_bordo_paradas.hora_fim,
diario_bordo_paradas.id_posto,
(diario_bordo_paradas.hora_ini * operacao.custo_operacao) AS TOTALPECASBOAS, 
(diario_bordo_paradas.hora_fim * operacao.custo_operacao) AS TOTALPECASRUINS,
funcionario.nome,operacao.operacao,operacao.custo_operacao,produto.descricao", "diario_bordo_paradas, produto,funcionario, posto_trabalho,operacao", "diario_bordo_paradas.id_produto = produto.id_produto 
AND diario_bordo_paradas.id_posto = posto_trabalho.id_posto 
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario 
AND diario_bordo_paradas.id_operacao = operacao.id_operacao 
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' 
AND funcionario.id_funcionario = '$id_funionario' 
ORDER BY diario_bordo_paradas.id_posto, diario_bordo_paradas.data_trabalhada, produto.descricao ASC;"
        );
    }

    function listaTodosMaquinasFuncionarios($data_trabalhada_ini, $data_trabalhada_fim) {
        return $this->listarTodosMuitosParaMuitos(
                        "posto_trabalho.numero, funcionario.nome, 
COUNT(diario_bordo_paradas.id_posto) AS REGISTROS,
SUM(diario_bordo_paradas.hora_ini) AS PECASBOAS, 
SUM(diario_bordo_paradas.hora_fim) AS PECASRUINS ", "diario_bordo_paradas,posto_trabalho,funcionario", "diario_bordo_paradas.id_posto = posto_trabalho.id_posto
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' 
GROUP BY diario_bordo_paradas.id_posto
HAVING count(diario_bordo_paradas.id_posto) > 0
ORDER BY posto_trabalho.numero ASC;");
    }
    function listaSomaProdutos($data_trabalhada_ini, $data_trabalhada_fim) {
        return $this->listarTodosMuitosParaMuitos("diario_bordo_paradas.data_trabalhada,
SUM(diario_bordo_paradas.hora_ini) AS PECASBOAS, 
SUM(diario_bordo_paradas.hora_fim) AS PECASRUINS,
produto.descricao, operacao.operacao,funcionario.nome ",
"diario_bordo_paradas, produto,operacao,posto_trabalho,funcionario ",
"diario_bordo_paradas.id_posto = posto_trabalho.id_posto 
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario 
AND diario_bordo_paradas.id_operacao = operacao.id_operacao 
AND diario_bordo_paradas.id_produto = produto.id_produto 
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
GROUP BY diario_bordo_paradas.data_trabalhada 
ORDER BY funcionario.nome ASC");
    }
    
    function listaGraficoData($data_trabalhada_ini, $data_trabalhada_fim,$id_funcionario){
        return $this->listarTodosMuitosParaMuitos("diario_bordo_paradas.data_trabalhada,
SUM(diario_bordo_paradas.hora_ini) AS PECASBOAS, 
SUM(diario_bordo_paradas.hora_fim) AS PECASRUINS,
produto.descricao, operacao.operacao,funcionario.nome ",
"diario_bordo_paradas, produto,operacao,posto_trabalho,funcionario ",
"diario_bordo_paradas.id_posto = posto_trabalho.id_posto 
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario 
AND diario_bordo_paradas.id_operacao = operacao.id_operacao 
AND diario_bordo_paradas.id_produto = produto.id_produto 
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' 
AND funcionario.id_funcionario = '$id_funcionario' 
GROUP BY diario_bordo_paradas.data_trabalhada 
ORDER BY diario_bordo_paradas.data_trabalhada, diario_bordo_paradas.turno, posto_trabalho.numero, produto.descricao, operacao.operacao ASC");
    }
    
    function graficoPecasFuncionario($data_trabalhada_ini, $data_trabalhada_fim){
        return $this->listarTodosMuitosParaMuitos("funcionario.nome, SUM(diario_bordo_paradas.hora_ini) AS hora_ini,SUM(diario_bordo_paradas.hora_fim) AS hora_fim ","diario_bordo_paradas,funcionario,posto_trabalho","diario_bordo_paradas.id_funcionario = funcionario.id_funcionario
AND diario_bordo_paradas.id_posto = posto_trabalho.id_posto
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
GROUP BY diario_bordo_paradas.id_funcionario;");
    }
    
    function graficoParadas($data_trabalhada_ini, $data_trabalhada_fim){
        return $this->listarTodosMuitosParaMuitos("diario_bordo_paradas.data_trabalhada,,hora_fim,motivo,TIMEDIFF(diario_bordo_paradas.hora_fim, diario_bordo_paradas.hora_ini) TEMPO, funcionario.nome,posto_trabalho.numero",
                "diario_bordo_paradas,produto,posto_trabalho,operacao,funcionario",
                "diario_bordo_paradas.hora_ini !='00:00:00'
AND diario_bordo_paradas.id_produto = produto.id_produto
AND diario_bordo_paradas.id_posto = posto_trabalho.id_posto
AND diario_bordo_paradas.id_operacao = operacao.id_operacao
AND diario_bordo_paradas.id_funcionario = funcionario.id_funcionario
AND diario_bordo_paradas.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' ORDER BY funcionario.nome;");
    }

    function excluirDiarioBordoParadas($id) {
        return $this->excluir("diario_bordo_paradas", "id_diario_paradas='$id'");
    }

}

?>
