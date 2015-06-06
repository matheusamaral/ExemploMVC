/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe de gerenciamento cliente servidor
 * @returns {GCS}
 */
function GCS(){
    
    //Array de conexões abertas
    var conexoes = new Array();
    
    /**
     * 
     * @param {type} url Local de requisição do servidor e parametros.
     * @param {type} tipo POST ou GET
     * @param {type} dados A serem processados no servidor.
     * @param {type} acao Qual ação tomar após conectar e receber resposta
     * @param {type} error Qual ação tomar caso não consiga conectar ao servidor.
     * @returns {Boolean}
     */
    this.conectar = function(url, tipo, dados, acao, error){        
        if(dados != null){
              conexoes[conexoes.length - 1] = $.ajax({
                    type: tipo,
                    url: url,
                    cache: false,
                    data: dados,
                    success: acao,
                    error: error                    
                });
        }else{            
          conexoes[conexoes.length - 1] = $.ajax({            
                url: url,           
                success: acao,
                error: error
            });       
        }
 
                return false;
    };
           
}

var GCS = new GCS();