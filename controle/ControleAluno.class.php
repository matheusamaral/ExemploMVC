<?php

/**
 * O controle aluno é responsável por verificar, tratar e identificar possíveis erros nos
 * dados informados de acordo com a regra de negócio estabelecida.
 *
 * @author Matheus Amaral
 * @version 1.3
 * @copyright (c) 2013, Matheus Amaral
 */
require ('require.php');
class ControleAluno {
    
    /**
     * O método construtor verifica se ele tem o método solicitado pelo parametro $operacao.
     * Essa verificação é feita para ter certeza se a classe tem a o método desejado e em caso
     * negativo informar de forma amigável para camada de visão.
     * 
     * @param String $operacao É o nome do método que irá executar a ação desejada.
     */
    public function __construct($operacao) {         
        $metodos = get_class_methods($this);
        if(in_array($operacao, $metodos)){
            call_user_func(array(get_class($this), $operacao));
        }else{
            $errors = 'Não foi possível encontrar o método desejado.';               
            $o = array('success' => false, 'errors' => $errors);                
            $obj = json_encode($o);        
            echo $obj; 
        }        
    }
    
    public function matricularAluno(){        
        if(isset($_POST['nome']) && !empty($_POST['nome'])){
            if(isset($_POST['matricula']) && !empty($_POST['matricula'])){
                $aluno = new Aluno();
                $aluno->setNome($_POST['nome']);
                $aluno->setMatricula($_POST['matricula']);
                $aluno->setMomentoCadastro(date("Y-m-d H:i:s"));
                $id = $aluno->salvar();
                if($id){
                    $o = array('success' => true, 'id' => $id);                
                    echo json_encode($o);
                }else{
                    $o = array('success' => false, 'errors' => 'Erro ao adicionar aluno no banco de dados.');                
                    echo json_encode($o);
                }
            }else{
                $o = array('success' => false, 'errors' => 'Preencha o campo matricula.');                
                echo json_encode($o);
            }
        }else{
            $o = array('success' => false, 'errors' => 'Preencha o campo nome.');                
            echo json_encode($o);
        }
    }
    
    public function carregarAlunos(){
        if(isset($_GET['uid']) && $_GET['uid'] >= 0){
            $aluno = new Aluno();
            $arrayAlunos = $aluno->carregarAlunos($_GET['uid']);            
            if(!is_null($arrayAlunos)){
                $o = array('success' => true, 'alunos' => $arrayAlunos);                
                echo json_encode($o);
            }else{
                $o = array('success' => false, 'errors' => 'Não foi possível encontrar alunos.');                
                echo json_encode($o);
            }
        }else{
            $o = array('success' => false, 'errors' => 'Não foi possível encontrar ID de referência.');                
            echo json_encode($o);
        }
    }
    
}

?>
