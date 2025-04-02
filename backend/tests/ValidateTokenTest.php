<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class ValidateTokenTest extends TestCase
{
    private $testData;

    protected function setUp(): void
    {
        // Carregar dados do arquivo YAML
        $this->testData = Yaml::parseFile(__DIR__ . '/test-data.yaml');
    }

    public function testValidateToken()
    {
        $token = $this->testData['validate-token']['valid-token'];
        $url = 'http://localhost:9000/validate-token';

        $options = [
            'http' => [
                'header' => "Authorization: Bearer $token"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        // Diagnóstico - Exibir a resposta para análise
        var_dump($response);

        // Verificar se a resposta não é nula
        $this->assertNotNull($response, 'A resposta da rota /validate-token é nula.');

        $data = json_decode($response, true);

        // Verificar se os dados retornados são válidos
        $this->assertIsArray($data, 'Os dados retornados não são um array.');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Token is valid!', $data['message']);
    }
}
