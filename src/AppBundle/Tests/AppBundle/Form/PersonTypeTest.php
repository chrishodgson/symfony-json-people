<?php
namespace AppBundle\Tests\Form;

use AppBundle\Entity\Person;
use AppBundle\Form\PersonType;
use Symfony\Component\Form\Test\TypeTestCase;

class PersonTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'firstname' => 'Chris',
            'lastname' => 'Hodgson',
        ];

        $form = $this->factory->create(PersonType::class);

        $person = new Person();
        $person->setFirstname('Chris');
        $person->setLastname('Hodgson');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($person, $form->getData());

        $view = $form->createView();

        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
