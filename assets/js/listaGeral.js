var query = location.search.slice(1);
var partes = query.split('&');
var datas = {};
partes.forEach(function (parte) {
  var chaveValor = parte.split('=');
  var chave = chaveValor[0];
  var valor = chaveValor[1];
  datas[chave] = valor;
});

var idUser = datas.idUser;
linhasPorPagina = 10;
paginaAtual = 1;

function carregarPagina(pagina) {
  datas.pagina = pagina;
  datas.limite = linhasPorPagina;

  $.ajax({
    type: "POST",
    url: "http://localhost/nina/php/listaGeral.php",
    data: datas,
    dataType: "json",
    success: function(retorno) {
      var lista = retorno.dados;
      var totalPaginas = retorno.totalPaginas;
      var html = '';

      lista.forEach(function(item) {
        var funcionario = item.funcionrio || '';
        var vinculo = item.vnculo || '';
        var cargo = item.cargo || '';
        var equipamento = item.equipamento || '-';
        var matricula = item.matrcula || '-';
        var data_admisso = item.data_admisso || '';

        html += '<tr class="linhas">' +
                  '<td>' + funcionario + '</td>' +
                  '<td>' + vinculo + '</td>' +
                  '<td>' + cargo + '</td>' +
                  '<td>' + equipamento + '</td>' +
                  '<td>' + matricula + '</td>' +
                  '<td>' + data_admisso + '</td>' +
                  '<td class="text-right">' +
                    '<a href="verGeral.html?idUser=' + idUser + '&funcionrio=' + encodeURIComponent(funcionario) + '">Ver</a>&nbsp;|&nbsp;' +
                    '<a href="edit_geral.html?idUser=' + idUser + '&funcionrio=' + encodeURIComponent(funcionario) + '">Editar</a>' +
                  '</td>' +
                '</tr>';
      });

      document.getElementById("listaGeral").innerHTML = html;
      atualizarControles(pagina, totalPaginas);
    },
    error: function(xhr) {
      alert('Erro ao carregar os dados');
      console.error(xhr.responseText);
    }
  });
}

function atualizarControles(paginaAtual, totalPaginas) {
  var html = '';
  if (paginaAtual > 1) {
    html += '<button onclick="carregarPagina(' + (paginaAtual - 1) + ')">Anterior</button> ';
  }

  html += '<strong>Página ' + paginaAtual + ' de ' + totalPaginas + '</strong>';

  if (paginaAtual < totalPaginas) {
    html += ' <button onclick="carregarPagina(' + (paginaAtual + 1) + ')">Próxima</button>';
  }

  document.getElementById("paginacao").innerHTML = html;
}

// Carrega a primeira página ao iniciar
carregarPagina(1);
