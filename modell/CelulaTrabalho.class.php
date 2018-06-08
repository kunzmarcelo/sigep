<?php

include_once "../modell/BancoDadosPDO.class.php";

class CelulaTrabalho extends BancoDadosPDO {

    protected $id_celula;
    protected $pessoas_celula;   
    protected $data;
    protected $numero_celula;
    protected $funcionarios;
    protected $setor;
    protected $status_celula;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraCelula($pessoas_celula,$data,$numero_celula,$funcionarios,$setor,$status_celula) {
        return $this->inserir("celula_trabalho", "pessoas_celula,data,numero_celula,funcionarios,setor,status_celula", "'$pessoas_celula','$data','$numero_celula','$funcionarios','$setor','$status_celula'");
    }

    function listaCelula() {
        return $this->listarTodos("celula_trabalho ORDER BY funcionarios ASC");
    }

    function excluirCelula($id) {
        return $this->excluir("celula_trabalho", "id_celula='$id'");
    }

}

?>
