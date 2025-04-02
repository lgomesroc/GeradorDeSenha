<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class GeneratePasswordTest extends TestCase
{
    private $testData;

    protected function setUp(): void
    {
        // Carregar dados do arquivo YAML
        $this->testData = Yaml::parseFile(__DIR__ . '/test-data.yaml');
    }

    public function testGeneratePassword()
    {
        $url = 'http://localhost:9000/generate-password';
        $response = file_get_contents($url);

        // Exibir a resposta para diagnóstico
        var_dump($response);

        // Verificar se a resposta não é nula
        $this->assertNotNull($response, 'A resposta da rota /generate-password é nula.');

        $data = json_decode($response, true);

        // Verificar se os dados retornados são válidos
        $this->assertIsArray($data, 'Os dados retornados não são um array.');
        $this->assertArrayHasKey('password', $data);
        $this->assertEquals($this->testData['generate-password']['length'], strlen($data['password']));
    }
}
