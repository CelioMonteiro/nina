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

linhasPorPagina = 50;
paginaAtual = 1;

  var datas = {};
    var linhasPorPagina = 50;
    
    function busca(value, targetSelector) {
      var termo = value.toLowerCase();

      $(targetSelector).each(function () {
        var texto = $(this).text().toLowerCase();
        if (texto.indexOf(termo) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }

function carregarPagina(pagina) {
  datas.pagina = pagina;
  datas.limite = linhasPorPagina;

  $.ajax({
    type: "GET",
    url: "http://localhost/nina/php/listaGeral.php",
    data: datas,
    dataType: "json",
    success: function (retorno) {
      var lista = retorno.dados;
      var totalPaginas = retorno.totalPaginas;
      var html = '';

      lista.forEach(function (item) {
        var funcionario = item.funcionrio || '';
        var vinculo = item.vnculo || '';
        var cargo = item.cargo || '';
        var equipamento = item.equipamento || '-';
        var matricula = item.matrcula || '-';
        var data_admisso = item.data_admisso || '-';

        html += '<tr class="linhas">';
        html += '<td>' + funcionario + '</td>';
        html += '<td>' + vinculo + '</td>';
        html += '<td>' + cargo + '</td>';
        html += '<td>' + equipamento + '</td>';
        html += '<td>' + matricula + '</td>';
        html += '<td>' + data_admisso + '</td>';
        html += '<td class="text-right">';
        html += '<a href="verGeral.html?idUser=' + encodeURIComponent(idUser) + '&funcionrio=' + encodeURIComponent(funcionario) + '">Ver</a>';
        html += ' | ';
        html += '<a href="edit_geral.html?idUser=' + encodeURIComponent(idUser) + '&funcionrio=' + encodeURIComponent(funcionario) + '">Editar</a>';
        html += '</td></tr>';
      });

      $('#listaGeral').html(html);
      atualizarControles(pagina, totalPaginas); // 🔥 isso aqui faz a mágica voltar
    },
    error: function (xhr) {
      alert('Erro ao carregar os dados');
      console.error(xhr.responseText);
    }
  });
}

function atualizarControles(paginaAtual, totalPaginas) {
  var html = '';
  for (var i = 1; i <= totalPaginas; i++) {
    if (i === paginaAtual) {
      html += '<strong style="margin: 0 5px;">' + i + '</strong>';
    } else {
      html += '<a href="#" class="pagina-link" data-pagina="' + i + '" style="margin: 0 5px;">' + i + '</a>';
    }
  }
  $('#paginacao').html(html);
}

$(document).ready(function () {
  carregarPagina(1);

  $(document).on('keyup', '#search', function () {
    busca($(this).val(), '.linhas');
  });

  $(document).on('click', '.pagina-link', function (e) {
    e.preventDefault();
    var pagina = $(this).data('pagina');
    carregarPagina(pagina);
  });
});

// Carrega a primeira página ao iniciar
carregarPagina(1);
