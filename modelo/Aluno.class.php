<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Responsável por persistir os dados referente ao aluno.
 *
 * @author Matheus Amaral
 * @version 1.3
 * @copyright (c) 2013, Matheus Amaral
 */
require 'require.php';
class Aluno implements Persistente{
    private $id;
    private $nome;
    private $matricula;
    private $momentoCadastro;
    
    public function __construct() {
        
    }
    
    public function __get($key) {
        return $this->$key;
    }
    
    public function __set($key, $valor) {
        $this->$key = $valor;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function getMomentoCadastro() {
        return $this->momentoCadastro;
    }

    public function setMomentoCadastro($momentoCadastro) {
        $this->momentoCadastro = $momentoCadastro;
    }

    public function carregar() {
        return Persistencia::carregar();
    }

    /**
     * 
     * @param boolean $tipo O valor padrão é 0, indica que sempre que chamado o método vai desativar o registro, caso você informe 1 ele vai excluir o registro permanentemente.
     * @return type
     */
    public function deletar($tipo = 0) {
        if($tipo == 0) return Persistente::desativar();
        else return Persistencia::deletar();
    }

    public function salvar() {        
        return Persistencia::salvar();
    }
    
    public function carregarAlunos($id){
        $array = array($id);
        $retorno = Persistencia::consultar($array,
                    "
                        select
                            *
                        from
                            aluno
                        where id > ?;
                    ");
        return $retorno;
    }

}

?>
