<?php
namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Person;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    /** @var Person */
    private $sut;

    protected function setUp()
    {
        $this->sut = new Person();
    }

    public function testId()
    {
        $id = 123;

        $setterResult = $this->sut->setId($id);

        $this->assertSame($id, $this->sut->getId());
        $this->assertSame($this->sut, $setterResult);
    }

    public function testFirstname()
    {
        $name = 'Chris';

        $setterResult = $this->sut->setFirstname($name);

        $this->assertSame($name, $this->sut->getFirstname());
        $this->assertSame($this->sut, $setterResult);
    }

    public function testLastname()
    {
        $name = 'Hodgson';

        $setterResult = $this->sut->setLastname($name);

        $this->assertSame($name, $this->sut->getLastname());
        $this->assertSame($this->sut, $setterResult);
    }
}
