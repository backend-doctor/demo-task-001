<?php

namespace App\Services\FileManager\Exceptions;

class DirectoryExistsException extends \Exception
{
    public function __construct(string $directory)
    {
        parent::__construct(sprintf('directory "%s" exists', $directory));
    }
}
