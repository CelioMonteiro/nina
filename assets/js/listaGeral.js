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
var idUserLista = 0
$.ajax({
    type: "POST",
    url: "http://localhost/nina/php/listaGeral.php",
    contentType: false,
    cache: false,
    dataType: "json",
    data: datas,
    processData:false,
    success: function(retorno){
        console.log(retorno);
        for(var i = 0; i<retorno.length; i++){
            
            var funcionario         = retorno[i].funcionrio;
            var vinculo              = retorno[i].vnculo;
            var cargo               = retorno[i].cargo;
            var equipamento         = retorno[i].equipamento;
            var matricula           = retorno[i].matrcula;
            var telefones           = retorno[i].telefones;
            var data_admisso        = retorno[i].data_admisso;
            var turno =retorno[i].turno;
            var endereo_bairro =retorno[i].endereo_bairro;
            var data_incio_e_fim_aviso_prvio = retorno[i].data_incio_e_fim_aviso_prvio
            var email               = retorno[i].email;
            var cpf                 = retorno[i].cpf;
            var telefones1          = retorno[i].telefones1;
            var obs                 = retorno[i].obs;

            
            document.getElementById("listaGeral").innerHTML += '<tr class="linhas"><td>'+funcionario+'</td><td>'+vinculo+'</td><td>'+cargo+'</td><td>'+equipamento+'</td><td>'+matricula+'</td><td>'+data_admisso+'</td><td class="text-right"><a href="user.html?idUser='+idUser+'&idUserLista='+idUserLista+'">Ver</a>&nbsp;|&nbsp;<a href="edit_user.html?idUser='+idUser+'&idUserLista='+idUserLista+'">editar</a></td></tr>';
        }
       
    },
      error: function(xhr, status, error) {
      alert('erro')
      alert(xhr.responseText);
    }
  }); 
