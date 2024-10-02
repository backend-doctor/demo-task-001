<?php

namespace App\Services\FileManager\Interfaces;

use Illuminate\Http\File;

interface DirectoryManager
{
    public function createDirectory(string $directory): bool;
    public function deleteDirectory(string $directory): bool;
    public function renameDirectory(string $from, string $to): bool;
}
