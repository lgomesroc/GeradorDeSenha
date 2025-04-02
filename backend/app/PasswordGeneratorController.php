<?php

class PasswordGeneratorController
{
    public static function generatePassword()
    {
        // Definição dos caracteres permitidos
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';

        // Comprimento da senha
        $passwordLength = 12;

        // Gerar a senha
        $password = substr(str_shuffle($characters), 0, $passwordLength);

        // Retornar a senha como JSON
        echo json_encode(['password' => $password]);
    }
}
