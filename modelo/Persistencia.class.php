<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Persistência padrão, foi criada uma classe capaz de atender as necessidades básicas de todas as outras classes persistindo com o Banco de dados.
 *
 * @author matheusamaral
 */
require ('require.php');
require '../bd/Conecta.class.php';
abstract class Persistencia {

    public function salvar() {
        $objConecta = new Conecta();
        $erroConexao = $objConecta->conectaPDO(); 
        if(is_null($erroConexao)){
            $camposXML = Persistencia::lerXML($this);                                
            //print_r($camposXML);
            //var_dump($camposXML[0]['classePai']['tabela']);  
            //exit;
            $i = 0;$j=0;
            $coluna = "";        
            $valor = null;  
            $valores = "";
            $existeId = Persistencia::carregar(false);

                while($j < count($camposXML)){
                    $key = key($camposXML[$j]);   

                    while ($i < count($camposXML[$j][$key]->propriedades)) {                            
                        $referencia = $camposXML[$j][$key]->propriedades[$i]['atributo'] . "";                

                        if($referencia != ""){
                            if ($i != 0){
                                $coluna .= ",";                    
                                $valor .= ","; 
                                $valores .= ",";
                            }                                               
                            $coluna .=  $camposXML[$j][$key]->propriedades[$i]['coluna'] ;

                            if(is_object($this->__get($referencia))){
                                if(!$this->__get($referencia)->__get($referencia)) $valor = 'NULL';
                                else $valor .= "'".$this->__get($referencia)->__get($referencia)."'";
                                $valores .= $camposXML[$j][$key]->propriedades[$i]['coluna']."='".$this->__get($referencia)->__get($referencia)."'";
                            }
                            else{
                                if(is_null($this->__get($referencia))) $valor .= 'NULL';
                                else $valor .= "'".$this->__get($referencia)."'";
                                $valores .= $camposXML[$j][$key]->propriedades[$i]['coluna']."='".$this->__get($referencia)."'";
                            }

                        }
                        $i++;
                    }

                   if(!empty($camposXML[$j][$key]->nparaum[0]['coluna']) || !empty($camposXML[$j][$key]->umparan[0]['coluna']) || !empty($camposXML[$j][$key]->umparaum[0]['coluna'])){
                    $g=0;
                    while($g <= count($camposXML[$j][$key]->nparaum)){
                          if($camposXML[$j][$key]->nparaum[$g]['coluna']){
                              $coluna .= ",";                    
                              $valor .= ",";   
                              $valores .= ",";
                              $coluna .= $camposXML[$j][$key]->nparaum[$g]['coluna'];
                              if(!$camposXML[$j][$key]->nparaum[$g]['atributo']) $valor .= 'NULL';
                              else $valor .= "'".$this->__get($camposXML[$j][$key]->nparaum[$g]['atributo'])->getId()."'";
                              $valores .= $camposXML[$j][$key]->nparaum[$g]['coluna']."='".$this->__get($camposXML[$j][$key]->nparaum[$g]['atributo'])->getId()."'";
                          }
                        $g++;
                    }
                    $g=0;
                    while($g <= count($camposXML[$j][$key]->umparan)){
                          if($camposXML[$j][$key]->umparan[$g]['coluna']){
                              $coluna .= ",";                    
                              $valor .= ",";  
                              $valores .= ",";
                              $coluna .= $camposXML[$j][$key]->umparan[$g]['coluna'];
                              $valor .= "'".$this->__get($camposXML[$j][$key]->nparaum[$g]['atributo'])->getId()."'";
                              $valores .= $camposXML[$j][$key]->umparan[$g]['coluna']."='".$this->__get($camposXML[$j][$key]->nparaum[$g]['atributo'])->getId()."'";
                          }
                        $g++;
                    }
                    $g=0;
                     while($g <= count($camposXML[$j][$key]->umparaum)){
                          if($camposXML[$j][$key]->umparaum[$g]['coluna']){
                              $coluna .= ",";                    
                              $valor .= ",";  
                              $valores .= ",";
                              $coluna .= $camposXML[$j][$key]->umparaum[$g]['coluna'];
                              $valor .= "'".$this->__get($camposXML[$j][$key]->umparaum[$g]['atributo'])->getId()."'";
                              $valores .= $camposXML[$j][$key]->umparaum[$g]['coluna']."='".$this->__get($camposXML[$j][$key]->umparaum[$g]['atributo'])->getId()."'";
                          }
                        $g++;
                    }

                   }                              

                    if($camposXML[$j][$key]->juncaosubclasse->chave['coluna']){
                        $coluna .= ",";                    
                        $valor .= ",";  
                        $valores .= ",";
                        $coluna .= $camposXML[$j][$key]->juncaosubclasse->chave['coluna'];
                        $valor .= "'".$this->getId()."'";//verificar como vai ficar essa chamada.
                        $valores .= $camposXML[$j][$key]->juncaosubclasse->chave['coluna']."='".$this->getId()."'";
                    }

    //               echo "insert into " . $camposXML[$j][$key]['tabela'] . "(". $coluna .") values(" . $valor . ")";
    //               echo "<br><br>";
    //               exit;

                   if(!$existeId){
                       //$valorTratado = str_replace('', 'NULL', $valor);
                       //echo "insert into " . $camposXML[$j][$key]['tabela'] . "(".$coluna.") values(" . $valor . ")";   
                       //echo "ahh leke leke leke";
                       $id = $objConecta->executePDO("insert into " . $camposXML[$j][$key]['tabela'] . "(".$coluna.") values(" . $valor . ")");                   
                       $this->setId($id);

                   }
                   else{                      
                       $filtros = $this->__get('id');
                       //echo "update " . $camposXML[$j][$key]['tabela'] . " set ".$valores." where id = ".$filtros."";                   
                       $id = $objConecta->executePDO("update " . $camposXML[$j][$key]['tabela'] . " set ".$valores." where id = ".$filtros."");
                   }
                    //exit;
                    #gerar LOG
                    $coluna = "";
                    $valor = "";
                    $i=0;
                    $j++;               
                }
                return $id;
        }else return null;
        
        unset($objConecta);
        
    }

    /**
     * 
     * @param type $arrayAtributo Enviar um array com os atributos necessário para a consulta e na ordem em que devem aparecer na consulta. Ex.:$arrayAtributo = array('10254787403','aluno') - SELECT * FROM PESSOA WHERE CPF = VARIAVEL1 AND TIPO = VARIAVEL2 
     * @param type $sql A SQL propriamente dita utilizando ? no lugar da variavel
     * @return array
     */
    public function consultar($arrayParametro, $sql) {
        $objConecta = new Conecta();        
        $erroConexao = $objConecta->conectaPDO(); 
        if(is_null($erroConexao)){
            if($arrayParametro){
                foreach ($arrayParametro as $parametro){                
                    if(is_string($parametro)) $objConecta->setString($parametro);
                    else if(is_int($parametro)) $objConecta->setNumber($parametro);
                    else return false;
                }
            }

            $objConecta->executePDO($sql);
            $resultado = ($objConecta->getExec()->fetchAll());        
            return $resultado;
        }else return null;
            
    }
    
    /**
    * método carregar()
    * * Recupera (retorna) um objeto da base de dados
    * * através de sua chave primaria e instancia ele em memória
    * @param [$tag]
    * * true = indica que o objeto deve ser carregado na memória
    * * false = indica que é apenas para verificar se o objeto existe no banco de dados
    */
    public function carregar($tag = true, $filtro = "sim"){                         
            $dados = Persistencia::getIdXml($this);           
            $validaId = explode("=", $dados['filtro']);  
            $validaId2 = rtrim($validaId[1]);
            
            if(!empty($dados)){
                $objConecta = new Conecta();
                $objConecta->conectaPDO();                   
                if($filtro == "sim"){      
                    if(empty($validaId2)){
                        return false;
                    }
                    $sql = "SELECT * FROM {$dados['tabela'][0]} WHERE {$dados['filtro']}";
                }else{                       
                    $sql = "SELECT * FROM {$dados['tabela'][0]} WHERE ATIVO = 1";
                }
          
                $objConecta->executePDO($sql);                
                
                // se retornou algum dado
                               
                if($filtro == 'sim'){
                    $resultado = $objConecta->getExec();                 
                    if ($resultado){
                        // retorna os dados em forma de objeto                            
                        $obj = $resultado->fetchObject(get_class($this));                                               
                    }
                    
                    if (is_object($obj)){
			if($tag) Persistencia::populaObjeto($obj);
			return true;
                    }
                    unset($objConecta);
                    
                }else{
                    $resultado = $objConecta->getExec()->fetchAll();                    
                    return $resultado;
                }
            }
            return false;
    }    
    
    public function atualizar($arrayParametro, $sql){
        $objConecta = new Conecta();
        $objConecta->conectaPDO();
        
        foreach ($arrayParametro as $parametro){     
            if(is_string($parametro)) $objConecta->setString($parametro);
            else if(is_int($parametro)) $objConecta->setNumber($parametro);
            else return false;
        }
        
        $objConecta->executePDO($sql);                        
        return true;
    }
    
    public function populaObjeto($obj){        
        $camposXML = Persistencia::lerXML($obj);
        $key = key($camposXML[0]);
        //echo $key."<br><br>";
        $i=0;
        while ($i < count($camposXML[0][$key]->propriedades)) {   
           if(!empty($camposXML[0][$key]->nparaum[$i]['coluna'])){
                $referenciaV = $camposXML[0][$key]->nparaum[$i]['coluna'] . "";
                $referenciaA = $camposXML[0][$key]->nparaum[$i]['atributo'] . "";
                //echo $referenciaV."<--<br>";
                //echo($camposXML[0][$key]->nparaum[0]['atributo']);
                //var_dump($obj->__get($referenciaV));
                $this->__get($referenciaA)->setId($obj->__get($referenciaV));
           }
           
                $referencia = $camposXML[0][$key]->propriedades[$i]['coluna'] . "";                
                //echo $referencia."<br>";
                //echo $referencia." = ".$obj->__get($referencia)."<br>";
                $this->__set($referencia, $obj->__get($referencia));
           
           //echo $referencia." = ".$this->__get($referencia)."<br>";
           $i++;
        }
        //return $this;
    }
    
    /**
    * método deletar()
    * * Exclui ou desativa um objeto da base de dados através de sua chave primaria.
    */
    public function deletar($obj, $tipo){
            $dados = Persistencia::getIdXml($obj);
            if($dados){
                $objConecta = new Conecta();
                $objConecta->conectaPDO();               

                if($tipo == 1){
                    $sql = "DELETE FROM {$dados['tabela']} WHERE {$dados['filtro']}";
                }
                else if($tipo == 0){
                    $sql = "UPDATE {$dados['tabela']} SET ATIVO = 0 WHERE {$dados['filtro']}";
                }
                
                $objConecta->executePDO($sql);               
                unset($objConecta);
                return true;
            }else{
                return false;
            }
            #gerar LOG
    }     
    
    /**
     * 
     * @param type $id Retorna um array de objeto populado com os envolvidos na publicacao
     */
    public function getEnvolvidosPublicacao($id){                   
            $objConecta = new Conecta();
            $objConecta->conectaPDO();

            $sql = "SELECT * FROM notificacao WHERE publicacao_id = {$id}";            
            $objConecta->executePDO($sql);                                            
                
            $resultado = $objConecta->getExec()->fetchAll();                                        
            unset($objConecta);
            return $resultado;
                                                
    }
    
     /**
     * Metodo para verificar quantos e quais atributos são chave no XML   
     * retorna um array com os atributos que são chave primaria e seus valores
     * @param type $classe A classe this
     */
    public function getIdXml($obj){
        $caminho = "../modelo/mapeamentoBD.xml";
        $classe = get_class($obj);        
        $xml = simplexml_load_file($caminho);
        
        for ($i = 0; $i < count($xml->classe); $i++) {
            if (rtrim($xml->classe[$i]['nome']) == rtrim($classe)) {
                //for ($j = 0; $j < count($xml->classe->id); $j++) {
                    $chave = $xml->classe->id['coluna'];                   
                    $arrayId = $chave." = ".$obj->getId();
                    $tabela = $xml->classe[$i]['tabela'];
                //}
            }
        }
        
        if(count($arrayId) > 1){
            $ids = implode(" AND ", $arrayId);  
            $dados = array('filtro'=>$ids, 'tabela'=>$tabela);
        }else{
            $dados = array('filtro'=>$arrayId, 'tabela'=>$tabela);
        }       
        return $dados;
        
    }   

    private function lerXML($obj) {
        $caminho = "../modelo/mapeamentoBD.xml";
        
        if(is_file($caminho)){           
            $xml = simplexml_load_file($caminho);                    
            
            $validaClassePai = false;
            if(get_parent_class($obj)){           
                //valida a classe PAI no XML
                for ($i = 0; $i < count($xml->classe); $i++) {
                     if (rtrim($xml->classe[$i]->juncaosubclasse['nome']) == rtrim(get_parent_class($obj))) {
                         $validaClassePai = true;
                     }
                 }

                if($validaClassePai == true){
                     for ($i = 0; $i < count($xml->classe); $i++) {
                         if (rtrim($xml->classe[$i]['nome']) == rtrim(get_parent_class($obj))) {
                             $array[] = array("classePai"=>$xml->classe[$i]);//retorna a classe PAI
                         }
                     } 

                     for ($i = 0; $i < count($xml->classe); $i++) {
                         if (rtrim($xml->classe[$i]['nome']) == rtrim(get_class($obj))) {
                             $array[] = array("classeFilha"=>$xml->classe[$i]);
                         }
                     }  
                     return $array;
                }
            }else{                
                for ($i = 0; $i < count($xml->classe); $i++) {
                     if (rtrim($xml->classe[$i]['nome']) == rtrim(get_class($obj))) {                         
                         $array[] = array("classeFilha"=>$xml->classe[$i]);
                     }
                 } 
                 return $array;
            }
        }else echo "erro ao ler arquivo XML.";
                            
        
                                   
    }

    function checarURI($uri){
        return (@fclose(@fopen($uri, 'r'))) ? true : false;
    }

}

?>
