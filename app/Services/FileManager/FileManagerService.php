<?php

namespace App\Services\FileManager;

use App\Interfaces\FileRepositoryInterface;
use App\Services\FileManager\Interfaces\FileManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\ConnectionResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Models\File as FileModel;

class FileManagerService implements FileManager
{
    public function __construct(
        private readonly Filesystem $storage,
        private readonly FileRepositoryInterface $repository
    ) {}

    public function uploadFile(File $file, string $path): FileModel
    {
        return $this->repository->store($file, $this->storage->putFile($path, $file));
    }

    public function uploadFiles(array $files, string $path, ConnectionResolverInterface $databaseManager): bool
    {
        $result = [];
        try {
            $databaseManager->beginTransaction();
            foreach ($files as $file) {
                $result[$this->storage->putFile($path, $file)] = $file;
            }
            $result = $this->repository->storeArray($result);
            $databaseManager->commit();
            return $result;
        } catch (\Throwable $th) {
            foreach ($result as $file) {
                $this->storage->delete($path);
            }
            $databaseManager->rollBack();
            throw $th;
        }
    }
}
