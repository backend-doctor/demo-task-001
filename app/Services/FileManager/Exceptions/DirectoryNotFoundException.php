<?php

namespace App\Services\FileManager\Exceptions;

class DirectoryNotFoundException extends \Exception
{
    public function __construct(string $directory)
    {
        parent::__construct(sprintf('directory "%s" not found', $directory));
    }
}
