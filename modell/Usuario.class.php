<?php

include_once "../modell/BancoDadosPDO.class.php";

class Usuario extends BancoDadosPDO {

    protected $id;
    protected $login;
    protected $senha;
    protected $data_login;
    protected $status;
    protected $permissao;
    protected $obs;
    protected $nome;
    protected $sobrenome;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraUsuario($login, $senha, $data_login, $status, $permissao,$obs,$nome,$sobrenome) {
        return $this->inserir("usuario", "login, senha, data_login,status,permissao,obs,nome,sobrenome", "'$login', md5('$senha'), '$data_login','$status','$permissao','$obs','$nome','$sobrenome'");
    }

    function listaUsuario() {
        return $this->listarTodos("usuario");
    }

    function excluirUsuario($id) {
        return $this->excluir("usuario", "id='$id'");
    }

}

?>
