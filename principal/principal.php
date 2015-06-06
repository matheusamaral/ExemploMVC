<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require '../controle/require.php';

 if (isset($_GET['c']) && isset($_GET['o'])) {
    $classe = $_GET['c'];
    $operacao = $_GET['o'];
    if(isset($classe)){
        $valida = Fabrica::instancia($classe, $operacao);
        if(!$valida) Fabrica::instancia("Acessar", "desconhecida");
    }
    else Fabrica::instancia("Acessar", "desconhecida");
 }else Fabrica::instancia("Acessar", "desconhecida"); 



?>
