<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Configuração de CORS - deve estar no topo do arquivo
header('Access-Control-Allow-Origin: http://localhost:8081'); // Especifique a origem permitida
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json'); // Define o tipo de resposta como JSON

// Lidar com solicitações OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0); // Responde com 200 OK para OPTIONS
}

require '../vendor/autoload.php';

// Inicializar o banco de dados
require '../app/database.php';

// Chave secreta do JWT
$key = 'seu_segredo_super_secreto';

// Rota inicial
Flight::route('/', function() {
    echo json_encode(['message' => 'Bem-vindo ao backend do projeto gerar senha!']);
});

// Testar a conexão com o banco de dados
Flight::route('/db-test', function() {
    try {
        $tables = Capsule::connection()->select('SHOW TABLES');
        $tableNames = array_map(function($table) {
            return array_values((array)$table)[0];
        }, $tables);
        echo json_encode(['tables' => $tableNames]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => 'Failed to connect to the database.', 'details' => $e->getMessage()]));
    }
});

// Rota para gerar uma senha (agora protegida por autenticação)
Flight::route('GET /generate-password', function() use ($key) {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        Flight::halt(401, json_encode(['error' => 'Authorization header is required.']));
        return;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $passwordLength = 12;
        $password = substr(str_shuffle($characters), 0, $passwordLength);

        echo json_encode(['password' => $password]);
    } catch (Exception $e) {
        Flight::halt(401, json_encode(['error' => 'Invalid or expired token.', 'details' => $e->getMessage()]));
    }
});

// Rota para recuperar senha
Flight::route('POST /recover-password', function() {
    $request = Flight::request()->data;

    if (!isset($request->email) || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        Flight::halt(400, json_encode(['error' => 'E-mail inválido ou ausente.']));
        return;
    }

    $email = $request->email;

    try {
        // Verificar se o e-mail existe na tabela de usuários
        $user = Capsule::table('users')->where('email', $email)->first();

        if (!$user) {
            Flight::halt(404, json_encode(['error' => 'E-mail não encontrado.']));
            return;
        }

        // Simular envio de e-mail de recuperação
        // Em um cenário real, utilize um serviço como SMTP, Mailgun, etc.
        $recoveryLink = "http://localhost:8081/reset-password?email=$email&token=" . bin2hex(random_bytes(16));

        echo json_encode(['message' => 'E-mail de recuperação enviado com sucesso!', 'link' => $recoveryLink]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => 'Erro ao processar solicitação de recuperação de senha.', 'details' => $e->getMessage()]));
    }
});

// Rota para salvar uma senha no banco
Flight::route('POST /save-password', function() {
    $request = Flight::request()->data;

    if (!isset($request->password) || !is_string($request->password)) {
        Flight::halt(400, json_encode(['error' => 'Password is required and must be a valid string.']));
        return;
    }

    $password = $request->password;

    // Criptografar a senha com bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        Capsule::table('passwords')->insert([
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        echo json_encode(['message' => 'Password saved successfully!']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => 'Failed to save password.', 'details' => $e->getMessage()]));
    }
});

// Rota para verificar a senha
Flight::route('POST /verify-password', function() {
    $request = Flight::request()->data;

    if (!isset($request->password) || !isset($request->hashedPassword)) {
        Flight::halt(400, json_encode(['error' => 'Password and hashedPassword are required.']));
        return;
    }

    $password = $request->password;
    $hashedPassword = $request->hashedPassword;

    if (password_verify($password, $hashedPassword)) {
        echo json_encode(['message' => 'Password verified successfully!']);
    } else {
        echo json_encode(['message' => 'Password verification failed!']);
    }
});

// Rota para listar todas as senhas salvas
Flight::route('GET /list-passwords', function() {
    try {
        $passwords = Capsule::table('passwords')->get();
        echo json_encode(['passwords' => $passwords]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => 'Failed to retrieve passwords.', 'details' => $e->getMessage()]));
    }
});

// Rota para registrar usuário
Flight::route('POST /register', function() {
    $request = Flight::request()->data;

    if (!isset($request->username) || !isset($request->password)) {
        Flight::halt(400, json_encode(['error' => 'Username and password are required.']));
        return;
    }

    $hashedPassword = password_hash($request->password, PASSWORD_BCRYPT);

    try {
        Capsule::table('users')->insert([
            'username' => $request->username,
            'password' => $hashedPassword
        ]);
        echo json_encode(['message' => 'User registered successfully!']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => 'Failed to register user.', 'details' => $e->getMessage()]));
    }
});

// Rota para login de usuário
Flight::route('POST /login', function() use ($key) {
    $request = Flight::request()->data;

    if (!isset($request->username) || !isset($request->password)) {
        Flight::halt(400, json_encode(['error' => 'Username and password are required.']));
        return;
    }

    $user = Capsule::table('users')->where('username', $request->username)->first();

    if (!$user || !password_verify($request->password, $user->password)) {
        Flight::halt(401, json_encode(['error' => 'Invalid credentials.']));
        return;
    }

    // Gerar token JWT
    $payload = [
        'iss' => 'localhost',
        'aud' => 'localhost',
        'iat' => time(),
        'exp' => time() + 3600,
        'username' => $user->username
    ];

    $token = JWT::encode($payload, $key, 'HS256');
    echo json_encode(['token' => $token]);
});

// Rota para validar um token JWT
Flight::route('GET /validate-token', function() use ($key) {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        Flight::halt(400, json_encode(['error' => 'Authorization header is required.']));
        return;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        echo json_encode(['message' => 'Token is valid!', 'data' => $decoded]);
    } catch (Exception $e) {
        Flight::halt(401, json_encode(['error' => 'Invalid token.', 'details' => $e->getMessage()]));
    }
});

// Configurações especiais do Flight
Flight::map('notFound', function() {
    header('Access-Control-Allow-Origin: http://localhost:8081');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Route not found']);
});

Flight::map('error', function(Exception $ex) {
    header('Access-Control-Allow-Origin: http://localhost:8081');
    header('Content-Type: application/json');
    echo json_encode(['error' => $ex->getMessage()]);
});

// Iniciar o Flight
Flight::start();
