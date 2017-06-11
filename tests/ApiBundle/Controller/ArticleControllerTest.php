<?php

namespace Tests\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $client = static::createClient();

        // Avec un paramètre manquant
        $client->request('POST', '/article/', ['title' => 'ajout article sans contenu']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('messages', $response);
        $this->assertTrue(in_array('Certains paramètres sont manquants.', $response['messages']));

        // Avec des données non valide
        $client->request('POST', '/article/', [
            'title' => 'ajout article avec champ invalide',
            'content' => 'coucou'
        ]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('messages', $response);
        $this->assertTrue(in_array('Le contenu doit contenir au moins 30 caractères', $response['messages']));

        // Tout est bon
        $article = [
            'title' => 'Titre de test',
            'content' => 'Contenu de test avec assez de caractères',
            'comments' => []
        ];
        $client->request('POST', '/article/', [
            'title' => $article['title'],
            'content' => $article['content']
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('article', $response);
        $this->assertTrue($response['article']['title'] == $article['title']);
        $this->assertTrue($response['article']['content'] == $article['content']);
        $this->assertTrue($response['article']['comments'] == $article['comments']);
        $this->assertTrue($response['article']['id'] > 0);
    }
}
