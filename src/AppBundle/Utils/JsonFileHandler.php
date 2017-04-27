<?php
namespace AppBundle\Utils;

class JsonFileHandler
{
    /**
     * the relative path of the data folder
     */
    const DATA_FOLDER = __DIR__ . '/../../../data';

    /**
     * @param $filename
     * @return object[]|null
     */
    public function read(string $filename)
    {
        $data = file_get_contents($this->getPath($filename));

        return json_decode($data, false);
    }

    /**
     * @param $filename
     * @param $data
     * @return void
     */
    public function write($filename, $data): void
    {
        $encoded = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents($this->getPath($filename), $encoded);
    }

    /**
     * @param string $filename
     * @throws \RuntimeException
     * @return string
     */
    private function getPath(string $filename): string
    {
        $path = realpath(self::DATA_FOLDER . '/' . $filename);

        if (!$path) {
            throw new \RuntimeException('File not found in path: ' . self::DATA_FOLDER . '/' . $filename);
        }

        return $path;
    }
}

