<?php
namespace AppBundle\Hydrator;

use AppBundle\Entity\Person;

class PersonHydrator implements PersonHydratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($data, Person $person): Person
    {
        if(isset($data->id)) {
            $person->setId($data->id);
        }

        if(isset($data->firstname)) {
            $person->setFirstname($data->firstname);
        }

        if(isset($data->lastname)) {
            $person->setLastname($data->lastname);
        }

        return $person;
    }
}