<?php
namespace AppBundle\Hydrator;

use AppBundle\Entity\Person;

interface PersonHydratorInterface
{
    /**
     * @param mixed $data
     * @param Person $person
     * @return Person
     */
    public function hydrate($data, Person $person): Person;
}
