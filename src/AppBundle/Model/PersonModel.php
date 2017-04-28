<?php
namespace AppBundle\Model;

use AppBundle\Entity\Person;
use AppBundle\Hydrator\PersonHydrator;
use AppBundle\Utils\JsonFileHandler;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PersonModel implements PersonModelInterface
{
    /**
     * @var JsonFileHandler
     */
    private $jsonHandler;

    /**
     * PersonModel constructor - sets up JsonFileHandler
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $path = $container->getParameter('data_folder') . 'people.json';

        $this->jsonHandler = new JsonFileHandler($path);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        $list = [];
        
        $jsonData = $this->jsonHandler->read();
        
        foreach ($jsonData as $item) {
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
        $jsonData = $this->jsonHandler->read();

        $results = array_filter(
            $jsonData,
            function ($item) use ($id) {
                return $item->id == $id;
            }
        );

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
        $jsonData = $this->jsonHandler->read();

        $last = end($jsonData);

        $key = $last->id + 1;

        $item = (object) [
            'id' => $key,
            'firstname' => $person->getFirstname(),
            'lastname' => $person->getLastname(),
        ];

        array_push($jsonData, $item);

        $this->jsonHandler->write($jsonData);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Person $person): void
    {
        $jsonData = $this->jsonHandler->read();

        $results = array_map(
            function ($item) use ($person) {
                if ($person->getId() == $item->id) {
                    return (object) [
                        'id' => $person->getId(),
                        'firstname' => $person->getFirstname(),
                        'lastname' => $person->getLastname()
                    ];
                } else {
                    return $item;
                }
            },
            $jsonData
        );

        $this->jsonHandler->write($results);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): void
    {
        $jsonData = $this->jsonHandler->read();

        $results = array_filter(
            $jsonData,
            function ($item) use ($id) {
                return $item->id != $id;
            }
        );

        $this->jsonHandler->write(array_values($results));
    }
}
