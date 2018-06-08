<?php

include_once "../modell/BancoDadosPDO.class.php";

class DiarioBordo extends BancoDadosPDO {

    protected $id_diario;
    protected $id_posto;
    protected $data_trabalhada;
    protected $id_funcionario;
    protected $turno;
    protected $id_produto;
    protected $id_operacao;
    protected $pecas_boas;
    protected $pecas_ruins;
    protected $hora_ini;
    protected $hora_fim;
    protected $motivo;
    protected $obs;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraDiarioBordo($id_posto, $data_trabalhada, $id_funcionario, $turno, $id_produto, $id_operacao, $pecas_boas, $pecas_ruins, $hora_ini, $hora_fim, $motivo, $obs) {
        return $this->inserir("diario_bordo", "id_posto,data_trabalhada,id_funcionario,turno,id_produto,id_operacao,pecas_boas,pecas_ruins,hora_ini,hora_fim,motivo,obs", "'$id_posto','$data_trabalhada','$id_funcionario','$turno','$id_produto','$id_operacao','$pecas_boas','$pecas_ruins','$hora_ini','$hora_fim','$motivo','$obs'");
    }

    function listaDiarioBordo() {
        return $this->listarTodos("diario_bordo ORDER BY id_diario ASC");
    }

    function litaTodosDiario() {
        return $this->listarTodosMuitosParaMuitos("diario_bordo.*, produto.descricao, funcionario.nome", "diario_bordo, produto,funcionario", "diario_bordo.id_produto = produto.id
and diario_bordo.id_funcionario = funcionario.id
and diario_bordo.id ORDER BY id ASC");
    }

    function listaTodosDiarioCondicaoData($data_trabalhada_ini, $data_trabalhada_fim, $id_funcionario) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo.*, produto.descricao, operacao.*, posto_trabalho.numero,funcionario.nome", "diario_bordo, produto,operacao,posto_trabalho,funcionario", "diario_bordo.id_posto = posto_trabalho.id_posto
AND diario_bordo.id_funcionario = funcionario.id_funcionario
AND diario_bordo.id_operacao = operacao.id_operacao
AND diario_bordo.id_produto = produto.id_produto
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
				AND funcionario.id_funcionario = '$id_funcionario'
				ORDER BY diario_bordo.data_trabalhada, diario_bordo.turno, posto_trabalho.numero, produto.descricao, operacao.operacao ASC");
    }

    function listaTodosDiarioCondicaoDataMaquina($data_trabalhada_ini, $data_trabalhada_fim, $id_posto) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo.*, produto.descricao, operacao.*, posto_trabalho.numero,funcionario.nome", "diario_bordo, produto,operacao,posto_trabalho,funcionario", "diario_bordo.id_posto = posto_trabalho.id_posto
AND diario_bordo.id_funcionario = funcionario.id_funcionario
AND diario_bordo.id_operacao = operacao.id_operacao
AND diario_bordo.id_produto = produto.id_produto
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
AND posto_trabalho.id_posto = $id_posto
ORDER BY diario_bordo.data_trabalhada,diario_bordo.turno, funcionario.nome,produto.descricao,operacao.operacao ASC;");
    }

    function listaTodosDiarioCondicaoDataProduzidos($data_trabalhada_ini, $data_trabalhada_fim, $id_funcionario) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo.*, produto.descricao, funcionario.nome,posto_trabalho.posto_trabalho, operacao.*", "diario_bordo, produto,funcionario, posto_trabalho, operacao", "diario_bordo.id_produto = produto.id
AND diario_bordo.id_posto = posto_trabalho.id
AND diario_bordo.id_produto = produto.id 
AND diario_bordo.id_funcionario = funcionario.id
AND diario_bordo.id_operacao = operacao.id
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
				AND funcionario.id = '$id_funcionario'
                                    AND total_peca != 0
				ORDER BY diario_bordo.data_trabalhada ASC");
    }

    function listaTodosDiarioCondicaoAgupado($data_trabalhada_ini, $data_trabalhada_fim, $id_funcionario) {
        return $this->listarTodosMuitosParaMuitos(
                        "diario_bordo.data_trabalhada,funcionario.nome,sum(diario_bordo.pecas_boas) AS SOMAPECABOA,sum(diario_bordo.pecas_ruins) AS SOMAPECARUIM", "diario_bordo, produto,funcionario, posto_trabalho,operacao", "diario_bordo.id_produto = produto.id_produto
AND diario_bordo.id_posto = posto_trabalho.id_posto
AND diario_bordo.id_funcionario = funcionario.id_funcionario
AND diario_bordo.id_operacao = operacao.id_operacao
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
				AND funcionario.id_funcionario = '$id_funcionario' 
                                    GROUP BY diario_bordo.data_trabalhada
ORDER BY diario_bordo.data_trabalhada ASC");
    }

    function calculaSalario($data_trabalhada_ini, $data_trabalhada_fim, $id_funionario) {
        return $this->listarTodosMuitosParaMuitos("
            diario_bordo.data_trabalhada,
diario_bordo.pecas_boas,
diario_bordo.pecas_ruins,
diario_bordo.hora_ini,
diario_bordo.hora_fim,
diario_bordo.motivo,
diario_bordo.id_posto,
(diario_bordo.pecas_boas * operacao.custo_operacao) AS TOTALPECASBOAS, 
(diario_bordo.pecas_ruins * operacao.custo_operacao) AS TOTALPECASRUINS,
funcionario.nome,operacao.operacao,operacao.custo_operacao,produto.descricao", "diario_bordo, produto,funcionario, posto_trabalho,operacao", "diario_bordo.id_produto = produto.id_produto 
AND diario_bordo.id_posto = posto_trabalho.id_posto 
AND diario_bordo.id_funcionario = funcionario.id_funcionario 
AND diario_bordo.id_operacao = operacao.id_operacao 
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' 
AND funcionario.id_funcionario = '$id_funionario' 
ORDER BY diario_bordo.id_posto, diario_bordo.data_trabalhada, produto.descricao ASC;"
        );
    }

    function listaTodosMaquinasFuncionarios($data_trabalhada_ini, $data_trabalhada_fim) {
        return $this->listarTodosMuitosParaMuitos(
                        "posto_trabalho.numero, funcionario.nome, 
COUNT(diario_bordo.id_posto) AS REGISTROS,
SUM(diario_bordo.pecas_boas) AS PECASBOAS, 
SUM(diario_bordo.pecas_ruins) AS PECASRUINS ", "diario_bordo,posto_trabalho,funcionario", "diario_bordo.id_posto = posto_trabalho.id_posto
AND diario_bordo.id_funcionario = funcionario.id_funcionario
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' 
GROUP BY diario_bordo.id_posto
HAVING count(diario_bordo.id_posto) > 0
ORDER BY posto_trabalho.numero ASC;");
    }
    function listaSomaProdutos($data_trabalhada_ini, $data_trabalhada_fim) {
        return $this->listarTodosMuitosParaMuitos("diario_bordo.data_trabalhada,
SUM(diario_bordo.pecas_boas) AS PECASBOAS, 
SUM(diario_bordo.pecas_ruins) AS PECASRUINS,
produto.descricao, operacao.operacao,funcionario.nome ",
"diario_bordo, produto,operacao,posto_trabalho,funcionario ",
"diario_bordo.id_posto = posto_trabalho.id_posto 
AND diario_bordo.id_funcionario = funcionario.id_funcionario 
AND diario_bordo.id_operacao = operacao.id_operacao 
AND diario_bordo.id_produto = produto.id_produto 
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
GROUP BY diario_bordo.data_trabalhada 
ORDER BY funcionario.nome ASC");
    }
    
    function listaGraficoData($data_trabalhada_ini, $data_trabalhada_fim,$id_funcionario){
        return $this->listarTodosMuitosParaMuitos("diario_bordo.data_trabalhada,
SUM(diario_bordo.pecas_boas) AS PECASBOAS, 
SUM(diario_bordo.pecas_ruins) AS PECASRUINS,
produto.descricao, operacao.operacao,funcionario.nome ",
"diario_bordo, produto,operacao,posto_trabalho,funcionario ",
"diario_bordo.id_posto = posto_trabalho.id_posto 
AND diario_bordo.id_funcionario = funcionario.id_funcionario 
AND diario_bordo.id_operacao = operacao.id_operacao 
AND diario_bordo.id_produto = produto.id_produto 
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' 
AND funcionario.id_funcionario = '$id_funcionario' 
GROUP BY diario_bordo.data_trabalhada 
ORDER BY diario_bordo.data_trabalhada, diario_bordo.turno, posto_trabalho.numero, produto.descricao, operacao.operacao ASC");
    }
    
    function graficoPecasFuncionario($data_trabalhada_ini, $data_trabalhada_fim){
        return $this->listarTodosMuitosParaMuitos("funcionario.nome, SUM(diario_bordo.pecas_boas) AS pecas_boas,SUM(diario_bordo.pecas_ruins) AS pecas_ruins ","diario_bordo,funcionario,posto_trabalho","diario_bordo.id_funcionario = funcionario.id_funcionario
AND diario_bordo.id_posto = posto_trabalho.id_posto
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim'
GROUP BY diario_bordo.id_funcionario;");
    }
    
    function graficoParadas($data_trabalhada_ini, $data_trabalhada_fim){
        return $this->listarTodosMuitosParaMuitos("diario_bordo.data_trabalhada,hora_ini,hora_fim,motivo,TIMEDIFF(diario_bordo.hora_fim, diario_bordo.hora_ini) TEMPO, funcionario.nome,posto_trabalho.numero",
                "diario_bordo,produto,posto_trabalho,operacao,funcionario",
                "diario_bordo.hora_ini !='00:00:00'
AND diario_bordo.id_produto = produto.id_produto
AND diario_bordo.id_posto = posto_trabalho.id_posto
AND diario_bordo.id_operacao = operacao.id_operacao
AND diario_bordo.id_funcionario = funcionario.id_funcionario
AND diario_bordo.data_trabalhada between '$data_trabalhada_ini' AND '$data_trabalhada_fim' ORDER BY funcionario.nome;");
    }

    function excluirDiarioBordo($id) {
        return $this->excluir("diario_bordo", "id_diario='$id'");
    }

}

?>
