<?php

include_once "../modell/BancoDadosPDO.class.php";

class Config extends BancoDadosPDO {

    protected $id_config;
    protected $meta_faturamento;
    protected $meta_producao;
    protected $data_ini;
    protected $data_fim;
    protected $hora_ini;
    protected $hora_fim;
    protected $hora_des;
    protected $status;
    
    

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraConfig($meta_faturamento,$meta_producao,$data_ini,$data_fim,$hora_ini,$hora_fim,$hora_des,$status) {
        return $this->inserir("config", "meta_faturamento,meta_producao,data_ini,data_fim,hora_ini,hora_fim,hora_des,status", "'$meta_faturamento','$meta_producao','$data_ini','$data_fim','$hora_ini','$hora_fim','$hora_des','$status'");
    }

    function listaConfig() {
        return $this->listarTodos("config ORDER BY data_ini ASC");
    }

    function excluirConfig($id) {
        return $this->excluir("config", "id_config='$id'");
    }

}

?>
