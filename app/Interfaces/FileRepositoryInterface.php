<?php

namespace App\Interfaces;

use App\Models\File as FileModel;
use Symfony\Component\HttpFoundation\File\File;

interface FileRepositoryInterface
{
    public function store(File $file, string $path): FileModel;
    public function storeArray(array $files): bool;
}
