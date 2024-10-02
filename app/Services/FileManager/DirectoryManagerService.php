<?php

namespace App\Services\FileManager;

use App\Services\FileManager\Exceptions\DirectoryExistsException;
use App\Services\FileManager\Exceptions\DirectoryNotFoundException;
use App\Services\FileManager\Interfaces\DirectoryManager;
use Illuminate\Contracts\Filesystem\Filesystem;

class DirectoryManagerService implements DirectoryManager
{
    public function __construct(private readonly Filesystem $storage) {}

    public function createDirectory(string $directory): bool
    {
        if (!$this->storage->exists($directory)) {
            return $this->storage->makeDirectory($directory);
        }
        throw new DirectoryExistsException($directory);
    }

    public function deleteDirectory(string $directory): bool
    {
        if ($this->storage->exists($directory)) {
            return $this->storage->deleteDirectory($directory);
        }
        throw new DirectoryNotFoundException($directory);
    }

    public function renameDirectory(string $from, string $to): bool
    {
        if ($this->storage->exists($from)) {
            return $this->storage->move($from, $to);
        }
        throw new DirectoryNotFoundException($from);
    }
}
