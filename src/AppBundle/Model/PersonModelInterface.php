<?php
namespace AppBundle\Model;

use AppBundle\Entity\Person;

interface PersonModelInterface
{
    /**
     * @return Person[]
     */
    public function all(): array;

    /**
     * @param int $id
     * @return Person
     */
    public function get(int $id): Person;

    /**
     * @param Person $person
     * @return void
     */
    public function add(Person $person): void;

    /**
     * @param Person $person
     * @return void
     */
    public function update(Person $person): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id);
}
