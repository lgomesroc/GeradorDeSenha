<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$attempts = 5; // Número máximo de tentativas
while ($attempts > 0) {
    try {
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'mysql', // Nome do serviço do banco no docker-compose
            'database'  => 'password_generator_db',
            'username'  => 'password_user', // Substituir por password_user
            'password'  => 'password123',   // Substituir pela senha correta
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        // Configurar como global
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        // Retorna o Capsule em caso de sucesso (sem ecoar mensagens)
        return $capsule;
    } catch (Exception $e) {
        // Mensagens para log interno, sem exibição direta no output HTTP
        error_log("Tentativa de conexão ao banco falhou... tentando novamente em 3 segundos.");
        sleep(3); // Aguarda 3 segundos antes de tentar novamente
        $attempts--;
        if ($attempts === 0) {
            die("Erro: não foi possível conectar ao banco de dados após várias tentativas.\n");
        }
    }
}

return $capsule;
