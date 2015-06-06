<?php

/*
 * 1ºPasso -> Instancia a classe $objConecta= new Conecta();
 * 2ºPasso-> Executa o Método PDO $objConecta->conectaPDO();
 * 3ºPasso -> seta os ? atraves dos metodos setString ou setNumber (depende do que é a ?) PS. tem que ser na ordem correta das ?
 * 4ºPasso -> Carrega a sentença no metodo executePDO
 * Ex: $objConecta->executePDO("insert  into instituicao (cnpj, razaosocial) values (?,?)");
 *  
 */

/**
 * Description of Conecta
 *
 * @author Dhieyson
 *
 */
class Conecta {

    private $host;
    private $user;
    private $passwd;
    private $db;
    private $objPdo;
    private $query;
    private $argumentos = array();
    private $args = array();
    private  $exec;
 

    /**
     * Seta as configurações que serão utilizadas com o banco de dados
     */

    public function __construct() {
        $this->host = "127.0.0.1";
        $this->user = "root";
        $this->passwd = "";
        $this->db = "tc_matheus";
        $this->query;
    }

    public function  __destruct() {
        
    }
   public function getExec() {
        return $this->exec;
    }
    /**
     * Instancia o objeto PDO
     */
    public function conectaPDO() {
        try {
            //echo "conectou ";
            @$this->objPdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db.";charset=UTF-8",  $this->user, $this->passwd);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
  public function getResultset(){
     return $this->exec->rowCount();

      
  }
    
  public function executePDO($queryPrepare) {
     try {
            $this->objPdo->beginTransaction();

            if (is_array($queryPrepare)) {
                foreach ($queryPrepare as $query) {
                    $this->exec = $this->objPdo->prepare($query);
                }
            } else {
                $this->exec = $this->objPdo->prepare($queryPrepare);
                }

            $this->exec->execute($this->args);
            /**
             * Testa se houve sucesso ao executar o método execute.
             * Se não houver falhas o else é executado.
             */
            if (0 <> $this->exec->errorCode()) {
                return ($this->exec->errorInfo());
                $this->objPdo->rollBack();
            } else {
                $id = $this->objPdo->lastInsertId();
                $this->objPdo->commit();
            }
        } catch (PDOException $e) {
            $this->objPdo->rollBack();
            // echo "Erro na transação do banco de dados" . ($e->getMessage());
        }

        return $id;
  }
    

/**
 * Set's referente ao tratamento de SQL Injection
 * OBS. Serão tratados além daqui, pelo PDO.
 */

    public function setString($value) {
        $value = mysql_escape_string($value);
        //$this->argumentos[$this->id++] = "'" . $value . "'";
        $this->args[] = $value;
        return $value;
    }

     public function setVariavel($value) {
        $value = mysql_escape_string($value); 

       return $value;
    }

    public function setNumber($value) {
        if ($value === null) {
            $this->argumentos[$this->id++] = "null";
            return;
        }
        if (!is_numeric($value)) {
            throw new Exception($value . ' is not a number');
        }
        //$this->argumentos[$this->id++] = "'" . $value . "'";
        $this->args[] = $value;
    }
  

}

?>
