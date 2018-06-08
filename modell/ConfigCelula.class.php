<?php

include_once "../modell/BancoDadosPDO.class.php";

class ConfigCelula extends BancoDadosPDO {

    protected $id_config;   
    protected $data_ini;
    protected $data_fim;
    protected $hora_ini;
    protected $hora_fim;
    protected $hora_des;
    protected $status;
    protected $desconto;
    protected $dias_semana;
    protected $dias;
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraConfig($data_ini,$data_fim,$hora_ini,$hora_fim,$hora_des,$status,$desconto,$dias_semana,$dias) {
        return $this->inserir("config_celula", "data_ini,data_fim,hora_ini,hora_fim,hora_des,status,desconto,dias_semana,dias", "'$data_ini','$data_fim','$hora_ini','$hora_fim','$hora_des','$status','$desconto','$dias_semana','$dias'");
    }

    function listaConfig() {
        return $this->listarTodos("config_celula ORDER BY data_ini ASC");
    }
    function listaUmConfig($dias) {
        return $this->listarUm("config_celula", "dias='$dias'");
    }

    function excluirConfig($id) {
        return $this->excluir("config_celula", "id_config='$id'");
    }

}

?>
