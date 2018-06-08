<?php

include_once "../modell/BancoDadosPDO.class.php";

class MenuPai extends BancoDadosPDO {

    protected $id_menu;   
    protected $nome_pai;    
    protected $status_pai;
    protected $ordem_pai;
    protected $icone_pai;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraMenuPai($nome_pai,$status_pai,$ordem_pai,$icone_pai) {
        return $this->inserir("menu_pai", "nome_pai,status_pai,ordem_pai,icone_pai", "'$nome_pai','$status_pai','$ordem_pai','$icone_pai'");
    }

    function listaMenuPai() {
        return $this->listarTodos("menu_pai");
    }

    function excluirMenuPai($id) {
        return $this->excluir("menu_pai", "id_menu='$id'");
    }

}

?>
