<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Obriga as classes que utilizarem a Classe Persistente a implementar seus métodos.
 * @author matheusamaral
 * @version 1.3
 * @copyright (c) 2013, Matheus Amaral
 */
interface Persistente {
    
    /**
     * Todas as classes vão implementar esse método
     */
    public function salvar();
    
    /**
     * 
     * @param type $tipo Define se vai realmente excluir ou desativar o registro.
     */
    public function deletar($tipo=0);
    
    /**
     * Todas as classes vão implementar esse método
     */
    public function carregar();
}

?>
