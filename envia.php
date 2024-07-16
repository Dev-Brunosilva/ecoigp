<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletando os dados do formulário
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $whatsapp = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $assunto = isset($_POST['assunto']) ? $_POST['assunto'] : '';

    // Verificando se os campos estão preenchidos
    if (empty($nome) || empty($whatsapp) || empty($email) || empty($assunto)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Verificando o upload do arquivo
    if (isset($_FILES['curriculo'])) {
        $nome_arquivo = $_FILES['curriculo']['name'];
        $temp_name = $_FILES['curriculo']['tmp_name'];
        $tipo_arquivo = $_FILES['curriculo']['type'];
        $error_arquivo = $_FILES['curriculo']['error'];

        // Verificar se houve algum erro no upload
        if ($error_arquivo !== UPLOAD_ERR_OK) {
            echo "Erro no upload do arquivo. Código de erro: $error_arquivo";
            exit();
        }

        // Verifica se o arquivo é PDF ou DOC/DOCX
        if (($tipo_arquivo == "application/pdf") || ($tipo_arquivo == "application/msword") || ($tipo_arquivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")) {
            // Diretório onde será salvo o currículo
            $diretorio = "curriculos/";

            // Verificar se o diretório existe e tem permissões de escrita
            if (!is_dir($diretorio)) {
                if (!mkdir($diretorio, 0777, true)) {
                    echo "Erro: Não foi possível criar o diretório $diretorio.";
                    exit();
                }
            }

            if (!is_writable($diretorio)) {
                echo "Erro: O diretório $diretorio não tem permissões de escrita.";
                exit();
            }

            // Movendo o currículo para o diretório desejado
            if (move_uploaded_file($temp_name, $diretorio . $nome_arquivo)) {
                // Enviando email com os dados do formulário e currículo anexado
                $destino = "dpecosolucoes@outlook.com"; // Altere para o seu endereço de email

                $mensagem = "Nome: $nome\n";
                $mensagem .= "WhatsApp: $whatsapp\n";
                $mensagem .= "Email: $email\n";
                $mensagem .= "Assunto: $assunto\n";

                // Enviando o email
                $headers = "From: $email\r\n";
                $headers .= "Reply-To: $email\r\n";
                $headers .= "Content-type: text/plain; charset=utf-8\r\n";

                // Adicionar logs para debug
                error_log("Headers: $headers");
                error_log("Mensagem: $mensagem");

                if (mail($destino, $assunto, $mensagem, $headers)) {
                    echo "Formulário enviado com sucesso!";
                } else {
                    // Registrar erro ao enviar o email
                    $error = error_get_last();
                    echo "Erro ao enviar o formulário. Detalhes do erro: " . print_r($error, true);
                }
            } else {
                echo "Erro ao enviar o currículo. Tente novamente mais tarde.";
            }
        } else {
            echo "Formato de currículo inválido. Envie um PDF, DOC ou DOCX.";
        }
    } else {
        echo "Por favor, envie o currículo.";
    }
} else {
    header("Location: formulario.html");
    exit();
}
?>



