const CHECK_SESSION_INTERVAL =  15 * 60 * 1000; // 15 minutos

  function verificarSessaoPeriodicamente() {
    setInterval(() => {
      $.ajax({
        type: "GET",
        url: "http://localhost/nina/php/validarUser.php",
        dataType: "json",
        success: function(retorno) {
          if (retorno.erro) {
            alert("Sessão expirada. Você será redirecionado para o login.");
            window.location.href = "http://localhost/nina/admin/login.html";
          } else {
            console.log("Sessão ainda ativa.");
          }
        },
        error: function(xhr, status, error) {
          console.error("Erro ao validar a sessão:", xhr.responseText);
        }
      });
    }, CHECK_SESSION_INTERVAL);
  }

  // Atualiza tempo de atividade com ações do usuário
  function monitorarInteracoes() {
    ['click', 'mousemove', 'keypress', 'scroll'].forEach(evt => {
      document.addEventListener(evt, () => {
        // Você pode futuramente atualizar um contador ou log se quiser
        console.log("Interação detectada, atividade mantida.");
      });
    });
  }

  $(document).ready(function() {
    verificarSessaoPeriodicamente();
    monitorarInteracoes();
  });