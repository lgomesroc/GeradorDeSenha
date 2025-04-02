<?php

use PHPUnit\Framework\TestCase;

class NotFoundRouteTest extends TestCase
{
    public function testNotFoundRoute()
    {
        $url = 'http://localhost:9000/invalid-route';
        $response = @file_get_contents($url);

        // Exibir a resposta para diagnóstico
        var_dump($response);

        // Verificar se a resposta não é nula
        $this->assertNotNull($response, 'A resposta da rota /invalid-route é nula.');

        $data = json_decode($response, true);

        // Verificar se os dados retornados são válidos
        $this->assertIsArray($data, 'Os dados retornados não são um array.');
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Route not found', $data['error']);
    }
}
