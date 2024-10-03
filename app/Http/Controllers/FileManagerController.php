<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileManager\UploadFilesRequest;
use App\Services\FileManager\Interfaces\FileManager;
use Illuminate\Database\ConnectionResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileManagerController extends Controller
{
    public function __construct(private readonly FileManager $service) {}
    public function uploadFiles(UploadFilesRequest $request, ConnectionResolverInterface $databaseManager): JsonResponse
    {
        if (!isset($request->all()['files'])) {
            return response()->json($this->service->uploadFile($request->all()['file'], $request->path, $databaseManager));
        }
        return response()->json([
            'success' => true,
            'data' => $this->service->uploadFiles($request->all()['files'], $request->path, $databaseManager)
        ]);
    }
}
