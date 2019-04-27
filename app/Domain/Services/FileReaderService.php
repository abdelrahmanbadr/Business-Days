<?php

namespace App\Domain\Services;

use App\Exceptions\{FileNotFoundException, FileNotReadableException};
use App\Domain\Contracts\FileReaderInterface;
use Illuminate\Support\Facades\Log;

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
            Log::error("The file $this->jsonFilePath  does not exist");
            throw new FileNotFoundException(sprintf('The file "%s" does not exist', $this->jsonFilePath));
        }
        if (!$content = file_get_contents($this->jsonFilePath)) {
            Log::error("The file $this->jsonFilePath is not readable");
            throw new FileNotReadableException(sprintf('The file "%s" is not readable', $this->jsonFilePath));
        }
        return $content;
    }

}