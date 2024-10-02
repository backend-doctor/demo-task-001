<?php

namespace App\Services\FileManager\Interfaces;

interface FileManager
{
    public function createDirectory(string $directory): bool;
    public function deleteDirectory(string $directory): bool;
    public function renameDirectory(string $from, string $to): bool;
}
