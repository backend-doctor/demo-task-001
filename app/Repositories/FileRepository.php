<?php

namespace App\Repositories;

use App\Interfaces\FileRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Models\File as FileModel;

class FileRepository implements FileRepositoryInterface
{
    public function store(File $file, string $path): FileModel
    {
        return FileModel::create([
            'name' => $file->name,
            'path' => $path
        ]);
    }

    public function storeArray(array $files): bool
    {
        $data = [];
        foreach ($files as $path => $file) {
            $data[] = [
                'name' => $file->name,
                'path' => $path
            ];
        }
        return FileModel::insert($data);
    }
}
