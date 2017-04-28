<?php
namespace AppBundle\Tests\Model;

use AppBundle\Entity\Person;
use AppBundle\Model\PersonModel;
use AppBundle\Model\PersonModelInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonModelTest extends WebTestCase
{
    /**
     * @var PersonModel
     */
    private $sut;

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
        $client = $this->createClient();

        $container = $client->getKernel()->getContainer();

        $this->path = $container->getParameter('data_folder') . 'people.json';

        $this->backup = $this->path . '.backup';

        $this->sut = new PersonModel($container);

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

    public function testShouldImplementAppropriateInterface()
    {
        $this->assertInstanceOf(PersonModelInterface::class, $this->sut);
    }

    public function testAll()
    {
        $expectedCount = 5;

        $people = $this->sut->all();

        $this->assertSame($expectedCount, count($people));
    }

    public function testGet()
    {
        $expectedId = 1;

        $person = $this->sut->get($expectedId);

        $this->assertSame($expectedId, $person->getId());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage: Person does not exist with id 3
     */
    public function testGetInvalidId()
    {
        $expectedId = 9999;

        $this->sut->get($expectedId);
    }

    public function testUpdate()
    {
        $expectedId = 1;
        $expectedFirstname = 'Chris';
        $expectedLastname = 'Hodgson';

        $person = new Person();
        $person->setId($expectedId);
        $person->setFirstname($expectedFirstname);
        $person->setLastname($expectedLastname);

        $this->sut->update($person);

        $result = $this->sut->get($expectedId);

        $this->assertSame($expectedId, $result->getId());
        $this->assertSame($expectedFirstname, $result->getFirstname());
        $this->assertSame($expectedLastname, $result->getLastname());
    }

    public function testAdd()
    {
        $expectedId = 6;
        $expectedFirstname = 'Chris';
        $expectedLastname = 'Hodgson';

        $person = new Person();
        $person->setFirstname($expectedFirstname);
        $person->setLastname($expectedLastname);

        $this->sut->add($person);

        $result = $this->sut->get($expectedId);

        $this->assertSame($expectedId, $result->getId());
        $this->assertSame($expectedFirstname, $result->getFirstname());
        $this->assertSame($expectedLastname, $result->getLastname());
    }

    public function testDelete()
    {
        $expectedId = 1;
        $expectedCount = 4;

        $this->sut->delete($expectedId);

        $people = $this->sut->all();

        $this->assertSame($expectedCount, count($people));
    }
}
