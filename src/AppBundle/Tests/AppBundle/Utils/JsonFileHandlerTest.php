<?php
namespace AppBundle\Tests\Utils;

use AppBundle\Utils\JsonFileHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsonFileHandlerTest extends WebTestCase
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $backup;

    /**
     * @var string
     */
    private $invalid;

    protected function setUp()
    {
        $client = $this->createClient();

        $container = $client->getKernel()->getContainer();

        $this->path = $container->getParameter('data_folder') . 'people.json';

        $this->invalid = $container->getParameter('data_folder') . 'invalid.json';

        $this->backup = $this->path . '.backup';

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

    public function testInstantiateWithValidPath()
    {
        new JsonFileHandler($this->path);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage File not found with path: 'invalid path'
     */
    public function testInstantiateWithInvalidPath()
    {
        new JsonFileHandler('invalid path');
    }

    public function testReadValidJson()
    {
        (new JsonFileHandler($this->path))->read();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Json could not be decoded
     */
    public function testReadInvalidJson()
    {
        (new JsonFileHandler($this->invalid))->read();
    }

    public function testWrite()
    {
        $data = ['test-data'];

        (new JsonFileHandler($this->path))->write($data);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /File is not writable with path:/
     */
    public function testWriteToReadOnlyFile()
    {
        $data = ['test-data'];

        chmod($this->path, 0444);

        (new JsonFileHandler($this->path))->write($data);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /File is not readable with path:/
     */
    public function testReadUnReadableFile()
    {
        chmod($this->path, 0333);

        (new JsonFileHandler($this->path))->read();
    }
}
