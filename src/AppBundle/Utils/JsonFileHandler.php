<?php
namespace AppBundle\Utils;

class JsonFileHandler
{
    /**
     * @var string
     */
    private $path;

    /**
     * JsonFileHandler constructor - sets the path for the Json Data Source
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $this->convertPath($path);
    }

    /**
     * @throws \RuntimeException
     * @return object[]
     */
    public function read(): array
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException("File is not readable with path: '{$this->path}'");
        }

        $json = file_get_contents($this->path);

        return $this->decodeJson($json);
    }

    /**
     * @param $data
     * @throws \RuntimeException
     * @return void
     */
    public function write($data): void
    {
        if (!is_writable($this->path)) {
            throw new \RuntimeException("File is not writable with path: '{$this->path}'");
        }

        $encoded = $encoded = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents($this->path, $encoded);
    }

    /**
     * @param string $json
     * @throws \RuntimeException
     * @return object[]
     */
    private function decodeJson(string $json): array
    {
        $data = json_decode($json, false);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \RuntimeException("Json could not be decoded.");
        }

        return $data;
    }

    /**
     * Converts a relative path into a real path
     * @param string $relativePath
     * @throws \RuntimeException
     * @return string
     */
    private function convertPath(string $relativePath): string
    {
        $path = realpath($relativePath);

        if (!$path) {
            throw new \RuntimeException("File not found with path: '$relativePath'");
        }

        return $path;
    }
}
