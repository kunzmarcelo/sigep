<?php

include_once "../modell/BancoDadosPDO.class.php";

class Produto extends BancoDadosPDO {

    protected $id;
    protected $descricao;
    protected $preco;
    protected $material;
    protected $custo_material;
    protected $tempo_producao;
    protected $descricao2;
    protected $material2;
    protected $custo_material2;
    protected $update_date;
    protected $status;

    function __get($atributo) {
        return $this->$atributo;
    }

    function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    function cadastraProduto($descricao,$preco, $material, $custo_material,$tempo_producao,$descricao2,$material2,$custo_material2,$update_date,$status) {
        return $this->inserir("produto", "descricao,preco,material,custo_material,tempo_producao,descricao2,material2,custo_material2,update_date,status", "'$descricao','$preco','$material','$custo_material','$tempo_producao','$descricao2','$material2','$custo_material2','$update_date','$status'");
    }

    function listaProduto() {
        return $this->listarTodos("produto ORDER BY descricao ASC");
    }

    function excluirProduto($id) {
        return $this->excluir("produto", "id_produto='$id'");
    }

}

?>
