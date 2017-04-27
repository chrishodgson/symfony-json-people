<?php
namespace AppBundle\Model;

use AppBundle\Entity\Person;

interface PersonModelInterface
{
    /**
     * @return Person[]
     */
    function all(): array;

    /**
     * @param int $id
     * @return Person
     */
    function get(int $id): Person;

    /**
     * @param Person $person
     * @return void
     */
    function add(Person $person): void;

    /**
     * @param Person $person
     * @return void
     */
    function update(Person $person): void;

    /**
     * @param int $id
     * @return void
     */
    function delete(int $id);
}