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
    url: "http://localhost/Slerin/lerin/php/listaVendas.php?idRepresentante="+idUser,
    contentType: false,
    cache: false,
    dataType: "json",
    data: datas,
    processData:false,
    success: function(retorno){
        
        for(var i = 0; i<retorno.length; i++){

            let idVenda             = retorno[i].idVenda;
            let idCliente           = retorno[i].idCliente;
            let idRepresentante     = retorno[i].idRepresentante;
            let idProduto           = retorno[i].idProduto;
            let quantidade          = retorno[i].quantidade;
            let descricao           = retorno[i].descricao;
            let data_cadastro       = retorno[i].data_cadastro;
            let nome_cliente        = retorno[i].nome_cliente
            let nome_produto        = retorno[i].nome_produto;
             
            document.getElementById("listaVendas").innerHTML += '<tr><td>'+idVenda+'</td><td>'+nome_cliente+'</td><td>'+nome_produto+'</td><td>'+quantidade+'</td><td>'+data_cadastro+'</td><td class="text-right"><a href="">editar</a></td></tr>';
        }
       
    },
      error: function(xhr, status, error) {
      alert('erro')
      alert(xhr.responseText);
    }
  }); 


