<?php

function tratarNome($nome) {
    return ucfirst(strtolower(trim($nome)));
}

function tratarEndereco($endereco) {
    return ucwords(strtolower(trim($endereco)));
}

function tratarTelefone($telefone, $database, &$errors) {
    $telefone = preg_replace('/\D/', '', $telefone);

    $sql = "SELECT id FROM produtor WHERE telefone = ?";
    $params = [$telefone];
    $check = $database->execute_query($sql, $params);

    if(count($check->results) > 0) {
        $errors['telefone'] = "Este telefone já está cadastrado.";
        return false;
    }

    if(strlen($telefone) === 11) {
        return $telefone;
    } elseif(strlen($telefone) === 10) {
        return $telefone;
    } else {
        $errors['telefone'] = "Telefone inválido. Use DDD + número.";
        return false;
    }
}

function tratarTelefoneAtualizacao($telefone, $idAtual, $database, &$errors) {
    $telefone = preg_replace('/\D/', '', $telefone);

    if(strlen($telefone) !== 10 && strlen($telefone) !== 11) {
        $errors['telefone'] = "Telefone inválido. Use DDD + número.";
        return false;
    }

    $sql = "SELECT id FROM produtor WHERE telefone = ? AND id != ?";
    $params = [$telefone, $idAtual];
    $check = $database->execute_query($sql, $params);


    if(count($check->results) > 0) {
        $errors['telefone'] = "Este telefone já está cadastrado em outro usuário.";
        return false;
    }

    return $telefone;
}


function formatarTelefone($telefone) {
    $telefone = preg_replace('/\D/', '', $telefone);
    if(strlen($telefone) === 11) {
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
    } elseif(strlen($telefone) === 10) {
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
    }
    return $telefone;
}


function tratarEmail($email, $database, &$errors) {
    $sql = "SELECT id FROM cooperativa WHERE email = ?
            UNION
            SELECT id FROM produtor WHERE email = ?";
    $params = [$email, $email];
    $check = $database->execute_query($sql, $params);

    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if(count($check->results) > 0) {
        $errors['email'] = "Este E-mail já está cadastrado.";
        return false;
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail inválido.";
        return false;
    }

    return strtolower($email);
}

function validarEmailAtualizacao($email, $idAtual, $tabelaOrigem, $database, &$errors) {
    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail inválido.";
        return false;
    }

    $sql = "SELECT id FROM cooperativa WHERE email = ? AND NOT (id = ? AND ? = 'cooperativa')
            UNION
            SELECT id FROM produtor WHERE email = ? AND NOT (id = ? AND ? = 'produtor')";
    
    $params = [$email, $idAtual, $tabelaOrigem, $email, $idAtual, $tabelaOrigem];
    $check = $database->execute_query($sql, $params);

    if(count($check->results) > 0) {
        $errors['email'] = "Este E-mail já está em uso por outro usuário.";
        return false;
    }

    return strtolower($email);
}


function tratarCPF($cpf, $database, &$errors) {
    $cpf = preg_replace('/\D/', '', $cpf);

    if(strlen($cpf) !== 11) {
        $errors['cpf'] = "CPF inválido.";
        return false;
    }

    $sql = "SELECT id FROM produtor WHERE cpf = ?";
    $params = [$cpf];
    $check = $database->execute_query($sql, $params);

    if(count($check->results) > 0) {
        $errors['cpf'] = "Este CPF já está cadastrado.";
        return false;
    }

    return $cpf;
}
function formatarCPF($cpf) {
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}



function validarCPFAtualizacao($cpf, $idAtual, $database, &$errors) {
    $cpf = preg_replace('/\D/', '', $cpf);

    if(strlen($cpf) !== 11) {
        $errors['cpf'] = "CPF inválido.";
        return false;
    }

    $sql = "SELECT id FROM produtor WHERE cpf = ? AND id != ?";
    $params = [$cpf, $idAtual];
    $check = $database->execute_query($sql, $params);

    if(count($check->results) > 0) {
        $errors['cpf'] = "Este CPF já está em uso por outro produtor.";
        return false;
    }

    return $cpf;
}


function validarCNPJAtualizacao($cnpj, $idAtual, $database, &$errors) {
    $cnpj = preg_replace('/\D/', '', $cnpj);

    if(strlen($cnpj) !== 14) {
        $errors['cnpj'] = "CNPJ inválido.";
        return false;
    }

    $sql = "SELECT id FROM cooperativa WHERE cnpj = ? AND id != ?";
    $params = [$cnpj, $idAtual];
    $check = $database->execute_query($sql, $params);

    if(count($check->results) > 0) {
        $errors['cnpj'] = "Este CNPJ já está em uso por outra cooperativa.";
        return false;
    }

    return $cnpj;
}


function tratarCNPJ($cnpj, $database, &$errors) {
    $cnpj = preg_replace('/\D/', '', $cnpj);

    if(strlen($cnpj) !== 14) {
        $errors['cnpj'] = "CNPJ inválido.";
        return false;
    }

    $sql = "SELECT id FROM cooperativa WHERE cnpj = ?";
    $params = [$cnpj];
    $check = $database->execute_query($sql, $params);

    if(count($check->results) > 0) {
        $errors['cnpj'] = "Este CNPJ já está cadastrado.";
        return false;
    }

    return $cnpj;
}


function formatarCNPJ($cnpj) {
    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
}



function confirmarSenha($senha, $confirmar_senha, &$errors) {
    if(strlen($senha) < 6) {
        $errors['senha'] = "sua senha precisa de no mínimo 6 digitos.";
        return false;
    }

    if($senha !== $confirmar_senha) {
        $errors['confirmar_senha'] = "As senhas não coincidem.";
        return false;
    }

    return password_hash($senha, PASSWORD_DEFAULT);
}


function tratarImage(&$errors) {
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $arquivo = $_FILES['imagem'];

        $nomeOriginal = $arquivo['name'];
        $tmpPath = $arquivo['tmp_name'];
        $tamanho = $arquivo['size'];

        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

        if(!in_array($extensao, $extensoesPermitidas)) {
            $errors['imagem'] = "Formato de imagem inválido.";
            return false;
        }

        if($tamanho > 2 * 1024 * 1024) {
            $errors['imagem'] = "Imagem muito grande. Máximo de 2MB.";
            return false;
        }

        $nomeFinal = uniqid('img_', true) . '.' . $extensao;

        $pastaRelativa = 'assets/img/imagens/';
        $pastaAbsoluta = dirname(__DIR__) . '/' . $pastaRelativa;

        if(!is_dir($pastaAbsoluta)) {
            mkdir($pastaAbsoluta, 0755, true);
        }

        $caminhoCompleto = $pastaAbsoluta . $nomeFinal;

        if(move_uploaded_file($tmpPath, $caminhoCompleto)) {
            return $pastaRelativa . $nomeFinal;
        } else {
            $errors['imagem'] = "Erro ao mover o arquivo para a pasta.";
            return false;
        }
    } else {
        $errors['imagem'] = "Nenhuma imagem foi enviada.";
        return false;
    }
}