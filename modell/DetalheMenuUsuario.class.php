<?php

include_once "BancoDadosPDO.class.php";

 

class DetalheMenuUsuario extends BancoDadosPDO {

    protected $id_menu;
    protected $url;
    protected $descricao;   
    protected $status;
    protected $permissao;
    protected $icone;
    protected $data_cadastro;
    protected $update_date;    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraMenu($url,$descricao,$status,$permissao,$icone,$data_cadastro,$update_date) {
        return $this->inserir("detalhe_menu_usuario", "url,descricao,status,permissao,icone,data_cadastro,update_date", "'$url','$descricao','$status','$permissao','$icone','$data_cadastro','$update_date'");
    }

    function listaMenu() {
        return $this->listarTodos("detalhe_menu_usuario ORDER BY descricao ASC");
    }
    
    
    function listaDetalheMenu() {
        return $this->listarTodos("detalhe_menu_usuario ORDER BY descricao ASC");
    }

    function excluirMenu($id) {
        return $this->excluir("detalhe_menu_usuario", "id='$id'");
    }

}

?>
