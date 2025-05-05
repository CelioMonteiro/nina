<?php 
header("Access-Control-Allow-Origin: *");

$emaildestinatario 	= 'contato@recuperandovidas24h.com.br';
$nome 				= $_POST['nome'];
$email 				= $_POST['emailCliente'];
$assunto 			= $_POST['assunto'];

$mensagemHTML 		= '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style>body{font-family:Arial,sans-serif;background-color:#f2f2f2;padding:20px}.container{max-width:600px;margin:0 auto;background-color:#fff;padding:40px;border-radius:5px}h1{color:#333}p{color:#555}.message{margin-top:20px}.button{display:inline-block;background-color:#4caf50;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px}</style></head><body><div class="container"><h1>Resposta de Formulário de Contato do Site</h1><p>Enviado por: '.$_POST['nome'].'</p><p>Email: '.$_POST['emailCliente'].'</p><p>'.$_POST['mensagem'].'</p><div class="message"><p>Entre em contato mais breve possível!</p><a href="#" class="button">'.$email.'</a></div></div></body></html>';

$emailsender = 'contato@recuperandovidas24h.com.br';

$headers  = "MIME-Version: 1.1\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: contato@recuperandovidas24h.com.br\r\n"; // remetente
$headers .= "Return-Path: contato@recuperandovidas24h.com.br\r\n"; // return-path

$quebra_linha = "";

if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,"-r".$emailsender)){ 
	// Se for Postfix
    $headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
    mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
}
?>
<script type="text/javascript">alert('Contato enviado com sucesso!Em breve entraremos em cotato')</script>
<script type="text/javascript">window.location.href = "http://recuperandovidas24h.com.br/contact.html"</script>