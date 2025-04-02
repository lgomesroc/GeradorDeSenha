<?php
$senha = "minha_senha_secreta";
$hash = password_hash($senha, PASSWORD_BCRYPT);

echo "Senha: $senha\n";
echo "Hash: $hash\n";

if (password_verify($senha, $hash)) {
    echo "Senha verificada com sucesso!";
} else {
    echo "Falha na verificação da senha.";
}
