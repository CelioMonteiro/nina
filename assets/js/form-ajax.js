//Enviar Estado
jQuery(document).ready(function(){
  // Evento Submit do formulário
$('#filtroEstado').submit(function() {
 
  var dadosForm = jQuery( this ).serialize();
  dadosForm = dadosForm.split('&');

  var dados = new FormData(this);
   
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "https://www.superlerin.com.br/boa/php/FiltroEstado.php",
    data:  dados,
    contentType: false,
    cache: false,
    processData:false,
    success: function(retorno){
      
      var idUser = retorno.idUser
      var idEstado = retorno.idEstado
      var idCidade = retorno.idCidade
     // console.log(idUser)
     window.location.href = 'categoria.html?idUser='+idUser+'&cidade='+idCidade; 
    
    },
      error: function(xhr, status, error) {
      alert(xhr.responseText);
    }

  });
    return false;
});
});

//enviar cidade / estado
jQuery(document).ready(function(){
    // Evento Submit do formulário
  $('#filtroCidade').submit(function() {
   
    var dadosForm = jQuery( this ).serialize();
    dadosForm = dadosForm.split('&');
  
    var dados = new FormData(this);
     
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "https://www.superlerin.com.br/boa/php/filtroCidade.php",
      data:  dados,
      contentType: false,
      cache: false,
      processData:false,
      success: function(retorno){
        
        var idUser = retorno.idUser
        var idCidade = retorno.idCidade
       // console.log(idUser)
        window.location.href = 'filtroCategoria.html?idUser='+idUser+'&cidade='+idCidade;  	
      
      },
        error: function(xhr, status, error) {
        alert(xhr.responseText);
      }

    });
      return false;
  });
});


//EDITAR USER
jQuery(document).ready(function(){
    // Evento Submit do formulário
  $('#editUser').submit(function() {
    var dados = new FormData(this);
    //console.log(dados)
    $.ajax({
      type: "POST",
      url: "http://localhost/nina/php/edit_user.php",
      data:  dados,
      contentType: false,
      cache: false,
      processData:false,
      success: function(retorno){
        console.log(retorno)
        alert(retorno)
        window.location.reload()
        
      },
        error: function(xhr, status, error) {
        alert(xhr.responseText);
      }
    });
      return false;
  });
});

jQuery(document).ready(function(){
  $('#editGeral').submit(function() {
    var dados = new FormData(this);

    $.ajax({
      type: "POST",
      url: "http://localhost/nina/php/edit_geral.php",
      data: dados,
      contentType: false,
      cache: false,
      processData: false,
      success: function(retorno){
        console.log(retorno);

        // Verifica se o retorno contém a palavra "sucesso"
        if (retorno.toLowerCase().includes("sucesso")) {
          alert("Dados atualizados com sucesso!");
          //window.location.reload(); // recarrega a página apenas se o update foi bem-sucedido
        } else {
          alert("Atenção: " + retorno); // mostra o erro retornado pelo PHP
        }
      },
      error: function(xhr, status, error) {
        console.error("Erro na requisição:", xhr.responseText);
        alert("Erro ao enviar dados: " + xhr.responseText);
      }
    });

    return false; // Impede o envio tradicional do formulário
  });
});


//EDITAR CLIENTE
jQuery(document).ready(function(){
  // Evento Submit do formulário
$('#editarCliente').submit(function() {
  var dados = new FormData(this);
  //console.log(dados)
  $.ajax({
    type: "POST",
    url: "http://localhost/lerin/php/editar_cliente.php",
    data:  dados,
    contentType: false,
    cache: false,
    processData:false,
    success: function(retorno){
      console.log(retorno)
      alert('dados atualizados com sucesso!')
      window.location.reload()
    },
      error: function(xhr, status, error) {
      
      alert(xhr.responseText);
    }
  });
    return false;
});
});

//EDITAR PRODUTO
jQuery(document).ready(function(){
  // Evento Submit do formulário
$('#editarProduto').submit(function() {
  var dados = new FormData(this);
  //console.log(dados)
  $.ajax({
    type: "POST",
    url: "http://localhost/lerin/php/editar_produto.php",
    data:  dados,
    contentType: false,
    cache: false,
    processData:false,
    success: function(retorno){
      console.log(retorno)
      alert('dados atualizados com sucesso!')
      window.location.reload()
    },
      error: function(xhr, status, error) {
      
      alert(xhr.responseText);
    }
  });
    return false;
});
});

//FORM GRAVAR-USER
jQuery(document).ready(function(){
    jQuery('#gravarUser').submit(function(){
      var dados = jQuery( this ).serialize();
      var dados = new FormData(this);
      jQuery.ajax({
        type: "POST",
        url: "http://localhost/lerin/php/cadastro_user.php",
        data: dados,
        contentType: false,
        cache: false,
        processData:false,
        success: function( data )
        
        {
         let texto =  data
         alert(texto)
         window.location.reload()
         //window.location.replace("login.html")
        },

        error: function(xhr, status, error) {
          //$('#formUser').hide();
          //document.getElementById('msgErro_cadastro').innerHTML = 'OPS! tivemos um erro :(';
          $('#msgSucess_logar').show(); 
            alert(xhr.responseText);
        }
      });
      
      return false;
    });
  });

//FORM GRAVAR-VENDA
jQuery(document).ready(function(){
  jQuery('#gravarVenda').submit(function(){
    var dados = jQuery( this ).serialize();
    var dados = new FormData(this);
    jQuery.ajax({
      type: "POST",
      url: "http://localhost/lerin/php/criar_venda.php",
      data: dados,
      contentType: false,
      cache: false,
      processData:false,
      success: function( data )
      
      {
       alert('cadastro realizado com sucesso')
       window.location.reload()
       //window.location.replace("login.html")
      },

      error: function(xhr, status, error) {
        //$('#formUser').hide();
        //document.getElementById('msgErro_cadastro').innerHTML = 'OPS! tivemos um erro :(';
        $('#msgSucess_logar').show(); 
          alert(xhr.responseText);
      }
    });
    
    return false;
  });
});
//FORM GRAVAR-USUARIO
jQuery(document).ready(function(){
  jQuery('#gravarCliente').submit(function(){
    var dados = jQuery( this ).serialize();
    var dados = new FormData(this);
    jQuery.ajax({
      type: "POST",
      url: "http://localhost/nina/php/criar_user.php",
      data: dados,
      contentType: false,
      cache: false,
      processData:false,
      success: function( data )
      
      {
       alert('cadastro realizado com sucesso')
       window.location.reload()
       //window.location.replace("login.html")
      },

      error: function(xhr, status, error) {
        //$('#formUser').hide();
        //document.getElementById('msgErro_cadastro').innerHTML = 'OPS! tivemos um erro :(';
        $('#msgSucess_logar').show(); 
          alert(xhr.responseText);
      }
    });
    
    return false;
  });
});

//FORM GRAVAR GERAL 
jQuery(document).ready(function(){
  jQuery('#gravarGeral').submit(function(e){
    e.preventDefault();
    var dados = new FormData(this);

    jQuery.ajax({
      type: "POST",
      url: "http://localhost/nina/php/criar_geral.php",
      data: dados,
      contentType: false,
      cache: false,
      processData: false,
      dataType: "json", // <--- importante!
      success: function(response) {
        alert(response.mensagem); // Exibe mensagem do PHP
        window.location.reload();
      },
      error: function(xhr) {
        try {
          const resposta = JSON.parse(xhr.responseText);
          alert("Erro: " + resposta.erro);
        } catch (e) {
          alert("Erro inesperado do servidor.");
          console.log("Resposta bruta:", xhr.responseText);
        }
      }
    });
  });
});

//FORM GRAVAR-PRODUTO
jQuery(document).ready(function(){
  jQuery('#gravarProduto').submit(function(){
    var dados = jQuery( this ).serialize();
    var dados = new FormData(this);
    jQuery.ajax({
      type: "POST",
      url: "http://localhost/lerin/php/criar_produto.php",
      data: dados,
      contentType: false,
      cache: false,
      processData:false,
      success: function( data )
      
      {
       alert('cadastro realizado com sucesso')
       window.location.reload()
       //window.location.replace("login.html")
      },

      error: function(xhr, status, error) {
        //$('#formUser').hide();
        //document.getElementById('msgErro_cadastro').innerHTML = 'OPS! tivemos um erro :(';
        $('#msgSucess_logar').show(); 
          alert(xhr.responseText);
      }
    });
    
    return false;
  });
});
//LOGAR USER UPDATE
jQuery(document).ready(function() { // Adicione esta linha
    // Seleciona o formulário de login pelo ID
    const loginForm = document.getElementById('loginForm'); // Certifique-se de que seu formulário HTML tem id="loginForm"
    const errorMessageDiv = document.getElementById('errorMessage'); // Opcional: Para exibir mensagens de erro ao usuário

    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault(); // Impede o comportamento padrão de submit do formulário (recarregar a página)

            // Limpa mensagens de erro anteriores
            if (errorMessageDiv) {
                errorMessageDiv.textContent = '';
                errorMessageDiv.style.display = 'none';
            }

            // Coleta os dados do formulário
            const email = loginForm.email.value;   // Assume que o input de email tem name="email"
            const senha = loginForm.senha.value;   // Assume que o input de senha tem name="senha"

            // Validação básica no cliente
            if (!email || !senha) {
                if (errorMessageDiv) {
                    errorMessageDiv.textContent = 'Por favor, preencha todos os campos.';
                    errorMessageDiv.style.display = 'block';
                }
                return;
            }

            try {
                const response = await fetch('http://localhost/nina/php/logar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}&senha=${encodeURIComponent(senha)}`
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({
                        erro: 'Erro de rede ou resposta inválida.'
                    }));
                    throw new Error(errorData.erro || `Erro HTTP: ${response.status} ${response.statusText}`);
                }

                const data = await response.json();
                console.log("Resposta do servidor:", data);

                if (data.idUser && data.idUser > 0) {
                    alert(`Bem-vindo(a), ${data.nome}! Login realizado com sucesso.`);
                    window.location.href = '../admin/tabela_geral.html?idUser=' + data.idUser; // Redireciona para a tabela geral com o idUser
                } else {
                    if (errorMessageDiv) {
                        errorMessageDiv.textContent = 'Email ou senha incorretos.';
                        errorMessageDiv.style.display = 'block';
                    }
                    console.log("Falha no login: ID de usuário inválido.");
                }

            } catch (error) {
                console.error("Erro na requisição de login:", error);
                if (errorMessageDiv) {
                    errorMessageDiv.textContent = `Ocorreu um erro: ${error.message}`;
                    errorMessageDiv.style.display = 'block';
                }
            }
        });
    } else {
        console.error("Elemento com ID 'loginForm' não encontrado no DOM.");
    }
}); // Adicione esta linha de fechamento do ready function

//FORM LOGAR erro- USER
jQuery(document).ready(function(){
  jQuery('#logarUser-erro').submit(function(){
    var dados = jQuery( this ).serialize();
    jQuery.ajax({
      type: "GET",
      url: "https://www.superlerin.com.br/pinn/php/logar.php",
      data: dados,
      success: function(data)
      {
        var user      = data.split(',');
        var idUser    = user[0];
        var userName  = user[1];
        var userEmail = user[2];
        idUser = idUser.split('>');
        idUser = idUser[1];
        
        if(idUser > 0){
          $('#btnvoltar2').show();
          $('#idUser').val(idUser);
          $('#banner').hide();
  		    $('#sucesso-login').show();
          $('#erro-login').hide();
          setTimeout( function () {window.location.replace("index-limpo.html?idUser="+idUser+"&tipocat=1") }, 3000);
        }else{
          $('#banner').hide();
  		    $('#sucesso-login').hide();
          $('#erro-login').show();
          $('#email').val();
          $('#senha').val();
        }

      },
      error: function(xhr, status, error) {
        alert(xhr.responseText);
      }
    });
    
    return false;
  });
});


//FORM RECUPERAR SENHA - envio de pedido
jQuery(document).ready(function(){
  jQuery('#recuperar_senha').submit(function(){
    var dados = jQuery( this ).serialize();
    var email = dados.split("=");
    var emailuser = email[1];
    emailuser = emailuser.split("%40");
    var emailcompleto = emailuser[0]+'@'+emailuser[1];
    console.log(emailcompleto);
    jQuery.ajax({
      type: "GET",
      url: "https://www.superlerin.com.br/pinn/php/recuperar_senha.php?email="+emailuser,
      data: dados,
      success: function(data)
      {
        $('#perdeu-senha').hide();
        $('#sucesso-pedido-senha').show();
        
        var user      = data.split(',');
        var idUser    = user[0];
        var userName  = user[1];
        var userEmail = user[2];
        idUser = idUser.split('>');
        idUser = idUser[1];
        
        
        
      },
      error: function(xhr, status, error) {
        alert(xhr.responseText);
      }
    });
    
    return false;
  });
});

//FORM RECUPERAR SENHA - troca de senha
jQuery(document).ready(function(){
  jQuery('#recuperar_senha').submit(function(){
    var dados = jQuery( this ).serialize();
    jQuery.ajax({
      type: "POST",
      url: "https://www.superlerin.com.br/pinn/php/trocar_senha.php",
      data: dados,
      success: function(data)
      {
        console.log(data);
        
      },
      error: function(xhr, status, error) {
        alert(xhr.responseText);
      }
    });
    
    return false;
  });
});