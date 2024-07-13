<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletando os dados do formulário
    $nome = $_POST['nome'];
    $whatsapp = $_POST['whatsapp'];
    $email = $_POST['email'];
    $assunto = $_POST['assunto'];

    // Debugging: verificar os dados recebidos
    var_dump($_POST);
    var_dump($_FILES);

    // Processando o envio do currículo
    $nome_arquivo = $_FILES['curriculo']['name'];
    $temp_name = $_FILES['curriculo']['tmp_name'];
    $tipo_arquivo = $_FILES['curriculo']['type'];

    // Verifica se o arquivo é PDF ou DOC/DOCX
    if (($tipo_arquivo == "application/pdf") || ($tipo_arquivo == "application/msword") || ($tipo_arquivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")) {

        // Diretório onde será salvo o currículo
        $diretorio = "curriculos/";

        // Movendo o currículo para o diretório desejado
        if (move_uploaded_file($temp_name, $diretorio . $nome_arquivo)) {

            // Enviando email com os dados do formulário e currículo anexado
            $destino = "brunosurf1005@gmail.com"; // Altere para o seu endereço de email

            $mensagem = "Nome: $nome\n";
            $mensagem .= "WhatsApp: $whatsapp\n";
            $mensagem .= "Email: $email\n";
            $mensagem .= "Assunto: $assunto\n";

            // Enviando o email
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-type: text/plain; charset=utf-8\r\n";

            // Debugging: verificar os cabeçalhos do e-mail
            print_r($headers);

            if (mail($destino, $assunto, $mensagem, $headers)) {
                echo "<script>alert('Formulário enviado com sucesso!');</script>";
                echo "<script>window.location = 'formulario.html';</script>";
            } else {
                // Debugging: verificar o erro ao enviar e-mail
                $error = error_get_last()['message'];
                echo "<script>alert('Erro ao enviar o formulário: $error');</script>";
                echo "<script>window.location = 'formulario.html';</script>";
            }

        } else {
            echo "<script>alert('Erro ao enviar o currículo. Tente novamente mais tarde.');</script>";
            echo "<script>window.location = 'formulario.html';</script>";
        }
    } else {
        echo "<script>alert('Formato de currículo inválido. Envie um PDF, DOC ou DOCX.');</script>";
        echo "<script>window.location = 'formulario.html';</script>";
    }
} else {
    header("Location: formulario.html");
    exit();
}
?>
