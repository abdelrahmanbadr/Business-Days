<?php

namespace App\Domain\Services;

use App\Exceptions\{FileNotFoundException, FileNotReadableException};
use App\Domain\Contracts\FileReaderInterface;

/**
 * Class JsonFileReaderService
 * @package App\Domain\Services
 *
 * This class will be responsible for reading file system content
 *
 */
class FileReaderService implements FileReaderInterface
{
    private $jsonFilePath;

    function __construct(string $jsonFilePath)
    {
        $this->jsonFilePath = $jsonFilePath;
    }

    /**
     * @return string
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    public function readFileContent(): string
    {
        if (!file_exists($this->jsonFilePath)) {
            throw new FileNotFoundException(sprintf('The file "%s" does not exist', $this->jsonFilePath));
        }
        if (!$content = file_get_contents($this->jsonFilePath)) {
            throw new FileNotReadableException(sprintf('The file "%s" is not readable', $this->jsonFilePath));
        }
        return $content;
    }

}