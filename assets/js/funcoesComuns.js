function sair() {
    $.ajax({
      type: "POST",
      url: "http://localhost/nina/php/logout.php",
      dataType: "json",
      success: function(retorno) {
        console.log("Sucesso:", retorno);
        window.location.href = "http://localhost/nina/admin/login.html";
      },
      error: function(xhr, status, error) {
        console.log("Erro AJAX:");
        console.log("Status:", status);
        console.log("Erro:", error);
        console.log("Response:", xhr.responseText);
        alert('Erro no AJAX. Veja o console.');
      }
    });
  }
  
function verformCliente()
{
document.getElementById("form_cliente").style.display = "block";
document.getElementById("listagem_cliente").style.display = "none"
document.getElementById("BtnNovo").style.display = "none"
document.getElementById("BtnListagem").style.display = "block"
}

function verListagemClientes()
{
document.getElementById("listagem_cliente").style.display = "block"
document.getElementById("form_cliente").style.display = "none"
document.getElementById("BtnNovo").style.display = "block"
document.getElementById("BtnListagem").style.display = "none"
}

function mostrarNomeRepresentante()
{
  var query = location.search.slice(1);
  var partes = query.split('&');
  var datas = {};
  partes.forEach(function (parte) {
    var chaveValor = parte.split('=');
    var chave = chaveValor[0];
    var valor = chaveValor[1];
    datas[chave] = valor;
  });

  var idUser = datas.idUser

  $.ajax({
      type: "GET",
      url: "http://localhost/nina/php/listaUser.php?idUser="+idUser,
      contentType: false,
      cache: false,
      dataType: "json",
      data: datas,
      processData:false,
      success: function(retorno){
          console.log(retorno);
          for(var i = 0; i<retorno.length; i++){
            
              var primeiro_nome = retorno[i].nome; 
              var telefone    = retorno[i].telefone;
              var email       = retorno[i].email;
              console.log(primeiro_nome);
              document.getElementById("mostraUser").innerHTML += '<input type="text" class="form-control" disabled="" placeholder="Company" value="'+primeiro_nome+'"><input type="hidden" value="'+idUser+'" name="idRepresentante" />';
          }
        
      },
        error: function(xhr, status, error) {
        alert('erro')
        alert(xhr.responseText);
      }
    }); 

}
mostrarNomeRepresentante()

function dadosUser()
{
var query = location.search.slice(1);
var partes = query.split('&');
var datas = {};
partes.forEach(function (parte) {
var chaveValor = parte.split('=');
var chave = chaveValor[0];
var valor = chaveValor[1];
datas[chave] = valor;
});

var idUser = datas.idUser

$.ajax({
    type: "GET",
    url: "http://localhost/lerin/php/listaUser.php?idUser="+idUser,
    contentType: false,
    cache: false,
    dataType: "json",
    data: datas,
    processData:false,
    success: function(retorno){
        for(var i = 0; i<retorno.length; i++){
            var primeiro_nome      = retorno[i].nome  
            
            
            document.getElementById("listaNomeUser").innerHTML += primeiro_nome;
        }
    
    },
    error: function(xhr, status, error) {
    alert('erro')
    alert(xhr.responseText);
    }
}); 
}
dadosUser()

