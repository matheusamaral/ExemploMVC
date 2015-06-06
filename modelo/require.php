<?php

if (!(function_exists('__autoload'))) {

    function __autoload($classe) {
        if (!(class_exists($classe))) {
            $pastas = array('../controle', 'modelo', '../bd');
            foreach ($pastas as $pasta) {
                if (file_exists("{$pasta}/{$classe}.class.php")) {
                    require_once "{$pasta}/{$classe}.class.php";
                }
            }
        }
    }

}
?>
