<?php
namespace AppBundle\Model;

use AppBundle\Entity\Person;
use AppBundle\Hydrator\PersonHydrator;
use AppBundle\Utils\JsonFileHandler;
use InvalidArgumentException;

class PersonModel implements PersonModelInterface
{
    /**
     * @var JsonFileHandler
     */
    private $jsonHandler;

    /**
     * @var object[]
     */
    private $jsonData;

    /**
     * PersonModel constructor - gets the People data from the Json Data Source
     */
    public function __construct()
    {
        $this->jsonHandler = new JsonFileHandler();

        $this->jsonData = $this->jsonHandler->read($this->getJsonDataSource());
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        $list = [];

        foreach($this->jsonData as $item) {
            $list[] = (new PersonHydrator())->hydrate($item, new Person());
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function get(int $id): Person
    {
        $results = array_filter($this->jsonData, function($item) use($id) {
            return $item->id == $id;
        });

        $person = reset($results);

        if (!$person) {
            throw new InvalidArgumentException('Person does not exist with id ' . $id);
        }

        return (new PersonHydrator())->hydrate($person, new Person());
    }

    /**
     * {@inheritdoc}
     */
    public function add(Person $person): void
    {
        $last = end($this->jsonData);

        $key = $last->id + 1;

        $item = (object) [
            'id' => $key,
            'firstname' => $person->getFirstname(),
            'lastname' => $person->getLastname(),
        ];

        array_push($this->jsonData, $item);

        $this->jsonHandler->write($this->getJsonDataSource(), $this->jsonData);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Person $person): void
    {
        $results = array_map(
            function($item) use($person) {
                if ($person->getId() == $item->id) {
                    return (object) [
                        'id' => $person->getId(),
                        'firstname' => $person->getFirstname(),
                        'lastname' => $person->getLastname()
                    ];
                } else {
                    return $item;
                }
            }, $this->jsonData
        );

        $this->jsonHandler->write($this->getJsonDataSource(), $results);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): void
    {
        $results = array_filter($this->jsonData, function($item) use($id) {
            return $item->id != $id;
        });

        $this->jsonHandler->write($this->getJsonDataSource(), array_values($results));
    }

    /**
     * @return string
     */
    private function getJsonDataSource(): string
    {
        return 'people.json';
    }
}
