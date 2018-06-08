<?php

include_once "../modell/BancoDadosPDO.class.php";

class MenuFilho extends BancoDadosPDO {

    protected $id_menu_filho;   
    protected $descricao;    
    protected $ordem;
    protected $id_menu;
    protected $status;
    protected $link;
    protected $icone;
    protected $permissao;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraMenuFilho($descricao,$ordem,$id_menu,$status,$link,$icone,$permissao) {
        return $this->inserir("menu_filho", "nome,ordem,id_menu,status,link,icone,permissao", "'$descricao','$ordem','$id_menu','$status','$link','$icone','$permissao'");
    }

    function listaMenuFilho($id_pai) {
        //return $this->listarTodos("menu_filho");
        return $this->listarTodos("menu_filho WHERE menu_filho.id_menu='$id_pai'  ");
    }
    function listaMenuFilhoTodos() {
        //return $this->listarTodos("menu_filho");
        return $this->listarTodos("menu_filho,menu_pai WHERE menu_filho.id_menu=menu_pai.id_menu ORDER BY menu_pai.nome_pai ASC  ");
    }

    function excluirMenuFilho($id) {
        return $this->excluir("menu_filho", "id_menu_filho='$id'");
    }

}

?>
