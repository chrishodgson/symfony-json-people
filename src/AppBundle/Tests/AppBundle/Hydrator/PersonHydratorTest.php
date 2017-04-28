<?php
namespace AppBundle\Tests\Hydrator;

use AppBundle\Entity\Person;
use AppBundle\Hydrator\PersonHydrator;
use AppBundle\Hydrator\PersonHydratorInterface;

class PersonHydratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var PersonHydrator */
    private $sut;

    protected function setUp()
    {
        $this->sut = new PersonHydrator();
    }

    public function testShouldImplementAppropriateInterface()
    {
        $this->assertInstanceOf(PersonHydratorInterface::class, $this->sut);
    }

    public function testShouldHydrate()
    {
        $id = 123;
        $firstname = 'Chris';
        $lastname = 'Hodgson';

        $data = (object) [
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname
        ];

        $person = $this->sut->hydrate($data, new Person());

        $this->assertSame($id, $person->getId());
        $this->assertSame($firstname, $person->getFirstname());
        $this->assertSame($lastname, $person->getLastname());
    }
}
