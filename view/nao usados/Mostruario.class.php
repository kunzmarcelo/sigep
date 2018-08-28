<?php

include_once "../modell/BancoDadosPDO.class.php";

class Mostruario extends BancoDadosPDO {

    protected $id_mostruario;
    protected $descricao;
    protected $id_produto;
    protected $cor;
    protected $imagem;
    protected $data_cadastro;
    protected $status;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraMostruario($descricao,$id_produto,$cor,$imagem,$data_cadastro,$status) {
        return $this->inserir("mostruario", "descricao,id_produto,cor,imagem,data_cadastro,status", "'$descricao','$id_produto','$cor','$imagem','$data_cadastro','$status'");
    }

    function listaMostruario() {
        return $this->listarTodos("mostruario ORDER BY cor ASC");
    }

    function excluirMostruario($id) {
        return $this->excluir("mostruario", "id_mostruario='$id'");
    }

}

?>
