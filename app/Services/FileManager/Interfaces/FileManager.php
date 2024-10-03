<?php

namespace App\Services\FileManager\Interfaces;

use Illuminate\Database\ConnectionResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Models\File as FileModel;


interface FileManager
{
    public function uploadFile(File $file, string $path): FileModel;
    public function uploadFiles(array $files, string $path, ConnectionResolverInterface $databaseManager): bool;
}
