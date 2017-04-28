<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $backup;

    protected function setUp()
    {
        $this->client = static::createClient();

        $container = $this->client->getKernel()->getContainer();

        $this->path = $container->getParameter('data_folder') . 'people.json';

        $this->backup = $this->path . '.backup';

        if (file_exists($this->backup)) {
            unlink($this->backup);
        }

        if (file_exists($this->path)) {
            copy($this->path, $this->backup);
        }
    }

    protected function tearDown()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }

        if (file_exists($this->backup)) {
            copy($this->backup, $this->path);
            unlink($this->backup);
        }
    }

    private function assertIsIndex($crawler)
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('List of People', $crawler->filter('#content h1')->text());
    }
    
    public function testBaseUrl()
    {
        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/');

        $this->assertIsIndex($crawler);
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/person');

        $this->assertIsIndex($crawler);
    }

    public function testAddFormGet()
    {
        $crawler = $this->client->request('GET', '/person/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Add Person', $crawler->filter('#content h1')->text());
    }

    public function testAddFormPost()
    {
        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/person/new');

        $form = $crawler->selectButton('add-person')->form();

        $crawler = $this->client->submit($form, [
            'person[firstname]' => 'New',
            'person[lastname]' => 'Person'
        ]);

        $this->assertIsIndex($crawler);
    }

    public function testUpdate()
    {
        $crawler = $this->client->request('GET', 'person/5/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Update Person', $crawler->filter('#content h1')->text());
    }

    public function testUpdatePost()
    {
        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', 'person/5/edit');

        $form = $crawler->selectButton('update-person')->form();

        $crawler = $this->client->submit($form, [
            'person[firstname]' => 'Updated',
            'person[lastname]' => 'Person'
        ]);

        $this->assertIsIndex($crawler);
    }

    public function testDeletePost()
    {
        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/person');

        $form = $crawler->selectButton('delete-person')->form();

        $crawler = $this->client->submit($form);

        $this->assertIsIndex($crawler);
    }
}
