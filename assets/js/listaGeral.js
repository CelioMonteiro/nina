// Remova qualquer outro conteúdo deste arquivo, deixando apenas este código
var query = location.search.slice(1);
var partes = query.split('&');
var datas = {};
partes.forEach(function (parte) {
  var chaveValor = parte.split('=');
  var chave = chaveValor[0];
  var valor = chaveValor[1];
  datas[chave] = valor;
});

var idUser = datas.idUser; // Pegar o idUser do URL uma única vez

var linhasPorPagina = 50;
var paginaAtual = 1; // Inicializa a página atual

function mostrarSpinner() {
  $('#loadingSpinner').show();
}

function esconderSpinner() {
  $('#loadingSpinner').hide();
}

function carregarPagina(pagina) {
  mostrarSpinner(); // Mostra o spinner antes da requisição

  // Ajusta o objeto 'params' para incluir os parâmetros de paginação e busca
  var params = {
    pagina: pagina,
    limite: linhasPorPagina
  };

  var termoBusca = $('#search').val();
  // Validação básica: se o termo de busca tiver menos de 2 caracteres, não envia o parâmetro de busca
  if (termoBusca && termoBusca.length >= 2) {
    params.busca = termoBusca;
  } else if (termoBusca && termoBusca.length > 0 && termoBusca.length < 2) {
    // Poderíamos dar um feedback ao usuário aqui, mas por enquanto, apenas não enviamos a busca
    // Ex: alert('Digite pelo menos 2 caracteres para buscar.');
  }

  if (idUser) {
    params.idUser = idUser;
  }

  $.ajax({
    type: "GET",
    url: "http://localhost/nina/php/listaGeral.php",
    data: params, // Envia os parâmetros ajustados
    dataType: "json",
    success: function (retorno) {
      esconderSpinner(); // Esconde o spinner após o sucesso

      var lista = retorno.dados;
      var totalPaginas = retorno.totalPaginas;
      paginaAtual = pagina; // Atualiza a página atual após o sucesso da requisição
      var html = '';

      if (lista.length === 0) {
        html += '<tr><td colspan="7" class="text-center">Nenhum resultado encontrado.</td></tr>';
      } else {
        lista.forEach(function (item) {
          // Garante que os campos existam antes de usar
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
          // Certifique-se de que o idUser é passado para os links
          html += '<a href="verGeral.html?idUser=' + encodeURIComponent(idUser) + '&funcionrio=' + encodeURIComponent(funcionario) + '">Ver</a>';
          html += ' | ';
          html += '<a href="edit_geral.html?idUser=' + encodeURIComponent(idUser) + '&funcionrio=' + encodeURIComponent(funcionario) + '">Editar</a>';
          html += '</td></tr>';
        });
      }

      $('#listaGeral').html(html);
      atualizarControles(paginaAtual, totalPaginas);
    },
    error: function (xhr) {
      esconderSpinner(); // Esconde o spinner mesmo em caso de erro
      alert('Erro ao carregar os dados. Verifique o console para mais detalhes.');
      console.error('Status:', xhr.status);
      console.error('Resposta do servidor:', xhr.responseText);
      console.error('Erro:', xhr.statusText);
    }
  });
}

// Melhoria na Paginação: Mostrar um subconjunto de páginas
function atualizarControles(paginaAtual, totalPaginas) {
  var html = '';
  var maxPaginasVisiveis = 5; // Quantidade máxima de botões de página para exibir
  var inicio = Math.max(1, paginaAtual - Math.floor(maxPaginasVisiveis / 2));
  var fim = Math.min(totalPaginas, inicio + maxPaginasVisiveis - 1);

  // Ajusta o início se o fim for o limite e não há páginas suficientes no início
  if (fim - inicio + 1 < maxPaginasVisiveis) {
    inicio = Math.max(1, fim - maxPaginasVisiveis + 1);
  }

  // Botão "Anterior"
  if (paginaAtual > 1) {
    html += '<a href="#" class="pagina-link" data-pagina="' + (paginaAtual - 1) + '" style="margin: 0 5px;"><< Anterior</a>';
  }

  // Botões numéricos
  if (inicio > 1) {
    html += '<a href="#" class="pagina-link" data-pagina="1" style="margin: 0 5px;">1</a>';
    if (inicio > 2) {
      html += '<span style="margin: 0 5px;">...</span>';
    }
  }

  for (var i = inicio; i <= fim; i++) {
    if (i === paginaAtual) {
      html += '<strong style="margin: 0 5px;">' + i + '</strong>';
    } else {
      html += '<a href="#" class="pagina-link" data-pagina="' + i + '" style="margin: 0 5px;">' + i + '</a>';
    }
  }

  if (fim < totalPaginas) {
    if (fim < totalPaginas - 1) {
      html += '<span style="margin: 0 5px;">...</span>';
    }
    html += '<a href="#" class="pagina-link" data-pagina="' + totalPaginas + '" style="margin: 0 5px;">' + totalPaginas + '</a>';
  }

  // Botão "Próxima"
  if (paginaAtual < totalPaginas) {
    html += '<a href="#" class="pagina-link" data-pagina="' + (paginaAtual + 1) + '" style="margin: 0 5px;">Próxima >></a>';
  }

  $('#paginacao').html(html);
}


$(document).ready(function () {
  carregarPagina(1); // Carrega a primeira página ao iniciar

  if ($('#search').length) {
    $(document).on('keyup', '#search', function () {
      // Sempre volta para a primeira página dos resultados da busca ao digitar
      carregarPagina(1);
    });
  }

  $(document).on('click', '.pagina-link', function (e) {
    e.preventDefault();
    var pagina = $(this).data('pagina');
    carregarPagina(pagina);
  });
});