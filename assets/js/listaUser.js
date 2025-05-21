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
    url: "http://localhost/lerin/php/listaUserTabela.php", 
    contentType: false,
    cache: false,
    dataType: "json",
    data: datas,
    processData:false,
    success: function(retorno){
        console.log(retorno);
        for(var i = 0; i<retorno.length; i++){
            
            var idUser          = retorno[i].idUser;
            var primeiro_nome   = retorno[i].primeiro_nome;
            var segundo_nome    = retorno[i].segundo_nome;
            var email           = retorno[i].email;
            var data_cadastro   = retorno[i].data_cadastro;

            document.getElementById("listaUser").innerHTML += '<tr><td>'+idUser+'</td><td>'+primeiro_nome+'</td><td>'+email+'</td><td>'+data_cadastro+'</td><td class="text-right"><a href="editar_user.html?idUser='+idUser+'">editar</a></td></tr>';
        }
       
    },
      error: function(xhr, status, error) {
      alert('erro')
      alert(xhr.responseText);
    }
  }); 
