<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class ListPasswordsTest extends TestCase
{
    private $testData;

    protected function setUp(): void
    {
        // Carregar dados do arquivo YAML
        $this->testData = Yaml::parseFile(__DIR__ . '/test-data.yaml');
    }

    public function testListPasswords()
    {
        $url = 'http://localhost:9000/list-passwords';
        $response = file_get_contents($url);

        // Exibir a resposta para diagnóstico
        var_dump($response);

        // Verificar se a resposta não é nula
        $this->assertNotNull($response, 'A resposta da rota /list-passwords é nula.');

        $data = json_decode($response, true);

        // Verificar se os dados retornados são válidos
        $this->assertIsArray($data, 'Os dados retornados não são um array.');
        $this->assertArrayHasKey('passwords', $data);
        $this->assertTrue(is_array($data['passwords']));
    }
}
