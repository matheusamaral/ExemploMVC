<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Matheus Amaral
 * matheusamaral.si@gmail.com
 * 
 * @author matheusamaral
 */
require ('require.php');

/**
 * Fábrica construído utilizando o design pattern Factory, promove maior flexibilidade, podendo instanciar qualquer classe utilizando somente esse método.
 */
class Fabrica {
    /**
     * 
     * @param Classe $classe Espera o nome de uma classe devidamente escrita. Exemplo: Projeto, Servico, Mensagem e outros.
     * @return \Projeto|\Servico|\Reuniao|\Negocio|\Comunicacao|\Mensagem Caso retorne false indica que a classe não existe e por isso não pode ser instanciada.
     */
    public function instancia($classe, $construtor=null){
        $validar = Fabrica::validarClasse($classe);             
        if($validar == true){
            if(!is_null($construtor)){
                if(!is_array($construtor)) return new $classe($construtor);
                if(count($construtor) == 2) return new $classe($construtor[0], $construtor[1]);
                if(count($construtor) == 3) return new $classe($construtor[0], $construtor[1], $construtor[2]);
                if(count($construtor) == 4) return new $classe($construtor[0], $construtor[1], $construtor[2], $construtor[3]);
                if(count($construtor) == 5) return new $classe($construtor[0], $construtor[1], $construtor[2], $construtor[3], $construtor[4]);
                if(count($construtor) == 6) return new $classe($construtor[0], $construtor[1], $construtor[2], $construtor[3], $construtor[4], $construtor[5]);
                if(count($construtor) == 7) return new $classe($construtor[0], $construtor[1], $construtor[2], $construtor[3], $construtor[4], $construtor[5], $construtor[6]);
            }else{                
                return new $classe;
            }            
        }
        else return false;
    }

    private function validarClasse($classe){
        //Validar se a sessão atual pertence a o IP que abriu.
        //if(@$_SESSION['ipConexao'] == $_SERVER['REMOTE_ADDR'] || ($classe == 'Acessar' || $classe == "Site")){
            if(class_exists($classe)){
                return true;
            }else{
                $pastas = array('../../controle', '../../modelo', '../../visao','../../db', '../../../lib');
                foreach ($pastas as $pasta) {
                    if (file_exists("{$pasta}/{$classe}.class.php")) {
                        return true;
                    }  else {
                        return false;
                    }
                }
            }  
        //}
    }
}

//$obj = Fabrica::instancia("Projeto");
//var_dump($obj);
//exit;
//echo (get_parent_class($obj));
//$obj->salvar($obj);

?>