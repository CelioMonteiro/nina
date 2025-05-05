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
    type: "POST",
    url: "http://localhost/nina/php/listar_User.php",
    contentType: false,
    cache: false,
    dataType: "json",
    data: datas,
    processData:false,
    success: function(retorno){
        console.log(retorno);
        for(var i = 0; i<retorno.length; i++){
            
            var idUserLista = retorno[i].idUser;
            var nome        = retorno[i].nome;
            var telefone    = retorno[i].telefone;
            var email       = retorno[i].email;
            var endereco    = retorno[i].endereco;
            var status      = retorno[i].status;
            var tipo        = retorno[i].tipo;
            var data_cadastro =retorno[i].data_cadastro;

            if(status == 1){
              status = 'Ativo' 
            }else{
              status = 'Desativado'
            }

            if(tipo == 0){
              tipo = 'Admin'
            }else{
              tipo = 'Consultor'
            }

            document.getElementById("listaUsers").innerHTML += '<tr class="linhas"><td>'+nome+'</td><td>'+email+'</td><td>'+telefone+'</td><td>'+status+'</td><td>'+tipo+'</td><td>'+data_cadastro+'</td><td class="text-right"><a href="user.html?idUser='+idUser+'&idUserLista='+idUserLista+'">Ver</a>&nbsp;|&nbsp;<a href="edit_user.html?idUser='+idUser+'&idUserLista='+idUserLista+'">editar</a></td></tr>';
        }
       
    },
      error: function(xhr, status, error) {
      alert('erro')
      alert(xhr.responseText);
    }
  }); 
