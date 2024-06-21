<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $assunto = $_POST['assunto'];

    // Configurações para enviar o email
    $to = 'brunojureia@hotmail.com'; // Insira aqui o seu endereço de email
    $subject = 'Novo contato pelo formulário ';
    $message = "Nome: $nome\n";
    $message .= "Idade: $idade\n\n";
    $message .= "Mensagem:\n$assunto";

    // Envia o email
    $headers = "From: $nome <$to>";
    if (mail($to, $subject, $message, $headers)) {
        echo '<p>Email enviado com sucesso!</p>';
    } else {
        echo '<p>Ocorreu um erro ao enviar o email.</p>';
    }
}
?>

