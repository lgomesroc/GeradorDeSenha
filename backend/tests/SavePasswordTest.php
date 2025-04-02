<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class SavePasswordTest extends TestCase
{
    private $testData;

    protected function setUp(): void
    {
        // Carregar dados do arquivo YAML
        $this->testData = Yaml::parseFile(__DIR__ . '/test-data.yaml');
    }

    public function testSavePassword()
    {
        $url = 'http://localhost:9000/save-password';
        $password = $this->testData['save-password']['valid-password'];
        $payload = json_encode(['password' => $password]);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json",
                'content' => $payload
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        // Exibir a resposta para diagnóstico
        var_dump($response);

        // Verificar se a resposta não é nula
        $this->assertNotNull($response, 'A resposta da rota /save-password é nula.');

        $data = json_decode($response, true);

        // Verificar se os dados retornados são válidos
        $this->assertIsArray($data, 'Os dados retornados não são um array.');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Password saved successfully!', $data['message']);
    }
}
