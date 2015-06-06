/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe cliente Aluno, contem as ações relacionadas ao aluno.
 * @returns {Aluno}
 */
function Aluno(){
    
    //Armazena o elemento inserido
    this.aluno = null;
    
    //Armazena a lista de elementos
    this.alunos = null;
    
    /**
     * Envia para o servidor os dados do formulário     
     */
    this.matricularAluno = function(){
        Aluno.aluno = $('#matricularAluno').serialize();
        GCS.conectar('principal/principal.php?c=ControleAluno&o=matricularAluno', 'post', Aluno.aluno, this.acaoMatricularAluno, this.erroMatricularAluno);
    };
    
    /**
     * Quando a aplicação consegue se conectar com o servidor e obtem resposta.
     * @param {type} data resposta do servidor
     * @returns {undefined}
     */
    this.acaoMatricularAluno = function(data){ 
        console.log(data);
        var valida = JSON.parse(data);
        if(valida.success){            
            $('#msg_mat').text('Os dados foram enviados para o servidor e salvo com sucesso!');
            Aluno.aluno = null;
            $('#matricularAluno').each(function(){
                this.reset();   //Here form fields will be cleared.
            });
            Aluno.carregarAlunos();
        }else{            
            $('#msg_mat').text('Os dados foram enviados para o servidor mas não foi possível salvar os dados!');
        }
    };
    
    /**
     * Quando a aplicação cliente não consegue se conectar com a aplicação servidor.
     * @param {type} data resposta do servidor
     * @returns {undefined}
     */
    this.erroMatricularAluno = function(data){
        $('#msg_mat').text('Os dados foram salvos na memória da aplicação cliente, quando restabelecer a conexão será enviado para o servidor.');
    };
    
    /**
     * Verifica se existe dados em memória para ser enviado para o servidor.
     * @returns {undefined}
     */
    this.verificarDadosEmMemoria = function(){
       if(Aluno.aluno != null){
           GCS.conectar('principal/principal.php?c=ControleAluno&o=matricularAluno', 'post', Aluno.aluno, this.acaoMatricularAluno, this.erroMatricularAluno);           
           Aluno.aluno = null;
       } 
    };
    
    /**
     * Carrega todos os alunos cadastrados.
     * @returns {undefined}
     */
    this.carregarAlunos = function(){   
        this.verificarDadosEmMemoria();
        var id = null;
        //console.log(Aluno.alunos);
        if(Aluno.alunos != null) id = Aluno.alunos.alunos[Aluno.alunos.alunos.length-1].id;
        else id = 0;
        GCS.conectar('principal/principal.php?c=ControleAluno&o=carregarAlunos&uid='+id, null, null, this.acaoCarregarAlunos, this.acaoErroCarregarAlunos);
    };
    
    /**
     * Apresenta alunos carregados na lista.
     * @returns {undefined}
     */
    this.acaoCarregarAlunos = function(data){   
        console.log(data);
        var dados = JSON.parse(data);        
        if(dados.success){            
            var elementos;           
            if(dados.alunos.length > 0) Aluno.alunos = dados;
            for(var contaAluno=0;contaAluno < dados.alunos.length;contaAluno++){
               elementos = $('<tr>').append(
                            $('<td>').text(dados.alunos[contaAluno].nome),
                            $('<td>').text(dados.alunos[contaAluno].matricula),
                            $('<td>').text(dados.alunos[contaAluno].momentoCadastro)
                        );              
                            $('#lista-alunos').append(elementos);
            }                       
            $('#lista-alunos').append(elementos);
            $('#carregando img').css('display', 'none');
            $("#msg").css('color', 'red').text('Atualizado!');
            $('#vcliente').addClass('vc');
            $('#vmodelo').addClass('vm');
            $('#vpersistencia').addClass('vp');
        }else{
            //console.log('erro!');
            $('#vcliente').addClass('vc');
            $('#vmodelo').addClass('vm');
            $('#vpersistencia').removeClass('vp');
            $("#msg").css('color', 'red').text('Não foi possível encontrar os dados!');
        }
    };
    
    /**
     * Quando a aplicação cliente não consegue se comunicar com a aplicação servidor.
     * @returns {undefined}
     */
    this.acaoErroCarregarAlunos = function(data){ 
        console.log(data);
        $('#vcliente').addClass('vc');
        $('#vmodelo').removeClass('vm');
        $('#vpersistencia').removeClass('vp');
        var arrayMensagens = new Array('Estamos tentando conectar...', 'Parece que você está sem conexão...', 'Ainda estamos tentando conectar...', 'Parece que vai demorar...', 'O sistema não está conseguindo conectar...');        
        $("#msg").css('color', '#000000').text(arrayMensagens[Math.floor((Math.random()*5)+0)]);
    };
        
}


/**
 *  É um pool de conexão 
 * @returns {Atualizar}
 */
function Atualizar(){    
    
    /**
     * Atualiza de tempo em tempo o conteúdo solicitado.
     * @param {String} tempo Define o tempo de intervalo de uma atualização para outra.
     * @returns {undefined}
     */
    this.pool = function(tempo){
        setInterval(function(){  
            $('#carregando img').css('display', 'block').attr('src', 'lib/img/carregando2.gif');
            $("#msg").css('color', '#000000').text('verificando...');
            Aluno.carregarAlunos();
        },tempo);        
    };    
    
}

Aluno = new Aluno();
Atualizar = new Atualizar();

Atualizar.pool(2000);